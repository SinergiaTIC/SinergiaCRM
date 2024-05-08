<?php
global $app_list_strings, $app_strings, $current_user, $mod_strings, $sugar_config;

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
if (!is_admin($current_user) && !defined('configurator_util')) sugar_die('Admin Only');

$settingsForm = [];

require_once 'modules/Configurator/Configurator.php';
require_once 'include/Sugar_Smarty.php';
require_once 'settings_form.php'; // populates $settingsForm

echo getClassicModuleTitle('seven', [$mod_strings['LBL_SEVEN_TITLE']]);

foreach (array_keys($settingsForm) as $k)
    if (!isset($sugar_config[$k])) $sugar_config[$k] = '';

$configurator = new Configurator;
$administration = new Administration;

if (!empty($_POST['save'])) {
    $configurator->saveConfig();

    $administration->saveConfig();

    header('Location: index.php?module=Administration&action=index');
}

$administration->retrieveSettings();

$ss = new Sugar_Smarty;
$ss->assign('APP', $app_strings);
$ss->assign('APP_LIST', $app_list_strings);
$ss->assign('config', $configurator->config);
$ss->assign('error', $configurator->errors);
$ss->assign('MOD', $mod_strings);
$ss->assign('seven_config', $settingsForm);
$ss->display('modules/seven/template_form_config.tpl');
