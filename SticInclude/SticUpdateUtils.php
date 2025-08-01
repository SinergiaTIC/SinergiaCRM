<?php
/**
 * repair file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * repair program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * repair program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * repair program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

require_once('include/utils/sugar_file_utils.php');
require_once('include/utils/file_utils.php');
require_once('include/database/DBManagerFactory.php');
require_once('include/TimeDate.php');
require_once('include/utils/db_utils.php');
require_once('include/utils.php');
require_once('include/SugarObjects/VardefManager.php');
require_once('data/BeanFactory.php');
require_once('include/SugarObjects/LanguageManager.php');
require_once('include/SugarEmailAddress/SugarEmailAddress.php');
require_once("include/SugarObjects/SugarConfig.php");
require_once("modules/Users/User.php");
require_once("modules/UserPreferences/UserPreference.php");
require_once("include/utils/LogicHook.php");
require_once("include/Localization/Localization.php");

require_once("custom/include/SugarLogger/SticLogger.php");
require_once('SticInclude/SticRepairAndRebuild.php');
require_once('modules/Configurator/Configurator.php');


 class SticUpdateUtils 
 {
    public static $errors = [];
    public static $actions = [];

    private static function log(string $level, int $line, string $method, string $text): void {
        if( !isset($GLOBALS['log'])) {

            $GLOBALS['log'] = LoggerManager::getLogger();
        }
        $GLOBALS['log']->$level(__FILE__ . ':' . $line . ' ' . $method . ' - ' . $text);
    }
    private static function logDebug(int $line, string $method, string $text): void {
        self::log("debug", $line, $method, $text);
    }
    private static function logInfo(int $line, string $method, string $text): void {
        self::log("info", $line, $method, $text);
        self::$actions[] = "[Info]: {$text}";
    }
    private static function logWarning(int $line, string $method, string $text): void {
        self::log("warn", $line, $method, $text);
        self::$actions[] = "[Warn]: {$text}";
    }
    private static function logError(int $line, string $method, string $text): void {
        self::log("error", $line, $method, $text);
        self::$actions[] = "[Error]: {$text}";
        self::$errors[] = $text;
    }
    private static function logFatal(int $line, string $method, string $text): void {
        self::log("fatal", $line, $method, $text);
        self::$actions[] = "[Fatal]: {$text}";
        self::$errors[] = $text;
    }


    private static function resetErrors() {
        self::$errors = [];
        self::$actions = [];
        self::logDebug(__LINE__, __METHOD__, "Reset errors");
    }

    public static function hasErrors(): int {
        return count(self::$errors) > 0;
    }

    public static function needUpdate() : bool {
        global $sugar_config;

        if (($sugar_config['stic_force_dev_update'] ?? false) == true) {
            self::logInfo(__LINE__, __METHOD__ , "Detected stic_force_dev_update");
            return true;
        }

        $currentVersion = $sugar_config['sinergiacrm_version'];
        
        include('SticUpdates/SticUpdatesIndex.php');
        $lastVersion = array_key_first($stic_updates_index);

        self::logInfo(__LINE__, __METHOD__ , "Installed version: {$currentVersion} - Last version: {$lastVersion}");
        return $currentVersion != $lastVersion;
    }

    private static function isUpdateLocked() : bool {
        global $sugar_config;
        return isset($sugar_config['stic_update_locked']) && $sugar_config['stic_update_locked'];
    }

    private static function lockUpdate(bool $lock = true) : bool {
        include_once ('modules/Configurator/Configurator.php');
        $configurator = new Configurator();

        $configurator->config['stic_update_locked'] = $lock;
        self::logInfo(__LINE__, __METHOD__, "Setting config 'stic_update_locked' = {$lock}");

        if ($configurator->saveConfig() === false) {
            self::logError(__LINE__, __METHOD__, "Error saving Configuration");
            return false;
        };
        return true;
    }


    public static function processUpdate() : bool {
        self::resetErrors();

        global $sugar_config;

        if (($sugar_config['stic_force_dev_update'] ?? false) == true) {
            self::logInfo(__LINE__, __METHOD__, "Detected stic_force_dev_update: Forcing custom Dev-Update");
            return self::processDevUpdate();
        }

        $currentVersion = $sugar_config['sinergiacrm_version'];

        include('SticUpdates/SticUpdatesIndex.php');
        $lastVersion = array_key_first($stic_updates_index);

        if($currentVersion == $lastVersion) {
            self::logInfo(__LINE__, __METHOD__, "Running last version: no update needed");
            return false;
        }

        if (self::isUpdateLocked()) {
            self::logError(__LINE__, __METHOD__, "Need Update but Update process is locked");
            return false;
        }

        if (!self::lockUpdate()) {
            self::logError(__LINE__, __METHOD__, "Can not start Update: Lock update failed");
            return false;
        }

        $updateDefs = [];
        $ver = $lastVersion;
        do {
            $updateDefs[] = $stic_updates_index[$ver];
            $ver = $stic_updates_index[$ver]['metadata']['prev_version'];
        } while($ver != $currentVersion);

        // In $updateDefs there are all versions, from newer to older
        // Install all versions from older to newer
        $isOk = true;
        for ($i = count($updateDefs) - 1; $i >= 0 && $isOk; $i--) {
            $isOk = self::processSingleUpdate($updateDefs[$i]);
        }

        if (!self::lockUpdate(false)) {
            self::logError(__LINE__, __METHOD__, "Can not end Update: Un-Lock update failed");
            return false;
        }
        return $isOk;
    }

    private static function processDevUpdate() : bool {
        $updateDef = [
            'instructions' => [],
            'finally' => [
                'repair',
                'css',
                'sda_rebuild',
                'cache_rebuild',
            ],            
        ];

        if(!self::processSingleUpdate($updateDef)) {
            self::logError(__LINE__, __METHOD__, "Can not force Dev-Update");
            return false;
        }

        // Reset 'stic_force_dev_update' config
        $configurator = new Configurator();

        $configurator->config['stic_force_dev_update'] = false;
        self::logInfo(__LINE__, __METHOD__, "Setting config 'stic_force_dev_update' = false");

        if ($configurator->saveConfig() === false) {
            self::logError(__LINE__, __METHOD__, "Error saving Configuration");
            return false;
        };

        return true;
    }

    private static function processSingleUpdate(array $updateDef) : bool {
        // '2.1.0' => [
        //     'metadata' => [
        //         'version' => '2.1.0',
        //         'prev_version' => '2.0.0',
        //         'timestamp' => '2025-06-25 12:00:00',
        //         'inc_js_version' => true,
        //         'show_message' => true,
        //     ],
        //     'instructions' => [
        //         'repair',
        //         'SticUpdates/Migrations/20250130_feature_TrackerModule.sql',
        //         'sda_rebuild',
        //         'SticUpdates/Migrations/20250617_enhancement_glTranslations.sql',
        //     ],
        //     'finally' => [
        //         'repair',
        //         'css',
        //         'sda_rebuild',
        //         'cache_rebuild',
        //     ],
        // ],
        foreach ($updateDef['instructions'] as $instruction) {
            if(!self::processSingleInstruction($instruction)) {
                return false;
            }
        }
        foreach ($updateDef['finally'] as $instruction) {
            if(!self::processSingleInstruction($instruction)) {
                return false;
            }
        }

        if (isset($updateDef['metadata'])) {
            // Update metadata version
            return self::updateVersionInfo($updateDef['metadata']);
        }

        return true;
    }

    private static function processSingleInstruction(string $instruction) : bool {

        // Check if instruction is a file (script)
        if (file_exists($instruction)) {
            if (str_ends_with(strtolower($instruction), ".php")) {
                return self::executePhpFile($instruction);
            } elseif (str_ends_with(strtolower($instruction), ".sql")) {
                return self::executeSqlFile($instruction);
            } else {
                self::logError(__LINE__, __METHOD__, "Script not supported: " . $instruction);
                return false;
            }
        }

        $instruction = strtolower($instruction);
        switch($instruction) {
            case "repair": 
                return self::executeRepair();
            case "sda_rebuild":
                return self::executeSdaRebuild();
            case "cache_rebuild":
                return self::executeCacheRebuild();
            case "css": 
                return self::executeCss();
            default:
                self::logError(__LINE__, __METHOD__, "Unknown instruction: " . $instruction);
                return false;
        }
        return true;
    }

    private static function executePhpFile(string $file) : bool {
        include($file);
        return true;
    }

    private static function executeSqlFile(string $file) : bool {
        $connection = self::connectToDBWithPDO();
        if (!$connection) {            
            return false;
        }

        $sqlFileContent = file_get_contents($file);
        if ($sqlFileContent === false) {
            self::logError(__LINE__, __METHOD__, "Script (sql file) not found: {$file}");
            return false;
        }
        // Execute SQL statements
        try {
            $connection->exec($sqlFileContent);
        } catch (PDOException $e) {
            self::logError(__LINE__, __METHOD__, "Error when executing sql statements: " . $e->getMessage());
            return false;
        }
        return true;
    }

    private static function connectToDBWithPDO(): PDO|false {
        global $sugar_config;
        $mysqlHost = $sugar_config["dbconfig"]["db_host_name"];
        $mysqlDatabase = $sugar_config["dbconfig"]["db_name"];
        $mysqlUser = $sugar_config["dbconfig"]["db_user_name"];
        $mysqlPassword = $sugar_config["dbconfig"]["db_password"];
        try {
            $connection = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDatabase", $mysqlUser, $mysqlPassword);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->exec("SET NAMES 'utf8'"); // Setting codification
            return $connection;
        } catch (PDOException $e) {
            self::logError(__LINE__, __METHOD__, "Connection Error: " . $e->getMessage());
            return false;
        }
    }

    private static function executeRepair() : bool {
        $repair = new SticRepairAndRebuild();
        try {
            $repair->clearVardefs();
            $repair->clearLanguageCache();
            $repair->rebuildExtensions();
            $repair->rebuildAuditTables();
            $repair->repairDatabase();
        } catch (Exception $e) {
            self::logError(__LINE__, __METHOD__, "Repair error: " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    private static function executeSdaRebuild() : bool {
        $repair = new SticRepairAndRebuild();
        try {
            $repair->rebuildSDA();
        } catch (Exception $e) {
            self::logError(__LINE__, __METHOD__, "SDA rebuild error: " . $e->getMessage());
            return false;
        }
        return true;
    }

    private static function executeCacheRebuild() : bool {
        $repair = new SticRepairAndRebuild();

        // TODO:
        // rm -rf $CURRENT_PATH/cache

        try {
            $repair->clearVardefs();
            $repair->clearLanguageCache();
            $repair->clearTpls();
            $repair->clearJsFiles();
            $repair->clearJsLangFiles();
            $repair->clearDashlets();
            $repair->clearSugarFeedCache();
            $repair->clearSmarty();
            $repair->clearThemeCache();
            $repair->clearXMLfiles();
            $repair->clearSearchCache();
            $repair->clearExternalAPICache();
            $repair->rebuildExtensions();
        } catch (Exception $e) {
            self::logError(__LINE__, __METHOD__, "Cache rebuild error: " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    private static function executeCss() : bool {
        include('SticInclude/SticCustomScss.php');
        return true;
    }

    private static function updateVersionInfo(array $verMetadata) : bool {
        //     'metadata' => [
        //         'version' => '2.1.0',
        //         'prev_version' => '2.0.0',
        //         'timestamp' => '2025-06-25 12:00:00',
        //         'inc_js_version' => true,
        //         'show_message' => true,
        //     ],
        
        $configurator = new Configurator();
        
        // Current version (sinergiacrm_version)
        if (!isset($verMetadata['version'])) {
            self::logError(__LINE__, __METHOD__, "Mandatory update metadata 'version' not found");
            return false;
        }
        $version = $verMetadata['version'];
        $configurator->config['sinergiacrm_version'] = $version;
        self::logInfo(__LINE__, __METHOD__, "Setting config 'sinergiacrm_version' = {$version}");

        // Show update message (stic_show_update_alert)
        $showMessage = 0;
        if (($verMetadata['show_message'] ?? false) == true) {
            $showMessage = 1;
        }
        $configurator->config['stic_show_update_alert'] = $showMessage;
        self::logInfo(__LINE__, __METHOD__, "Setting config 'stic_show_update_alert' = {$showMessage}");

        // Current js version (js_custom_version)
        if(($verMetadata['inc_js_version'] ?? false) == true) {
            $jsVersion =  crc32($verMetadata['version'] . ($verMetadata['timestamp'] ?? "01234567890"));
            $configurator->config['js_custom_version'] = $jsVersion;
            self::logInfo(__LINE__, __METHOD__, "Setting config 'js_custom_version' = {$jsVersion}");
        }

        //Save config
        if ($configurator->saveConfig() === false) {
            self::logError(__LINE__, __METHOD__, "Error saving Configuration");
            return false;
        };

        return true;
    }
 }