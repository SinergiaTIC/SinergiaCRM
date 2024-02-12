<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_role', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_role&action=EditView&return_module=adrep_role&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_role');
}
if(ACLController::checkAccess('adrep_role', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_role&action=index&return_module=adrep_role&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_role');
}
