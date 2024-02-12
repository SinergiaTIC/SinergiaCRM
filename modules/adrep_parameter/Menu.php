<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_parameter', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_parameter&action=EditView&return_module=adrep_parameter&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_parameter');
}
if(ACLController::checkAccess('adrep_parameter', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_parameter&action=index&return_module=adrep_parameter&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_parameter');
}
