<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('AOS_Invoices', 'edit', true)) {
    $module_menu[]=array("index.php?module=AOS_Invoices&action=EditView&return_module=AOS_Invoices&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"Create", 'AOS_Invoices');
}
if (ACLController::checkAccess('AOS_Invoices', 'list', true)) {
    $module_menu[]=array("index.php?module=AOS_Invoices&action=index&return_module=AOS_Invoices&return_action=DetailView", $mod_strings['LNK_LIST'],"List", 'AOS_Invoices');
}
if (ACLController::checkAccess('AOS_Invoices', 'import', true)) {
    $module_menu[]=array("index.php?module=Import&action=Step1&import_module=AOS_Invoices&return_module=AOS_Invoices&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'AOS_Invoices');
}
if (ACLController::checkAccess('AOS_Invoices', 'import', true)) {
    $module_menu[]=array("index.php?module=Import&action=Step1&import_module=AOS_Products_Quotes&return_module=AOS_Invoices&return_action=index", $mod_strings['LBL_IMPORT_LINE_ITEMS'],"Import", 'AOS_Products_Quotes');
}

require_once 'custom/modules/AOS_Invoices/SticUtils.php';
if (AOS_InvoicesUtils::isVerifactuActivated()) {
    $module_menu[] = array(
        "index.php?module=AOS_Invoices&action=QueryAeatInvoices",
        $mod_strings['LBL_VERIFACTU_QUERY_MENU'],
        "Verifactu",
        'AOS_Invoices',
    );
}
