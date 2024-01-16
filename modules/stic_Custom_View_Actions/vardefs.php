<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

$dictionary['stic_Custom_View_Actions'] = array(
    'table' => 'stic_custom_view_actions',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'action_order' => 
  array (
    'required' => false,
    'name' => 'action_order',
    'vname' => 'LBL_ACTION_ORDER',
    'type' => 'int',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Indica l\'ordre de la acció de personalització entre les diferents accions de personalització a aplicar',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'enable_range_search' => false,
    'disable_num_format' => '',
    'min' => false,
    'max' => false,
  ),
  'module' => 
  array (
    'required' => false,
    'name' => 'module',
    'vname' => 'LBL_MODULE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'El mòdul al qual s\'aplica la vista personalitzada',
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
  'action_type' => 
  array (
    'required' => false,
    'name' => 'action_type',
    'vname' => 'LBL_ACTION_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'El tipus d\'acció que s\'aplicarà en la vista personalitzada',
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
    'options' => 'stic_custom_views_action_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'module_view' => 
  array (
    'required' => false,
    'name' => 'module_view',
    'vname' => 'LBL_MODULE_VIEW',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'La vista del mòdul a la qual se li aplicarà l\'acció de personalització',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'field' => 
  array (
    'required' => false,
    'name' => 'field',
    'vname' => 'LBL_FIELD',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'El camp a modificar per l&#039;acció de personalització',
    'help' => 'Camp',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'field_change_type' => 
  array (
    'required' => false,
    'name' => 'field_change_type',
    'vname' => 'LBL_FIELD_CHANGE_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Tipus de canvi a realitzar sobre el camp',
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
    'options' => 'stic_custom_views_field_change_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'panel' => 
  array (
    'required' => false,
    'name' => 'panel',
    'vname' => 'LBL_PANEL',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'El panell a modificar per l\'acció de personalització',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'panel_change_type' => 
  array (
    'required' => false,
    'name' => 'panel_change_type',
    'vname' => 'LBL_PANEL_CHANGE_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Tipus de canvi a realitzar sobre el panell',
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
    'options' => 'stic_custom_views_panel_change_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'value' => 
  array (
    'required' => false,
    'name' => 'value',
    'vname' => 'LBL_VALUE',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Valor final després del canvi',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary["stic_Custom_View_Actions"]["fields"]["stic_custom_view_actions_stic_custom_view_customizations"] = array (
  'name' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'type' => 'link',
  'relationship' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'source' => 'non-db',
  'module' => 'stic_Custom_View_Customizations',
  'bean_name' => false,
  'vname' => 'LBL_STIC_CUSTOM_VIEW_ACTIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
  'id_name' => 'stic_custod6f1zations_ida',
);
$dictionary["stic_Custom_View_Actions"]["fields"]["stic_custom_view_actions_stic_custom_view_customizations_name"] = array (
  'name' => 'stic_custom_view_actions_stic_custom_view_customizations_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_ACTIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
  'save' => true,
  'id_name' => 'stic_custod6f1zations_ida',
  'link' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'table' => 'stic_custom_view_customizations',
  'module' => 'stic_custom_View_Customizations',
  'rname' => 'name',
);
$dictionary["stic_Custom_View_Actions"]["fields"]["stic_custod6f1zations_ida"] = array (
  'name' => 'stic_custod6f1zations_ida',
  'type' => 'link',
  'relationship' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_ACTIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_ACTIONS_TITLE',
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Custom_View_Actions', 'stic_Custom_View_Actions', array('basic','assignable','security_groups'));

