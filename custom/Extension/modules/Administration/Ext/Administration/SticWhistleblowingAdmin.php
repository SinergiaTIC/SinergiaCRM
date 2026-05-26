<?php

$admin_option_defs = [];

$admin_option_defs['Administration']['WhistleblowingConfig_Admin'] = [
    'LBL_WHISTLEBLOWING_CONFIG_TITLE',
    'LBL_WHISTLEBLOWING_CONFIG_TITLE',
    'LBL_WHISTLEBLOWING_CONFIG_DESCRIPTION',
    'index.php?module=Administration&action=WhistleblowingConfig_Admin'
];

if (!isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE']) || !isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3])) {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'] = array(
        'LBL_SINERGIACRM_TAB_TITLE',
        '',
        false,
        $admin_option_defs,
        'LBL_SINERGIACRM_TAB_DESCRIPTION',
    );
} else {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3] = array_replace_recursive(
        $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3], 
        $admin_option_defs
    );
}