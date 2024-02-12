<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_menu_link', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_menu_link&action=EditView&return_module=adrep_menu_link&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_menu_link');
}
if(ACLController::checkAccess('adrep_menu_link', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_menu_link&action=index&return_module=adrep_menu_link&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_menu_link');
}
