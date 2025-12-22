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

/**
 * This script can be run during the update of SinergiaCRM instances. 
 * 
 * It is in charge of running any SugraCRM PHP script that will find in SticUpdates/Scripts folder
 * 
 * If you want to run just one script, you can specify the path and file name (with the extension) in the argument list . Like this:
 * http://xxxxxxxxxx.sinergiacrm.org/SticRunScripts.php?file=SticUpdates/Scripts/FixPersonalEnvironmentModuleDisplay.php
 * http://xxxxxxxxxx.sinergiacrm.org/SticRunScripts.php?file=SticUpdates/Migrations/20200803_hotfix_34_RemovingMailMergeExampleTemplates.sql
 * 
 */



/**
 * Check if the folder exists and has files to run
 * @params : $folderPath - a String containing the path to the folder.
 * @returns: Boolean     - true if empty, false otherwise
*/
function isEmptyFolder($folderPath)
{
    if (is_dir($folderPath)) {
        $archivos = scandir($folderPath);
        $archivos = array_diff($archivos, array('.', '..'));  // Removes the elements from the array: "." and ".."
        if (count($archivos) > 0) {
            return false;
        }
    }
    echo "$folderPath folder has no files to run. <br />";
    return true; 
}

/**
 * Connect to the database through PDO to be able to execute files that contain multiple SQL statements
 * @returns: PDO Object representing the connection to the database
*/
function connectToDBWithPDO()
{
    echo "Connecting to the database <br />";
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
        http_response_code(500);
        die("Connection Error: " . $e->getMessage());
    }
}

/**
 * Executes a file with SQL statements through a PDO connection
 * @params : $connection - PDO Object representing the connection to the database
 * @params : $file       - File with SQL statements
 * @returns: boolean     - True if the execution was successful, false otherwise.
*/
function executeSQLFile($connection, $file)
{
    $sqlFileContent = file_get_contents($file);
    echo "<br> -- $file <br>";
    
    // Execute SQL statements
    try {
        $connection->exec($sqlFileContent);
        echo "Sql statements executed correctly. <br />";
        return true;
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error when executing sql statements: " . $e->getMessage() . "<br />";
        return false;
    }
}



// ******* SCRIPT *******
// This script runs either the script indicated in the request 
// or all scripts and migrations in the SticUpdates/Scripts/ and SticUpdates/Migrations/ folders.

include ('include/MVC/preDispatch.php');
$startTime = microtime(true);
require_once('include/entryPoint.php');
ob_start();
// Only allow execution when maintenance mode is enabled in config
global $sugar_config;
// Require maintenance mode to be enabled in config (accepts boolean or string-ish values)
if (!(isset($sugar_config['stic_maintenance_mode_enabled']) && filter_var($sugar_config['stic_maintenance_mode_enabled'], FILTER_VALIDATE_BOOLEAN))) {
    http_response_code(503);
    echo "stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
    exit;
}

// Require a file parameter to do anything; do not execute all scripts automatically
if (!isset($_REQUEST['file'])) {
    http_response_code(400);
    echo "File isn't specified in URL. Exiting.";
    exit;
}

$file = $_REQUEST['file'];
if (!file_exists($file)) {
    http_response_code(404);
    echo "File $file doesn't exist on server";
    exit;
}

// Allow only files inside SticUpdates/ or SticInstall/scripts/. If it's a language file, enforce the CRM default language.
$normalizedFile = str_replace('\\', '/', $file);
$normalizedNoLeading = ltrim($normalizedFile, '/');

// Only allow execution from these two locations
if (!preg_match('#^(?:SticUpdates/|SticInstall/scripts/)#i', $normalizedNoLeading)) {
    http_response_code(403);
    echo "Execution is restricted to SticUpdates/ or SticInstall/scripts/.";
    exit;
}

// If it's a language update, ensure it matches the CRM language
if (stripos($normalizedNoLeading, 'SticUpdates/Languages/') === 0) {
    $after = substr($normalizedNoLeading, strlen('SticUpdates/Languages/'));
    $fileLang = strtolower(strtok(trim($after, '/'), '/')) ?: '';
    $crmLang = strtolower(strtok(str_replace('-', '_', $sugar_config['default_language'] ?? ''), '_')) ?: '';

    if ($fileLang !== $crmLang) {
        http_response_code(403);
        echo "Requested language file ($fileLang) doesn't match CRM language ($crmLang). Skipping execution.";
        exit;
    }
}

$ext = substr($file, -3);
if ($ext === "php") {
    echo "$file <br>";
    require($file);
    // Indicate successful execution of the script
    http_response_code(200);
    echo "Script executed correctly.";
} else if ($ext === "sql") {
    $connection = connectToDBWithPDO();
        $ok = executeSQLFile($connection,$file);
        // Dereferencing the PDO instance, allowing the PHP garbage collector to free the resources associated with the connection
        $connection = null;
        if ($ok === false) {
            // executeSQLFile already set a 500 status and printed the error
            exit;
        }
        // SQL executed successfully
        http_response_code(200);
        echo "Script executed correctly.";
} else {
    http_response_code(400);
    echo "File: $file. The file extension must be php or sql";
    exit;
}
