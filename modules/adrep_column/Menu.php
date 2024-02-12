<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_column', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_column&action=EditView&return_module=adrep_column&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_column');
}
if(ACLController::checkAccess('adrep_column', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_column&action=index&return_module=adrep_column&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_column');
}
