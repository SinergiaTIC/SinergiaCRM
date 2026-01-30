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

$dictionary['stic_Advanced_Web_Forms_Links'] = array(
    'table' => 'stic_advanced_web_forms_links',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array (
  'sequence_number' => 
  array (
    'required' => false,
    'name' => 'sequence_number',
    'vname' => 'LBL_SEQUENCE_NUMBER',
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
    'inline_edit' => false,
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
  'parent_name' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'parent_name',
    'vname' => 'LBL_FLEX_RELATE',
    'type' => 'parent',
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
    'len' => 25,
    'size' => '20',
    'options' => 'parent_type_display',
    'studio' => 'visible',
    'type_name' => 'parent_type',
    'id_name' => 'parent_id',
    'parent_type' => 'record_type_display',
    'resetFieldInStudio' => 'true',
  ),
  'parent_type' => 
  array (
    'required' => false,
    'name' => 'parent_type',
    'vname' => 'LBL_PARENT_TYPE',
    // 'type' => 'parent_type',
    'type' => 'enum',
    'options' => 'moduleList',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 255,
    'size' => '20',
    'dbType' => 'varchar',
    'studio' => 'hidden',
  ),
  'parent_id' => 
  array (
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
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'record_action' => 
  array (
    'required' => false,
    'name' => 'record_action',
    'vname' => 'LBL_RECORD_ACTION',
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
    'options' => 'stic_advanced_web_forms_links_record_action_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'submitted_data' => 
  array (
    'required' => false,
    'name' => 'submitted_data',
    'vname' => 'LBL_SUBMITTED_DATA',
    'type' => 'text',
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
    'rows' => '4',
    'cols' => '20',
  ),
  'submitted_data_html' => 
  array (
    'name' => 'submitted_data_html',
    'vname' => 'LBL_SUBMITTED_DATA',
    'type' => 'varchar',
    'source' => 'non-db',
    'studio' => false,
    'inline_edit' => false,
    'function' => 
    array (
      'name' => 'getLinkDataHtml',
      'returns' => 'html',
      'include' => 'modules/stic_Advanced_Web_Forms_Links/Utils.php',
    ),
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary["stic_Advanced_Web_Forms_Links"]["fields"]["stic_1c31forms_links"] = array (
  'name' => 'stic_1c31forms_links',
  'type' => 'link',
  'relationship' => 'stic_advanced_web_forms_responses_stic_advanced_web_forms_links',
  'source' => 'non-db',
  'module' => 'stic_Advanced_Web_Forms_Responses',
  'bean_name' => false,
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_RESPONSES_STIC_ADVANCED_WEB_FORMS_LINKS_FROM_STIC_ADVANCED_WEB_FORMS_RESPONSES_TITLE',
  'id_name' => 'stic_e755sponses_ida',
);
$dictionary["stic_Advanced_Web_Forms_Links"]["fields"]["stic_c271_links_name"] = array (
  'name' => 'stic_c271_links_name',
  'type' => 'relate',
  'source' => 'non-db',
  'inline_edit' => false,
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_RESPONSES_STIC_ADVANCED_WEB_FORMS_LINKS_FROM_STIC_ADVANCED_WEB_FORMS_RESPONSES_TITLE',
  'save' => true,
  'id_name' => 'stic_e755sponses_ida',
  'link' => 'stic_1c31forms_links',
  'table' => 'stic_advanced_web_forms_responses',
  'module' => 'stic_Advanced_Web_Forms_Responses',
  'rname' => 'name',
);
$dictionary["stic_Advanced_Web_Forms_Links"]["fields"]["stic_e755sponses_ida"] = array (
  'name' => 'stic_e755sponses_ida',
  'type' => 'link',
  'relationship' => 'stic_advanced_web_forms_responses_stic_advanced_web_forms_links',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ADVANCED_WEB_FORMS_RESPONSES_STIC_ADVANCED_WEB_FORMS_LINKS_FROM_STIC_ADVANCED_WEB_FORMS_LINKS_TITLE',
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('stic_Advanced_Web_Forms_Links', 'stic_Advanced_Web_Forms_Links', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Advanced_Web_Forms_Links']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

$dictionary['stic_Advanced_Web_Forms_Links']['fields']['name']['massupdate'] = false;
$dictionary['stic_Advanced_Web_Forms_Links']['fields']['name']['inline_edit'] = false;

$dictionary['stic_Advanced_Web_Forms_Links']['fields']['assigned_user_name']['massupdate'] = false;
$dictionary['stic_Advanced_Web_Forms_Links']['fields']['assigned_user_name']['inline_edit'] = false;