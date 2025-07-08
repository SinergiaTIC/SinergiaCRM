<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

 class SticUpdateUtils 
 {
    public static $errors = [];

    public static function resetErrors() {
        self::$errors = [];
    }

    private static function addError(string $error){
        self::$errors[] = $error;
    }

    public static function isUpdated() : bool {
        global $sugar_config;
        $currentVersion = $sugar_config['sinergiacrm_version'];
        
        require_once 'SticUpdates/SticUpdatesIndex.php';
        $lastVersion = array_key_first($stic_updates_index);

        return $currentVersion == $lastVersion;
    }

    private static function isUpdateLocked() : bool {
        global $sugar_config;
        return isset($sugar_config['stic_update_locked']) && $sugar_config['stic_update_locked'];
    }

    private static function lockUpdate(bool $lock = true) : void {
        require_once 'modules/Configurator/Configurator.php';
        $configurator = new Configurator();
        $configurator->config['stic_update_locked'] = $lock;
        $configurator->saveConfig();
    }


    public static function processUpdate() : bool {
        self::resetErrors();

        global $sugar_config;
        $currentVersion = $sugar_config['sinergiacrm_version'];
        
        require_once 'SticUpdates/SticUpdatesIndex.php';
        $lastVersion = array_key_first($stic_updates_index);

        if($currentVersion == $lastVersion) {
            return false;
        }

        if (self::isUpdateLocked()) {
            self::addError("Need Update but Update process is locked");
            return false;
        }

        self::lockUpdate();

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

        self::lockUpdate(false);
        return $isOk;
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
        //         // 'repair',
        //         'SticUpdates/Migrations/20250130_feature_TrackerModule.sql',
        //         // 'sda_rebuild',
        //         'SticUpdates/Migrations/20250617_enhancement_glTranslations.sql',
        //     ],
        //     'finally' => [
        //         'repair',
        //         'css',
        //         'sda_rebuild',
        //         'cache_rebuild', // Borrar y regenerar (para los css de los formularios web)
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

        // Update metadata version
        return self::updateVersionInfo($updateDef['metadata']);
    }

    private static function processSingleInstruction(string $instruction) : bool {

        // Check if instruction is a file (script)
        if (file_exists($instruction)) {
            if (str_ends_with(strtolower($instruction), ".php")) {
                return self::executePhpFile($instruction);
            } elseif (str_ends_with(strtolower($instruction), ".sql")) {
                return self::executeSqlFile($instruction);
            } else {
                self::addError("Script not supported: " . $instruction);
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
                self::addError("Unknown instruction: " . $instruction);
                return false;
        }
        return true;
    }

    private static function executePhpFile(string $file) : bool {
        require($file);

        return true;
    }

    private static function executeSqlFile(string $file) : bool {
        $connection = self::connectToDBWithPDO();
        if (!$connection) {
            return false;
        }

        $sqlFileContent = file_get_contents($file);
        // Execute SQL statements
        try {
            $connection->exec($sqlFileContent);
        } catch (PDOException $e) {
            self::addError("Error when executing sql statements: " . $e->getMessage());
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
            self::addError("Connection Error: " . $e->getMessage());
            return false;
        }
    }

    private static function executeRepair() : bool {
        // TODO
        return true;
    }

    private static function executeSdaRebuild() : bool {
        // TODO
        return true;
    }

    private static function executeCacheRebuild() : bool {
        // TODO
        return true;
    }

    private static function executeCss() : bool {
        // TODO
        return true;
    }

    private static function updateVersionInfo(array $verMetadata) : bool {
        // TODO

        //     'metadata' => [
        //         'version' => '2.1.0',
        //         'prev_version' => '2.0.0',
        //         'timestamp' => '2025-06-25 12:00:00',
        //         'inc_js_version' => true,
        //         'show_message' => true,
        //     ],

        return true;
    }
 }