<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

// STIC-Custom 20261023 DEV - Related records panel
// Registers the admin link inside the "SinergiaCRM" section (bottom group) of
// the administration panel.
// See stic-ai-handbook: output/features/related-records-panel

$admin_option_defs = array();

// Create menu element
$admin_option_defs['Administration']['stic_related_panel'] = array(
    'Administration',
    'LBL_STIC_RRP_LINK_TITLE',
    'LBL_STIC_RRP_DESCRIPTION',
    './index.php?module=Administration&action=sticrelatedpanel',
    'stic-related-panel',
);

// Create the SinergiaCRM section if it does not exist yet; otherwise merge the
// new option into it (the section is shared by several SinergiaCRM features).
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
