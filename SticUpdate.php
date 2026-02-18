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
 * SticUpdate.php
 *
 * Main update script for SinergiaCRM. Handles database repairs, cache clearing,
 * and pre/post update script execution.
 *
 * Reference: https://github.com/SinergiaTIC/SinergiaCRM/blob/6dfadf98d6f5e5375a65f72fad7379dcf0e5d247/ModuleInstall/ModuleInstaller.php#L127-L141
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'include/entryPoint.php';
require_once 'modules/Administration/QuickRepairAndRebuild.php';
require_once 'SticRunScripts.php';

// Initialize global variables
global $sugar_config, $current_user, $db;
$errors = [];
$infos = [];

// Check if maintenance mode is enabled
if (!(isset($sugar_config['stic_maintenance_mode_enabled']) && filter_var($sugar_config['stic_maintenance_mode_enabled'], FILTER_VALIDATE_BOOLEAN))) {
    http_response_code(503);
    $errors[] = "stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
    outputJsonResponse('error', $errors, $infos);
    exit;
}


$scriptsVersion = $_REQUEST['scripts_version'] ?? null;

// Store default language for cache management
$defaultLanguage = $sugar_config['default_language'];

require_once('ModuleInstall/ModuleInstaller.php');

// Create system user with admin capabilities to execute repairs
$current_user = new User();
$current_user->getSystemUser();

// Execute pre-installation scripts
runScripts($scriptsVersion, 'pre_install.txt', $errors, $infos);

$mi = new ModuleInstaller();

// Load vardefs for all modules
global $beanList;
foreach ($mi->modules as $module_name) {
    if (!empty($beanList[$module_name])) {
        $objectName = BeanFactory::getObjectName($module_name);
        VardefManager::loadVardef($module_name, $objectName);
    }
}

// Define repair and rebuild actions to perform
$selectedActions = array(
    'clearTpls',
    'clearJsFiles',
    'clearDashlets',
    'clearVardefs',
    'clearJsLangFiles',
    'rebuildAuditTables',
    'repairDatabase',
);

// Clear and rebuild database
VardefManager::clearVardef();
global $beanList, $beanFiles, $moduleList;
$mi->rebuild_all(true);
require_once('modules/Administration/QuickRepairAndRebuild.php');
$mod_strings = return_module_language($current_language, 'Administration');

// Execute repair and clear operations
$db->setDieOnError(true);
try {
    $rac = new RepairAndClear();
    $rac->repairAndClearAll($selectedActions, array(translate('LBL_ALL_MODULES')), true, false);
} catch (Exception $e) {
    ob_clean();
    $errors[] = 'Database error during sync: ' . $e->getMessage();
    $infos[] = "[ERROR] Database error during sync: " . $e->getMessage();

}

// Clear all language and JavaScript cache files
clearAllJsAndJsLangFilesWithoutOutput();
$cache_key = 'app_list_strings.' . ($defaultLanguage ?? 'en_us');
sugar_cache_clear($cache_key);
sugar_cache_reset();

// Execute post-installation scripts
runScripts($scriptsVersion, 'post_install.txt', $errors, $infos);

// Apply custom SCSS theme settings (configurable via skipCssRebuild parameter, defaults to false)
if (!filter_var($_REQUEST['skipCssRebuild'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
    $_REQUEST['keepUserTheme'] = 1;
    include_once('SticInclude/SticCustomScss.php');
}

// Cleanup application resources
sugar_cleanup(false);

// Ensure database disconnection
// Some jobs call sugar_cleanup() multiple times, which is not allowed.
// Since job results may be written to DB after completion, we disconnect here.
if (class_exists('DBManagerFactory')) {
    $db = DBManagerFactory::getInstance();
    $db->disconnect();
}

// Output results
if (count($errors)) {
    outputJsonResponse('error', $errors, $infos);
    exit;
}

outputJsonResponse('success', [], $infos);
exit;

/**
 * Execute scripts from a specified file list.
 *
 * Reads a file containing a list of scripts to execute and runs each one.
 * Empty lines and comment lines (starting with #) are skipped.
 *
 * @param string $scriptsVersion Version identifier for the scripts
 * @param string $fileName       Name of the file containing script list (e.g., 'pre_install.txt')
 * @param array  $errors         Reference to errors array to append to
 * @param array  $infos          Reference to infos array to append to
 *
 * @return void
 */
function runScripts($scriptsVersion, $fileName, &$errors, &$infos) {
    if (!$scriptsVersion) {
        $infos[] = "No scripts version provided. Skipping script execution.";
        return;
    }

    $scriptsFile = 'SticUpdates/Releases/' . $scriptsVersion . "/$fileName";
    if (!file_exists($scriptsFile)) {
        $infos[] = "Scripts file not found: $scriptsFile";
        return;
    }

    $scripts = file($scriptsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($scripts as $script) {
        $script = trim($script);
        // Skip empty lines and comments
        if (!empty($script) && strpos($script, '#') !== 0) {
            SticRunScripts::executeScript($script, $infos, $errors);
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
function outputJsonResponse($status, $errors, $infos) {
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