<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
if (!is_admin($current_user) && !defined('configurator_util')) sugar_die('Admin Only');

require_once 'modules/Configurator/Configurator.php';
require_once 'include/Sugar_Smarty.php';
require_once 'settings_form.php';
require_once 'utils.php';

global $sugar_config, $mod_strings;

echo getClassicModuleTitle('seven', [$mod_strings['LBL_SEVEN_USER_TITLE']]);

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

seven_smarty_render(new User, $configurator, 'modules/seven/user_form.tpl', [
    '{address_city}',
    '{address_country}',
    '{address_postalcode}',
    '{address_state}',
    '{address_street}',
    '{authenticate_id}',
    '{created_by}',
    '{date_entered}',
    '{date_modified}',
    '{deleted}',
    '{department}',
    '{description}',
    '{employee_status}',
    '{external_auth_only}',
    '{factor_auth_interface}',
    '{factor_auth}',
    '{first_name}',
    '{id}',
    '{is_admin}',
    '{is_group}',
    '{last_name}',
    '{messenger_id}',
    '{messenger_type}',
    '{modified_user_id}',
    '{phone_fax}',
    '{phone_home}',
    '{phone_mobile}',
    '{phone_other}',
    '{phone_work}',
    '{photo}',
    '{portal_only}',
    '{pwd_last_changed}',
    '{receive_notifications}',
    '{reports_to_id}',
    '{show_on_employees}',
    '{status}',
    '{sugar_login}',
    '{system_generated_password}',
    '{title}',
    '{user_hash}',
    '{user_name}',
], $settingsForm);
