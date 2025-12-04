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

 // Removing PHP Warnings in output
error_reporting(0);

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

// It's necessary to include here both config files. If not, the entryPoint.php will include them again and it will fail.
include_once 'config.php';
include_once 'config_override.php';
$GLOBALS['sugar_config'] = $sugar_config;


echo "<h1>SinergiaCRM Update</h1>";
include_once 'SticInclude/SticUpdateUtils.php';
if (SticUpdateUtils::needUpdate()) {
    echo "<h2> Updating SinergiaCRM </h2>";

    SticUpdateUtils::processUpdate();
    echo "<h3> Actions </h3>";
    echo "<ul>";
    foreach (SticUpdateUtils::$actions as $action) {
        echo "<li>{$action}</li>";
    }
    echo "</ul>";
    if (SticUpdateUtils::hasErrors()) {
        echo "<h3> Process ended with errors </h3>";
        echo "<ul>";
        foreach (SticUpdateUtils::$errors as $error) {
            echo "<li>{$error}</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<h2>No available updates<br>";
}
