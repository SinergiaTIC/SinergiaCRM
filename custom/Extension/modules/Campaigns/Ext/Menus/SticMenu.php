<?php

global $module_menu;
if (ACLController::checkAccess('Campaigns', 'edit', true)) {
    array_unshift($module_menu, array(
        "index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index",
        $mod_strings['LNK_NEW_CAMPAIGN'], "Create Classic Campaign")
    );
}
if (ACLController::checkAccess('Campaigns', 'edit', true)) {
    $module_menu[] = array(
        "index.php?module=stic_Web_Forms&action=assistant&webFormClass=Donation&return_module=Campaigns&return_action=index",
        translate('LBL_STIC_WEB_FORMS_DONATION', 'stic_Web_Forms'), "Create_stic_Web_Forms", 'Campaigns',
    );
}
