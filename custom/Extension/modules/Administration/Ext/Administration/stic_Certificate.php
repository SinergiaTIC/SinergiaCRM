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

/**
 * Settings for the Digital Certificate administration option in SinergiaCRM.
 */

$admin_option_defs = array();

$admin_option_defs['Administration']['stic_digital_certificate'] = array(
    'stic_digital_certificate',
    'LBL_STIC_CERTIFICATE_TITLE',
    'LBL_STIC_CERTIFICATE_DESC',
    './index.php?module=Administration&action=SticManageCertificate',
    'security' // use an existing icon
);

// Ensure the SinergiaCRM section exists (pattern used in other files in your workspace)
if (!isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE']) || !isset($admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3])) {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'] = array(
        'LBL_SINERGIACRM_TAB_TITLE',
        '',
        false,
        $admin_option_defs,
        'LBL_SINERGIACRM_TAB_DESCRIPTION'
    );
} else {
    $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3]['Administration'] = array_merge(
        $admin_group_header['LBL_SINERGIACRM_TAB_TITLE'][3]['Administration'],
        $admin_option_defs['Administration']
    );
}