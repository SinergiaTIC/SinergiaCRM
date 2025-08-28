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

$dictionary['stic_Advanced_Web_Forms_DataBlocks'] = array(
    'table' => 'stic_advanced_web_forms_datablocks',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'base_module' => 
  array (
    'required' => true,
    'name' => 'base_module',
    'vname' => 'LBL_BASE_MODULE',
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
  'fields_def' => 
  array (
    'required' => false,
    'name' => 'fields_def',
    'vname' => 'LBL_FIELDS_DEF',
    'type' => 'text',
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
    'size' => '20',
    'studio' => 'visible',
    'rows' => '4',
    'cols' => '20',
  ),
  'block_order' => 
  array (
    'required' => false,
    'name' => 'block_order',
    'vname' => 'LBL_BLOCK_ORDER',
    'type' => 'int',
    'massupdate' => 0,
    'default' => '0',
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
  'duplicate_detection_fields' => 
  array (
    'required' => false,
    'name' => 'duplicate_detection_fields',
    'vname' => 'LBL_DUPLICATE_DETECTION_FIELDS',
    'type' => 'varchar',
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
    'len' => '255',
    'size' => '20',
  ),
  'duplicate_action' => 
  array (
    'required' => true,
    'name' => 'duplicate_action',
    'vname' => 'LBL_DUPLICATE_ACTION',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'skip',
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
    'options' => 'stic_advanced_web_forms_datablocks_duplicate_action_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'group_name' => 
  array (
    'required' => false,
    'name' => 'group_name',
    'vname' => 'LBL_GROUP_NAME',
    'type' => 'varchar',
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
    'len' => '255',
    'size' => '20',
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary["stic_Advanced_Web_Forms_DataBlocks"]["fields"]["stic_39d0rms_actions"] = array (
  'name' => 'stic_39d0rms_actions',
  'type' => 'link',
  'relationship' => 'stic_advanced_web_forms_datablocks_stic_advanced_web_forms_actions',
  'source' => 'non-db',
  'module' => 'stic_Advanced_Web_Forms_Actions',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_DATABLOCKS_STIC_ADVANCED_WEB_FORMS_ACTIONS_FROM_STIC_ADVANCED_WEB_FORMS_ACTIONS_TITLE',
);

$dictionary["stic_Advanced_Web_Forms_DataBlocks"]["fields"]["stic_2860_datablocks"] = array (
  'name' => 'stic_2860_datablocks',
  'type' => 'link',
  'relationship' => 'stic_advanced_web_forms_stic_advanced_web_forms_datablocks',
  'source' => 'non-db',
  'module' => 'stic_Advanced_Web_Forms',
  'bean_name' => false,
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_STIC_ADVANCED_WEB_FORMS_DATABLOCKS_FROM_STIC_ADVANCED_WEB_FORMS_TITLE',
  'id_name' => 'stic_36c0b_forms_ida',
);
$dictionary["stic_Advanced_Web_Forms_DataBlocks"]["fields"]["stic_835dblocks_name"] = array (
  'name' => 'stic_835dblocks_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_STIC_ADVANCED_WEB_FORMS_DATABLOCKS_FROM_STIC_ADVANCED_WEB_FORMS_TITLE',
  'save' => true,
  'id_name' => 'stic_36c0b_forms_ida',
  'link' => 'stic_2860_datablocks',
  'table' => 'stic_advanced_web_forms',
  'module' => 'stic_Advanced_Web_Forms',
  'rname' => 'name',
);
$dictionary["stic_Advanced_Web_Forms_DataBlocks"]["fields"]["stic_36c0b_forms_ida"] = array (
  'name' => 'stic_36c0b_forms_ida',
  'type' => 'link',
  'relationship' => 'stic_advanced_web_forms_stic_advanced_web_forms_datablocks',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_STIC_ADVANCED_WEB_FORMS_DATABLOCKS_FROM_STIC_ADVANCED_WEB_FORMS_DATABLOCKS_TITLE',
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('stic_Advanced_Web_Forms_DataBlocks', 'stic_Advanced_Web_Forms_DataBlocks', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Advanced_Web_Forms_DataBlocks']['fields']['description']['rows'] = '2'; // Make textarea fields shorter