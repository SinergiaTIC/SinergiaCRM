<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
if (!is_admin($current_user) && !defined('configurator_util')) sugar_die('Admin Only');

require_once 'modules/Configurator/Configurator.php';
require_once 'include/Sugar_Smarty.php';
require_once 'settings_form.php';
require_once 'utils.php';

global $sugar_config, $mod_strings;

echo getClassicModuleTitle('seven', [$mod_strings['LBL_SEVEN_LEAD_TITLE']]);

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

seven_smarty_render(new Lead, $configurator, 'modules/seven/lead_form.tpl', [
    '{first-name} => First Name',
    '{last-name} => last Name',
    '{full-name} => Full Name',
    '{office-phone} => Office Phone',
    '{mobile-number} => Mobile Number',
    '{job-title} => Job Title',
    '{department} => Department',
    '{account-name} => Account Name',
    '{email} => Email',
    '{primary-address} => Primary Address',
    '{primary-city} => Primary City',
    '{primary-state} => Primary State',
    '{primary-postalcode}  => Primary Postal Code',
    '{primary-country} => Primary Country',
    '{alter-address} => Alternative Address',
    '{alter-city} => Alternative City',
    '{alter-state} => Alternative State',
    '{alter-postalcode} => Alternative Postal Code',
    '{alter-country} => Alternative Country',
    '{description} => Description',
    '{date} => Date',
], $settingsForm);
