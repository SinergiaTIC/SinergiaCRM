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
 * Utility to execute a single update script or SQL migration on this SinergiaCRM instance.
 *
 * Usage: call this script with the `file` query parameter set to the relative path of
 * the script or SQL file to run. Example:
 *  - SticRunScripts.php?file=SticUpdates/Scripts/FixPersonalEnvironmentModuleDisplay.php
 *  - SticRunScripts.php?file=SticUpdates/Migrations/20200803_hotfix_34_RemovingMailMergeExampleTemplates.sql
 *
 * Only files under `SticUpdates/` or `SticInstall/scripts/` are permitted (see checks below).
 */

/**
 * Create and return a PDO connection using database settings from $sugar_config.
 *
 * The connection is configured to throw exceptions on error and to use UTF-8.
 *
 * @return PDO PDO connection object on success.
 *                On failure the script sends an HTTP 500 and terminates.
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
        $connection->exec("SET NAMES 'utf8'"); // Set connection charset to UTF-8
        return $connection;
    } catch (PDOException $e) {
        http_response_code(500);
        die("Connection Error: " . $e->getMessage());
    }
}

/**
 * Execute the SQL statements contained in the given file using the provided PDO connection.
 *
 * The file should contain valid SQL; multiple statements are supported.
 *
 * @param PDO    $connection PDO connection object.
 * @param string $file       Path to the SQL file.
 * @return bool  True on success, false on failure. On failure an HTTP 500 status is sent.
 */
function executeSQLFile($connection, $file)
{
    $sqlFileContent = file_get_contents($file);
    echo "<br> -- $file <br>";
    
    // Execute SQL from file
    try {
        $connection->exec($sqlFileContent);
        echo "SQL statements executed correctly. <br />";
        return true;
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error when executing SQL statements: " . $e->getMessage() . "<br />";
        return false;
    }
}

// ******* MAIN SCRIPT *******
// This script executes a single update script or SQL migration specified by the `file` request parameter.
// It does not run multiple files automatically; the caller must indicate which file to execute.

include ('include/MVC/preDispatch.php');
$startTime = microtime(true);
require_once('include/entryPoint.php');
ob_start();
// Only allow execution when maintenance mode is enabled in configuration
global $sugar_config;
// Require maintenance mode to be enabled in config (accepts boolean or string-ish values)
if (!(isset($sugar_config['stic_maintenance_mode_enabled']) && filter_var($sugar_config['stic_maintenance_mode_enabled'], FILTER_VALIDATE_BOOLEAN))) {
    http_response_code(503);
    echo "stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
    exit;
}

// Require the `file` parameter: nothing will be executed without it.
if (!isset($_REQUEST['file'])) {
    http_response_code(400);
    echo "File isn't specified in URL. Exiting.";
    exit;
}

$file = $_REQUEST['file'];

// Normalize path separators and strip any leading slash to avoid absolute path bypasses
$normalizedFile = str_replace('\\', '/', $file);
$normalizedNoLeading = ltrim($normalizedFile, '/');

if (!file_exists($normalizedNoLeading) || !is_file($normalizedNoLeading)) {
    http_response_code(404);
    echo "File $normalizedNoLeading doesn't exist on server or not it is a file";
    exit;
}

// Restrict allowed files to prevent arbitrary file execution. Only files under `SticUpdates/` or `SticInstall/scripts/` are permitted.
// Language packages under `SticUpdates/Languages/` must match the CRM default language (checked below).
if (!preg_match('#^(?:SticUpdates/|SticInstall/scripts/)#i', $normalizedNoLeading)) {
    http_response_code(403);
    echo "Execution is restricted to SticUpdates/ or SticInstall/scripts/.";
    exit;
}

// If this is a language package, ensure its language directory matches the CRM default language
if (stripos($normalizedNoLeading, 'SticUpdates/Languages/') === 0) {
    $after = substr($normalizedNoLeading, strlen('SticUpdates/Languages/'));
    $fileLang = strtolower(strtok(trim($after, '/'), '/')) ?: '';
    $crmLang = strtolower(strtok(str_replace('-', '_', $sugar_config['default_language'] ?? ''), '_')) ?: '';

    if ($fileLang !== $crmLang) {
        http_response_code(204);
        echo "Requested language file ($fileLang) doesn't match CRM language ($crmLang). Skipping execution.";
        exit;
    }
}

$ext = substr($normalizedNoLeading, -3);
if ($ext === "php") {
    echo "$normalizedNoLeading <br>";
    require($normalizedNoLeading);
    // Indicate successful execution of the script
    http_response_code(200);
    echo "Script executed correctly.";
} else if ($ext === "sql") {
    $connection = connectToDBWithPDO();
        $ok = executeSQLFile($connection,$normalizedNoLeading);
        // Close the PDO connection by unsetting the reference so it can be freed
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
    echo "File: $normalizedNoLeading. The file extension must be php or sql";
    exit;
}
