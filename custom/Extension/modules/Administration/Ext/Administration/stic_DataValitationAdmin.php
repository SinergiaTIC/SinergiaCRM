<?php

$admin_option_defs = array();

// Create admin menu
$admin_option_defs['Administration']['stic_Validation_Actions'] = array(
    'stic_Validation_Actions',
    'LBL_STIC_VALIDATION_ACTIONS_LINK_TITLE',
    'LBL_STIC_VALIDATION_ACTIONS_DESCRIPTION',
    './index.php?module=stic_Validation_Actions&action=index',
    'stic-validation-actions',
);

// Create de section if not exists. The SinergiaCRM section in the administration section is a shared section with several modules,
// therefore, we must check if the section has already been initialized.
// If you have not already done so, create it again, if it has already been initialized, mix the old options with the new one.
if (!isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE']) || !isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3])) {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'] = array(
        'LBL_SINERGIACRM_TAB_TITLE',
        '',
        false,
        $admin_option_defs,
        'LBL_SINERGIACRM_TAB_DESCRIPTION',

    );
} else {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3] = array_replace_recursive($admin_option_defs, $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3]);
}
