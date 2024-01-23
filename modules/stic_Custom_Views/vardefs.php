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
    'fields' => array (
  'view_name' => 
  array(
    'name' => 'view_name',
    'vname' => 'LBL_VIEW_NAME',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'required' => true,
    'len' => 255,
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
    'size' => '20',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'view_module' => 
  array (
    'required' => false,
    'name' => 'view_module',
    'vname' => 'LBL_VIEW_MODULE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'moduleList',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'user_type' => 
  array (
    'required' => false,
    'name' => 'user_type',
    'vname' => 'LBL_USER_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'all',
    'no_default' => false,
    'comments' => '',
    'help' => 'Tipus d\'usuari al que se li aplicarà la vista personalitzada',
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
  'roles' => 
  array (
    'required' => false,
    'name' => 'roles',
    'vname' => 'LBL_ROLES',
    'type' => 'multienum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Rols específics dels usuaris als que se li aplicarà la vista personalitzada',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'dynamic_role_list',
    'studio' => 'visible',
    'isMultiSelect' => true,
  ),
  'security_groups' => 
  array (
    'required' => false,
    'name' => 'security_groups',
    'vname' => 'LBL_SECURITY_GROUPS',
    'type' => 'multienum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Grups de seguretat específics dels usuaris als que se li aplicarà la vista personalitzada',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'dynamic_security_group_list',
    'studio' => 'visible',
    'isMultiSelect' => true,
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary["stic_Custom_Views"]["fields"]["stic_custom_views_stic_custom_view_customizations"] = array (
  'name' => 'stic_custom_views_stic_custom_view_customizations',
  'type' => 'link',
  'relationship' => 'stic_custom_views_stic_custom_view_customizations',
  'source' => 'non-db',
  'module' => 'stic_Custom_View_Customizations',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_CUSTOM_VIEWS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
);

if (!class_exists('VardefManager')) {
  require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('stic_Custom_Views', 'stic_Custom_Views', array('basic','assignable'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Custom_Views']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Custom_Views']['fields']['name']['inline_edit'] = false; // Not inline_edit for name
$dictionary['stic_Custom_Views']['fields']['description']['rows'] = '2'; // Make textarea fields shorter