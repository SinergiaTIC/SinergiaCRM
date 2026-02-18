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
 *
 * Can be called in two ways:
 * 1. Via URL with 'file' parameter
 * 2. Directly from other files using static methods: SticRunScripts::executeScript($file, $infos, $errors)
 */

/**
 * Class SticRunScripts
 * Handles execution of SQL and PHP scripts with proper validation and error handling.
 */
class SticRunScripts {
    
    /**
     * Create and return a PDO connection using database settings from $sugar_config.
     *
     * The connection is configured to throw exceptions on error and to use UTF-8.
     *
     * @param array $infos  Array to store info messages (passed by reference).
     * @param array $errors Array to store error messages (passed by reference).
     * @return PDO|bool     PDO connection object on success, false on failure.
     */
    public static function connectToDBWithPDO(&$infos, &$errors) {
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
            $infos[] = "[ERROR] Errors database connection: " . $e->getMessage();
            $errors[] = "Errors database connection: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Execute the SQL statements contained in the given file using the provided PDO connection.
     *
     * The file should contain valid SQL; multiple statements are supported.
     *
     * @param PDO    $connection PDO connection object.
     * @param string $file       Path to the SQL file.
     * @param array  $infos      Array to store info messages (passed by reference).
     * @param array  $errors     Array to store error messages (passed by reference).
     * @return bool  True on success, false on failure.
     */
    public static function executeSQLFile($connection, $file, &$infos, &$errors) {
        $sqlFileContent = file_get_contents($file);
        
        try {
            $connection->exec($sqlFileContent);
            return true;
        } catch (PDOException $e) {
            $infos[] = "[ERROR] Error when executing SQL statements: " . $e->getMessage();
            $errors[] = "Error when executing SQL statements: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Execute a PHP script file.
     *
     * @param string $file   Path to the PHP file.
     * @param array  $infos  Array to store info messages (passed by reference).
     * @param array  $errors Array to store error messages (passed by reference).
     * @return bool  True on success, false on failure.
     */
    public static function executePHPFile($file, &$infos, &$errors) {
        try {
            require($file);
            $infos[] = "Script executed correctly: $file";
            return true;
        } catch (Exception $e) {
            $infos[] = "[ERROR] Error when executing PHP script: " . $e->getMessage();
            $errors[] = "Error when executing PHP script: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Validate and normalize the file path.
     *
     * Checks that:
     * - File exists and is a regular file
     * - File is under permitted directories (SticUpdates/ or SticInstall/scripts/)
     * - For language files, language matches CRM default language
     *
     * @param string $file   The file path to validate.
     * @param array  $infos  Array to store info messages (passed by reference).
     * @param array  $errors Array to store error messages (passed by reference).
     * @return string|false  Normalized file path on success, false on failure.
     */
    public static function validateAndNormalizePath($file, &$infos, &$errors) {
        global $sugar_config;
        
        // Normalize path separators and strip any leading slash to avoid absolute path bypasses
        $normalizedFile = str_replace('\\', '/', $file);
        $normalizedNoLeading = ltrim($normalizedFile, '/');

        // Check if file exists
        if (!file_exists($normalizedNoLeading) || !is_file($normalizedNoLeading)) {
            $infos[] = "[ERROR] File $normalizedNoLeading doesn't exist on server or is not a file";
            $errors[] = "File $normalizedNoLeading doesn't exist on server or is not a file";
            return false;
        }

        // Restrict allowed files to prevent arbitrary file execution
        if (!preg_match('#^(?:SticUpdates/|SticInstall/scripts/)#i', $normalizedNoLeading)) {
            $infos[] = "[ERROR] Execution is restricted to SticUpdates/ or SticInstall/scripts/";
            $errors[] = "Execution is restricted to SticUpdates/ or SticInstall/scripts/";
            return false;
        }

        // If this is a language package, ensure its language directory matches the CRM default language
        if (stripos($normalizedNoLeading, 'SticUpdates/Languages/') === 0) {
            $after = substr($normalizedNoLeading, strlen('SticUpdates/Languages/'));
            $fileLang = strtolower(strtok(trim($after, '/'), '/')) ?: '';
            $crmLang = strtolower(strtok(str_replace('-', '_', $sugar_config['default_language'] ?? ''), '_')) ?: '';

            if ($fileLang !== $crmLang) {
                $infos[] = "Requested language file ($fileLang) doesn't match CRM language ($crmLang). Skipping execution.";
                return false;
            }
        }

        return $normalizedNoLeading;
    }

    /**
     * Execute a script file (SQL or PHP).
     *
     * Main method to execute a script with full validation and proper error handling.
     * Checks maintenance mode is enabled before execution.
     *
     * @param string $file   Path to the script file (relative to CRM root).
     * @param array  $infos  Array to store info messages (passed by reference).
     * @param array  $errors Array to store error messages (passed by reference).
     * @return bool  True on success, false on failure.
     */
    public static function executeScript($file, &$infos, &$errors) {
        global $sugar_config;
        $infos[] = "Executing file: $file";
        // Initialize arrays if not provided
        if (!is_array($infos)) {
            $infos = array();
        }
        if (!is_array($errors)) {
            $errors = array();
        }

        // Check if maintenance mode is enabled
        if (!(isset($sugar_config['stic_maintenance_mode_enabled']) && filter_var($sugar_config['stic_maintenance_mode_enabled'], FILTER_VALIDATE_BOOLEAN))) {
            $infos[] = "[ERROR] stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
            $errors[] = "stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
            return false;
        }

        // Validate and normalize the file path
        $normalizedFile = self::validateAndNormalizePath($file, $infos, $errors);
        if ($normalizedFile === false) {
            return false;
        }

        // Determine file type and execute
        $ext = strtolower(substr($normalizedFile, -3));
        
        if ($ext === "php") {
            return self::executePHPFile($normalizedFile, $infos, $errors);
        } else if ($ext === "sql") {
            $connection = self::connectToDBWithPDO($infos, $errors);
            if ($connection === false) {
                return false;
            }
            $result = self::executeSQLFile($connection, $normalizedFile, $infos, $errors);
            $connection = null; // Close the connection
            return $result;
        } else {
            $infos[] = "[ERROR] File: $normalizedFile. The file extension must be php or sql";
            $errors[] = "File: $normalizedFile. The file extension must be php or sql";
            return false;
        }
    }
}

/**
 * Output a JSON response with proper HTTP headers and buffer cleanup.
 *
 * Ensures clean output by ending and clearing any existing output buffers
 * before sending the JSON response.
 *
 * @param string $status Status of the operation ('success' or 'error')
 * @param array  $errors Array of error messages
 * @param array  $infos  Array of information/success messages
 *
 * @return void
 */
function outputJsonResponseScripts($status, $errors, $infos) {
    @ob_end_clean();
    ob_start();
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'errors' => $errors,
        'infos' => $infos,
    ]);
    ob_flush();
}

// ******* MAIN SCRIPT *******
// This script executes a single update script or SQL migration specified by the `file` request parameter.
// It does not run multiple files automatically; the caller must indicate which file to execute.

// Require the `file` parameter: nothing will be executed without it.
if (!isset($_REQUEST['file'])) {
    return;
}

include ('include/MVC/preDispatch.php');
$startTime = microtime(true);
require_once('include/entryPoint.php');
ob_start();

// Initialize arrays for storing info and error messages
$infos = array();
$errors = array();

$file = $_REQUEST['file'];

// Execute the script using the static method
$result = SticRunScripts::executeScript($file, $infos, $errors);

// Add success message if execution was successful
if ($result === true) {
    $infos[] = "Script executed successfully.";
}

// Output results using standardized JSON response function
if (count($errors) > 0) {
    outputJsonResponseScripts('error', $errors, $infos);
} else {
    outputJsonResponseScripts('success', $errors, $infos);
}
