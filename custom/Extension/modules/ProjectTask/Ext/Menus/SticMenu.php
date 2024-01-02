<?php

$module_menu = array();

    
if(ACLController::checkAccess('ProjectTask', 'edit', true)) {
    $module_menu[] = array("index.php?module=ProjectTask&action=EditView&return_module=ProjectTask&return_action=DetailView", $mod_strings['LNK_NEW_PROJECT_TASK'], 'Create');
}
if (ACLController::checkAccess('ProjectTask', 'list', true)) {
    $module_menu[] = array('index.php?module=ProjectTask&action=index', $mod_strings['LNK_PROJECT_TASK_LIST'], 'List');
}

if(ACLController::checkAccess('ProjectTask', 'import', true)){
    $module_menu[]=array('index.php?module=Import&action=Step1&import_module=ProjectTask&return_module=ProjectTask&return_action=index', $mod_strings['LNK_IMPORT_PROJECTTASK'], 'Import', 'ProjectTask');
}
