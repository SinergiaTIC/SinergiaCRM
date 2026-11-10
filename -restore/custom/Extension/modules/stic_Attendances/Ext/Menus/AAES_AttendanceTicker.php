<?php
// STIC-Custom AAES - 20261109 - Attendance ticker module menu link (IMPL-AAES-001, Fase B)
// Adds the "Pasador de asistencias" link to the stic_Attendances module menu,
// guarded by module ACL. Label from the module language ext files.
// END STIC-Custom AAES

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings;

if (ACLController::checkAccess('stic_Attendances', 'index', true)) {
    $module_menu[] = array(
        'index.php?module=stic_Attendances&action=AAESAttendanceTicker',
        $mod_strings['LBL_AAES_TICKER_MENU'],
        'List',
        'stic_Attendances',
    );
}
