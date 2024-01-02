<?php

if (ACLController::checkAccess('FP_Event_Locations', 'import', true)) {
    $module_menu[] = array("index.php?module=Import&action=Step1&import_module=FP_Event_Locations&return_module=FP_Event_Locations&return_action=index", $mod_strings['LNK_IMPORT_FP_EVENT_LOCATIONS'], "Import", 'FP_Event_Locations');
}
