<?php

// https://github.com/SinergiaTIC/SinergiaCRM/blob/6dfadf98d6f5e5375a65f72fad7379dcf0e5d247/ModuleInstall/ModuleInstaller.php#L127-L141

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'include/entryPoint.php';
require_once 'modules/Administration/QuickRepairAndRebuild.php';

global $sugar_config, $current_user, $db;
$errors = [];
$infos = [];
if (!(isset($sugar_config['stic_maintenance_mode_enabled']) && filter_var($sugar_config['stic_maintenance_mode_enabled'], FILTER_VALIDATE_BOOLEAN))) {
    http_response_code(503);
    $errors[] = "stic_maintenance_mode_enabled is not enabled in configuration. Exiting.";
    @ob_end_clean();
    ob_start();
    ob_clean();

    // Optional: Return a JSON error message
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'errors' => $errors,
        'infos' => $infos,
    ]);
    ob_flush();
    exit;
}


$scriptsVersion = $_REQUEST['scripts_version'] ?? null;

// For easier management, repair actions will be held in English
$defaultLanguage = $sugar_config['default_language'];

require_once('ModuleInstall/ModuleInstaller.php');
// Set a user with admin capabilities in order to exec the repair and rebuild process
$current_user = new User();
$current_user->getSystemUser();

// Run all scripts listed in the pre_install.txt file 
runScripts($scriptsVersion, 'pre_install.txt', $errors, $infos);

$mi = new ModuleInstaller();

global $beanList;
foreach ($mi->modules as $module_name) {
    if (!empty($beanList[$module_name])) {
        $objectName = BeanFactory::getObjectName($module_name);
        VardefManager::loadVardef($module_name, $objectName);
    }
}

$selectedActions = array(
    'clearTpls',
    'clearJsFiles',
    'clearDashlets',
    'clearVardefs',
    'clearJsLangFiles',
    'rebuildAuditTables',
    'repairDatabase',
);
VardefManager::clearVardef();
global $beanList, $beanFiles, $moduleList;
$mi->rebuild_all(true);
require_once('modules/Administration/QuickRepairAndRebuild.php');
$mod_strings = return_module_language($current_language, 'Administration');

$db->setDieOnError(true);
try {
    $rac = new RepairAndClear();
    $rac->repairAndClearAll($selectedActions, array(translate('LBL_ALL_MODULES')), true, false);
} catch (Exception $e) {
    ob_clean();
    $errors[] = 'Database error during sync: ' . $e->getMessage();
}
// echo '<h3>Repairing roles</h3>';
// include('modules/ACL/install_actions.php');
// $GLOBALS['log']->info(__FILE__ . '(' . __LINE__ . ') >> Repairing roles');

//Clear out all the langauge cache files.
clearAllJsAndJsLangFilesWithoutOutput();
$cache_key = 'app_list_strings.'.$defaultLanguage ?? 'en_us' ;
sugar_cache_clear($cache_key);
sugar_cache_reset();

// Run all scripts listed in the post_install.txt file
runScripts($scriptsVersion, 'post_install.txt', $errors, $infos);


$_REQUEST['keepUserTheme'] = 1;
include_once('SticInclude/SticCustomScss.php');

sugar_cleanup(false);

// some jobs have annoying habit of calling sugar_cleanup(), and it can be called only once
// but job results can be written to DB after job is finished, so we have to disconnect here again
// just in case we couldn't call cleanup
if (class_exists('DBManagerFactory')) {
    $db = DBManagerFactory::getInstance();
    $db->disconnect();
}
if (count($errors)) {
    @ob_end_clean();
    ob_start();
    ob_clean();

    // Optional: Return a JSON error message
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'errors' => $errors,
        'infos' => $infos,
    ]);
    ob_flush();
    exit;
}
@ob_end_clean();
ob_start();
ob_clean();

// Optional: Return a JSON error message
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'infos' => $infos,
]);
ob_flush();
exit;


function runScripts($scriptsVersion, $fileName, &$errors, &$infos) {
    if ($scriptsVersion) {
        $scriptsFile = 'SticUpdates/Releases/' . $scriptsVersion . "/$fileName";
        if (file_exists($scriptsFile)) {
            $scripts = file($scriptsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($scripts as $script) {
                $script = trim($script);
                if (!empty($script) && strpos($script, '#') !== 0) { // Skip empty lines and comments
                    $_REQUEST['file'] = $script;
                    include('SticRunScripts.php');
                }
            }
        } else {
            $errors[] = "Scripts file not found: $scriptsFile";
        }
    } else {
        $infos[] = "No scripts version provided. Skipping script execution.";
    }

}