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
        $error = false;
        for ($i = count($updateDefs) - 1; $i >= 0 && !$error; $i--) {
            $error = self::processSingleUpdate($updateDefs[$i]);
        }

        self::lockUpdate(false);
        return !$error;
    }

    private static function processSingleUpdate(array $updateDef) : bool {

        return true;
    }
 }