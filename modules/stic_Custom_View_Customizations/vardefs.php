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

$dictionary['stic_Custom_View_Customizations'] = array(
    'table' => 'stic_custom_view_customizations',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'customization_order' => 
  array (
    'required' => false,
    'name' => 'customization_order',
    'vname' => 'LBL_CUSTOMIZATION_ORDER',
    'type' => 'int',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Indica l\'ordre de la personalització entre les diferents personalitzacions del mòdul',
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
    'min' => 0,
    'max' => false,
    'validation' => 
    array (
      'type' => 'range',
      'min' => 0,
      'max' => false,
    ),
  ),
  'is_initial' => 
  array (
    'required' => false,
    'name' => 'is_initial',
    'vname' => 'LBL_IS_INITIAL',
    'type' => 'bool',
    'massupdate' => 0,
    'default' => '0',
    'no_default' => false,
    'comments' => '',
    'help' => 'Indica si és la personalització inicial a aplicar',
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
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'moduleList',
    'studio' => 'visible',
    'dependency' => false,
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
$dictionary["stic_Custom_View_Customizations"]["fields"]["stic_custom_view_actions_stic_custom_view_customizations"] = array (
  'name' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'type' => 'link',
  'relationship' => 'stic_custom_view_actions_stic_custom_view_customizations',
  'source' => 'non-db',
  'module' => 'stic_Custom_View_Actions',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_ACTIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_ACTIONS_TITLE',
);
$dictionary["stic_Custom_View_Customizations"]["fields"]["stic_custom_view_conditions_stic_custom_view_customizations"] = array (
  'name' => 'stic_custom_view_conditions_stic_custom_view_customizations',
  'type' => 'link',
  'relationship' => 'stic_custom_view_conditions_stic_custom_view_customizations',
  'source' => 'non-db',
  'module' => 'stic_Custom_View_Conditions',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_CONDITIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CONDITIONS_TITLE',
);
$dictionary["stic_Custom_View_Customizations"]["fields"]["stic_custom_view_customizations_stic_custom_views"] = array (
  'name' => 'stic_custom_view_customizations_stic_custom_views',
  'type' => 'link',
  'relationship' => 'stic_custom_view_customizations_stic_custom_views',
  'source' => 'non-db',
  'module' => 'stic_Custom_Views',
  'bean_name' => false,
  'vname' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEWS_FROM_STIC_CUSTOM_VIEWS_TITLE',
  'id_name' => 'stic_custo94b4m_views_ida',
);
$dictionary["stic_Custom_View_Customizations"]["fields"]["stic_custom_view_customizations_stic_custom_views_name"] = array (
  'name' => 'stic_custom_view_customizations_stic_custom_views_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEWS_FROM_STIC_CUSTOM_VIEWS_TITLE',
  'save' => true,
  'id_name' => 'stic_custo94b4m_views_ida',
  'link' => 'stic_custom_view_customizations_stic_custom_views',
  'table' => 'stic_custom_views',
  'module' => 'stic_Custom_Views',
  'rname' => 'name',
);
$dictionary["stic_Custom_View_Customizations"]["fields"]["stic_custo94b4m_views_ida"] = array (
  'name' => 'stic_custo94b4m_views_ida',
  'type' => 'link',
  'relationship' => 'stic_custom_view_customizations_stic_custom_views',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEWS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Custom_View_Customizations', 'stic_Custom_View_Customizations', array('basic','assignable','security_groups'));