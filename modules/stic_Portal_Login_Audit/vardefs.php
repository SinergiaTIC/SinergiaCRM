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
 * stic_Portal_Login_Audit - SinergiaCRM Basic module (same vardefs idiom as stic_Bookings).
 * Maintains the portal infrastructure table `stic_portal_login_audit` through the standard
 * vardefs + quick-repair pipeline. Hidden from the UI (see
 * custom/Extension/application/Ext/Include/SticModules.php).
 */

$dictionary['stic_Portal_Login_Audit'] = array(
    'table' => 'stic_portal_login_audit',
    'audited' => false,
    'inline_edit' => false,
    'unified_search' => false,
    'fields' => array(
        'parent_name' => array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'parent_name',
            'vname' => 'LBL_FLEX_RELATE',
            'type' => 'parent',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'inline_edit' => 1,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 25,
            'size' => '20',
            'options' => 'parent_type_display',
            'studio' => 'visible',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'parent_type' => 'record_type_display',
        ),
        'parent_type' => array(
            'required' => false,
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 255,
            'size' => '20',
            'dbType' => 'varchar',
            'studio' => 'hidden',
        ),
        'parent_id' => array(
            'required' => false,
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 36,
            'size' => '20',
        ),
        'username' => array(
            'required' => false,
            'name' => 'username',
            'vname' => 'LBL_USERNAME',
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
            'len' => 100,
            'size' => '20',
        ),
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
        'user_agent' => array(
            'required' => false,
            'name' => 'user_agent',
            'vname' => 'LBL_USER_AGENT',
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
            'len' => 500,
            'size' => '20',
        ),
        'success' => array(
            'required' => false,
            'name' => 'success',
            'vname' => 'LBL_SUCCESS',
            'type' => 'bool',
            'no_default' => false,
            'massupdate' => 0,
            'inline_edit' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'default' => 0,
        ),
        'failure_reason' => array(
            'required' => false,
            'name' => 'failure_reason',
            'vname' => 'LBL_FAILURE_REASON',
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
            'len' => 32,
            'size' => '20',
        ),
        'auth_method' => array(
            'required' => false,
            'name' => 'auth_method',
            'vname' => 'LBL_AUTH_METHOD',
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
            'len' => 20,
            'size' => '20',
        ),
    ),
    'indices' => array(
            array(
                'name' => 'idx_audit_parent_date',
                'type' => 'index',
                'fields' => array('parent_id', 'date_entered'),
            ),
            array(
                'name' => 'idx_audit_username_date',
                'type' => 'index',
                'fields' => array('username', 'date_entered'),
            ),
            array(
                'name' => 'idx_audit_ip_date',
                'type' => 'index',
                'fields' => array('ip_address', 'date_entered'),
            ),
    ),
    'relationships' => array(),
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Portal_Login_Audit', 'stic_Portal_Login_Audit', array('basic', 'assignable', 'security_groups'));

