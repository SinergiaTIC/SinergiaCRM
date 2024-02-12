<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_cache', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_cache&action=EditView&return_module=adrep_cache&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_cache');
}
if(ACLController::checkAccess('adrep_cache', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_cache&action=index&return_module=adrep_cache&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_cache');
}
