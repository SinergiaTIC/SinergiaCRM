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


$dictionary['stic_Custom_Views'] = array(
    'table' => 'stic_custom_views',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'user_type' => array (
            'required' => true,
            'name' => 'user_type',
            'vname' => 'LBL_USER_TYPE',
            'popupHelp' => 'LBL_USER_TYPE_DESC',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => 'all',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_custom_views_user_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'user_profile' => array (
            'required' => true,
            'name' => 'user_profile',
            'vname' => 'LBL_USER_PROFILE',
            'popupHelp' => 'LBL_USER_PROFILE_DESC',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_custom_views_user_profile_list',
            'studio' => 'visible',
            'dependency' => false,
            'default' => 'all',
        ),
        'roles' => array (
            'required' => false,
            'name' => 'roles',
            'vname' => 'LBL_ROLES',
            'popupHelp' => 'LBL_ROLES_DESC',
            'type' => 'multienum',
            'massupdate' => 0,
            'default' => '^^',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'options' => 'stic_custom_views_roleList',
            'studio' => 'visible',
            'isMultiSelect' => true,
        ),
        'security_groups' => array (
            'required' => false,
            'name' => 'security_groups',
            'vname' => 'LBL_SECURITY_GROUPS',
            'popupHelp' => 'LBL_SECURITY_GROUPS_DESC',
            'type' => 'multienum',
            'massupdate' => 0,
            'default' => '^^',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'options' => 'stic_custom_views_securityGroupList',
            'studio' => 'visible',
            'isMultiSelect' => true,
        ),
        'view_module' => array (
            'required' => false,
            'name' => 'view_module',
            'vname' => 'LBL_MODULE',
            'popupHelp' => 'LBL_MODULE_DESC',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_custom_views_moduleList',
            'studio' => 'visible',
            'dependency' => false,
        ),
        
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Custom_Views', 'stic_Custom_Views', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Custom_Views']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
