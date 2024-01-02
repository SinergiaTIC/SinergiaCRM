<?php

$admin_option_defs = array();

// Create menu element
$admin_option_defs['Administration']['manage-test-data-link'] = array(
    'manage-test-data-link',
    'LBL_STIC_TEST_DATA_LINK_TITLE',
    'LBL_STIC_TEST_DATA_DESCRIPTION',
    './index.php?module=Administration&action=SticManageTestData',
    'activity-streams',
);


// Create the section if not exists. The SinergiaCRM section in the administration page is shared with several modules.
// Therefore, must check if the section has already been initialized. If not, just create it. If it has already been initialized, 
// mix the existing options with the new one.
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
