<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('adrep_chart', 'edit', true)){
    $module_menu[]=array('index.php?module=adrep_chart&action=EditView&return_module=adrep_chart&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'adrep_chart');
}
if(ACLController::checkAccess('adrep_chart', 'list', true)){
    $module_menu[]=array('index.php?module=adrep_chart&action=index&return_module=adrep_chart&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'adrep_chart');
}
