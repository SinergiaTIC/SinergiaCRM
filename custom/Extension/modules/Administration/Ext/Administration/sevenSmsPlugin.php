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

$admin_options_defs = [];
$admin_options_defs['Administration']['Seven_Index'] = [
    'PANELSETTINGS',
    'LBL_SEVEN_CONFIGURATION_TITLE',
    'LBL_SEVEN_CONFIGURATION_DESC',
    './index.php?module=seven&action=index',
];
$admin_options_defs['Administration']['Seven_Contact'] = [
    'PANELSETTINGS',
    'LBL_SEVEN_TEMPLATE_CONFIGURATION_TITLE',
    'LBL_SEVEN_TEMPLATE_CONFIGURATION_DESC',
    './index.php?module=seven&action=contact',
];
$admin_options_defs['Administration']['Seven_Lead'] = [
    'PANELSETTINGS',
    'LBL_SEVEN_LEAD_CONFIGURATION_TITLE',
    'LBL_SEVEN_LEAD_CONFIGURATION_DESC',
    './index.php?module=seven&action=lead',
];
$admin_options_defs['Administration']['Seven_Account'] = [
    'PANELSETTINGS',
    'LBL_SEVEN_ACCOUNT_CONFIGURATION_TITLE',
    'LBL_SEVEN_ACCOUNT_CONFIGURATION_DESC',
    './index.php?module=seven&action=account',
];
$admin_options_defs['Administration']['Seven_Employee'] = [
    'PANELSETTINGS',
    'LBL_SEVEN_EMPLOYEE_CONFIGURATION_TITLE',
    'LBL_SEVEN_EMPLOYEE_CONFIGURATION_DESC',
    './index.php?module=seven&action=employee',
];

$admin_group_header[] = [
    'LBL_SEVEN_TITLE',
    'LBL_SEVEN_DESC',
    false,
    $admin_options_defs,
];