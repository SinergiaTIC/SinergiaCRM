<?php

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('stic_Bookings', 'edit', true)) {
    $module_menu[] = array("index.php?module=stic_Bookings&action=EditView&return_module=stic_Bookings_Calendar&return_action=index", translate('LNK_NEW_RECORD', 'stic_Bookings'), "create-stic-bookings", 'stic_Bookings');
}

if (ACLController::checkAccess('stic_Resources', 'edit', true)) {
    $module_menu[] = array("index.php?module=stic_Resources&action=EditView&return_module=stic_Bookings_Calendar&return_action=index", translate('LNK_NEW_RECORD', 'stic_Resources'), "create-stic-resources", 'stic_Resources');
}
