<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
if (!is_admin($current_user) && !defined('configurator_util')) sugar_die('Admin Only');

require_once 'modules/Configurator/Configurator.php';
require_once 'include/Sugar_Smarty.php';
require_once 'settings_form.php';
require_once 'utils.php';

global $sugar_config, $mod_strings;

echo getClassicModuleTitle('seven', [$mod_strings['LBL_SEVEN_ACCOUNT_TITLE']]);

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

seven_smarty_render(new Account, $configurator, 'modules/seven/account_form.tpl', [
    '{account_type} => Account Type',
    '{annual_revenue} => Annual Revenue',
    '{assigned_user_id} => Assigned User ID',
    '{billing_address_city} => Billing Address City',
    '{billing_address_country} => Billing Address Country',
    '{billing_address_postalcode} => Billing Address Postal Code',
    '{billing_address_state} => Billing Address State',
    '{billing_address_street} => Billing Address Street',
    '{campaign_id} => Campaign ID',
    '{created_by} => Created By',
    '{date_entered} => Date Entered',
    '{date_modified} => Date Modified',
    '{deleted} => Deleted',
    '{description} => Description',
    '{employees} => Employees',
    '{id} => ID',
    '{industry} => Industry',
    '{modified_user_id} => Modified User ID',
    '{name} => Name',
    '{ownership} => Ownership',
    '{parent_id} => Parent ID',
    '{phone_alternate} => Phone Alternate',
    '{phone_fax} => Phone Fax',
    '{phone_office} => Phone Office',
    '{rating} => Rating',
    '{shipping_address_city} => Shipping Address City',
    '{shipping_address_country} => Shipping Address Country',
    '{shipping_address_postalcode} => Shipping Address Postal Code',
    '{shipping_address_state} => Shipping Address State',
    '{shipping_address_street} => Shipping Address Street',
    '{sic_code} => SIC Code',
    '{ticker_symbol} => Ticker Symbol',
    '{website} => Website',
], $settingsForm);

