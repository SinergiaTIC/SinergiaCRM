<?php

$admin_option_defs = array();

// Create SinergiaDA element in SinergiaCRM admin section
require_once 'modules/stic_Settings/Utils.php';
$sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;
if (!empty($sdaEnabled) && $sdaEnabled) {
    $admin_option_defs['Administration']['manage-sda-integration-link'] = array(
        'manage-sda-integration-link',
        'LBL_STIC_MANAGE_SDA_ACTIONS_LINK_TITLE',
        'LBL_STIC_MANAGE_SDA_ACTIONS_DESCRIPTION',
        './index.php?module=Administration&action=sticmanagesdaintegration',
        'activity-streams',
    );

// Create SinergiaCRM admin section if not exists.
    // SinergiaCRM admin section is shared by several modules. If it is not initialized, it must be created.
    // Otherwise, old elements should be mixed with the new one.
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

} else {
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA setting is not = 1.");
}
