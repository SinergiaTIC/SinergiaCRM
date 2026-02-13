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

$dictionary['stic_Advanced_Web_Forms_Response_Details'] = array(
    'table' => 'stic_advanced_web_forms_response_details',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array (
  'question_sort_order' => 
  array (
    'required' => false,
    'name' => 'question_sort_order',
    'vname' => 'LBL_QUESTION_SORT_ORDER',
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
    'min' => false,
    'max' => false,
  ),
  'question_key' => 
  array (
    'required' => false,
    'name' => 'question_key',
    'vname' => 'LBL_QUESTION_KEY',
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
  'question_label' => 
  array (
    'required' => false,
    'name' => 'question_label',
    'vname' => 'LBL_QUESTION_LABEL',
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
  'question_help_text' => 
  array (
    'required' => false,
    'name' => 'question_help_text',
    'vname' => 'LBL_QUESTION_HELP_TEXT',
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
  'question_section' => 
  array (
    'required' => false,
    'name' => 'question_section',
    'vname' => 'LBL_QUESTION_SECTION',
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
  'answer_value' => 
  array (
    'required' => false,
    'name' => 'answer_value',
    'vname' => 'LBL_ANSWER_VALUE',
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
  'answer_text' => 
  array (
    'required' => false,
    'name' => 'answer_text',
    'vname' => 'LBL_ANSWER_TEXT',
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
  'answer_form_type' => 
  array (
    'required' => false,
    'name' => 'answer_form_type',
    'vname' => 'LBL_ANSWER_FORM_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'text',
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
    'options' => 'stic_advanced_web_forms_field_in_form_type_list', // text, select, date...
    'studio' => 'visible',
    'dependency' => false,
  ),
  'answer_integer' => 
  array (
    'required' => false,
    'name' => 'answer_integer',
    'vname' => 'LBL_ANSWER_INTEGER',
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
    'min' => false,
    'max' => false,
  ),
  'stic_advanced_web_forms_id_c' => 
  array (
    'required' => false,
    'name' => 'stic_advanced_web_forms_id_c',
    'vname' => 'LBL_FORM_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'form' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'form',
    'vname' => 'LBL_FORM',
    'type' => 'relate',
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
    'id_name' => 'stic_advanced_web_forms_id_c',
    'ext2' => 'stic_Advanced_Web_Forms',
    'module' => 'stic_Advanced_Web_Forms',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
    'resetFieldInStudio' => 'true',
  ),
  'stic_advanced_web_forms_responses_id_c' => 
  array (
    'required' => false,
    'name' => 'stic_advanced_web_forms_responses_id_c',
    'vname' => 'LBL_RESPONSE_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'response' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'response',
    'vname' => 'LBL_RESPONSE',
    'type' => 'relate',
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
    'len' => '255',
    'size' => '20',
    'id_name' => 'stic_advanced_web_forms_responses_id_c',
    'ext2' => 'stic_Advanced_Web_Forms_Responses',
    'module' => 'stic_Advanced_Web_Forms_Responses',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
    'resetFieldInStudio' => 'true',
  ),
  'responses_link' => 
  array (
    'name' => 'responses_link',
    'vname' => 'LBL_RESPONSE',
    'type' => 'link',
    'relationship' => 'stic_awf_responses_details', 
    'link_type' => 'one',
    'side' => 'right',
    'source' => 'non-db',
  ),
),
    'relationships' => array (
      'stic_awf_responses_details' => 
      array (
        'lhs_module' => 'stic_Advanced_Web_Forms_Responses',
        'lhs_table' => 'stic_advanced_web_forms_responses',
        'lhs_key' => 'id',
        'rhs_module' => 'stic_Advanced_Web_Forms_Response_Details',
        'rhs_table' => 'stic_advanced_web_forms_response_details',
        'rhs_key' => 'stic_advanced_web_forms_responses_id_c', 
        'relationship_type' => 'one-to-many',
      ),
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Advanced_Web_Forms_Response_Details', 'stic_Advanced_Web_Forms_Response_Details', array('basic','assignable','security_groups'));
