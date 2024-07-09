<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

$admin_option_defs = array();

// Create menu element
$admin_option_defs['Administration']['configure_group_tabs_new'] = [
    'ConfigureTabs',
    'LBL_STIC_ADVANCED_MENU',
    'LBL_STIC_ADVANCED_MENU_DESC',
    './index.php?action=wizard&module=Studio&wizard=StudioWizard&option=SticAdvancedMenuEdit',
    'configure-module-menu-filters-new'
];


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
