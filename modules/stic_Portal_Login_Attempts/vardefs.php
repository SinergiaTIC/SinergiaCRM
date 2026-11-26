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
/*
 * stic_Portal_Login_Attempts - SinergiaCRM Basic module (same vardefs idiom as stic_Bookings).
 * Maintains the portal infrastructure table `stic_portal_login_attempts` through the standard
 * vardefs + quick-repair pipeline. Hidden from the UI (see
 * custom/Extension/application/Ext/Include/SticModules.php).
 */

$dictionary['stic_Portal_Login_Attempts'] = array(
    'table' => 'stic_portal_login_attempts',
    'audited' => false,
    'inline_edit' => false,
    'unified_search' => false,
    'fields' => array(
        'ip_address' => array(
            'required' => false,
            'name' => 'ip_address',
            'vname' => 'LBL_IP_ADDRESS',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'inline_edit' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 45,
            'size' => '20',
        ),
        'failed_attempts' => array(
            'required' => false,
            'name' => 'failed_attempts',
            'vname' => 'LBL_FAILED_ATTEMPTS',
            'type' => 'int',
            'massupdate' => '0',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'inline_edit' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 11,
            'size' => '20,',
            'disable_num_format' => 1,
            'default' => 1,
        ),
        'locked_until' => array(
            'required' => false,
            'name' => 'locked_until',
            'vname' => 'LBL_LOCKED_UNTIL',
            'type' => 'datetimecombo',
            'massupdate' => 0,
            'no_default' => false,
            'display_default' => '',
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'inline_edit' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
        ),
        'last_attempt' => array(
            'required' => false,
            'name' => 'last_attempt',
            'vname' => 'LBL_LAST_ATTEMPT',
            'type' => 'datetimecombo',
            'massupdate' => 0,
            'no_default' => false,
            'display_default' => '',
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'inline_edit' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
        ),
    ),
    'indices' => array(
            array(
                'name' => 'idx_attempts_ip',
                'type' => 'index',
                'fields' => array('ip_address'),
            ),
    ),
    'relationships' => array(),
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Portal_Login_Attempts', 'stic_Portal_Login_Attempts', array('basic', 'assignable', 'security_groups'));

