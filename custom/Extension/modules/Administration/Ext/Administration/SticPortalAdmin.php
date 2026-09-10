<?php
$admin_option_defs = array();
$admin_option_defs['Administration']['stic_portal_config'] = array(
    'Portal',
    'LBL_STIC_PORTAL_CONFIG_LINK_TITLE',
    'LBL_STIC_PORTAL_CONFIG_DESCRIPTION',
    './index.php?module=Administration&action=sticportalconfig',
    'stic-portal-config',
);
if (!isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE']) || !isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3])) {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'] = array('LBL_SINERGIACRM_TAB_TITLE', '', false, $admin_option_defs, 'LBL_SINERGIACRM_TAB_DESCRIPTION');
} else {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3] = array_replace_recursive($admin_option_defs, $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3]);
}
