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
$dictionary['stic_Advanced_Web_Forms_Answers'] = array(
    'table' => 'stic_advanced_web_forms_answers',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array (
  'question_sort_order' => 
  array(
    'name' => 'question_sort_order',
    'vname' => 'LBL_QUESTION_SORT_ORDER',
    'type' => 'int',
    'default' => 0,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
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
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
  ),
  'question_label' => 
  array (
    'required' => false,
    'name' => 'question_label',
    'vname' => 'LBL_QUESTION_LABEL',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
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
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
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
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',    
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
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
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
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
  ),
  'answer_form_type' => 
  array(
    'name' => 'answer_form_type',
    'vname' => 'LBL_ANSWER_FORM_TYPE',
    'type' => 'enum',
    'options' => 'stic_advanced_web_forms_field_in_form_type_list', // text, select, date...
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '50',
  ),
  'answer_integer' => 
  array(
    'name' => 'answer_integer',
    'vname' => 'LBL_ANSWER_INTEGER',
    'type' => 'int',
    'default' => 0,
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
  ),
  'form_id' => 
  array(
    'name' => 'form_id',
    'type' => 'id',
    'vname' => 'LBL_FORM_ID',
    'required' => true,
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
  ),
  'form_link' => 
  array(
    'name' => 'form_link',
    'type' => 'link',
    'relationship' => 'stic_awf_forms_answers', 
    'vname' => 'LBL_FORM_LINK',
    'link_type' => 'one',
    'module' => 'stic_Advanced_Web_Forms',
    'bean_name' => 'stic_Advanced_Web_Forms',
    'source' => 'non-db',
    'side' => 'right',
    'id_name' => 'form_id', // foreign key field
    'inline_edit' => false,
  ),
  'form_name' => 
  array(
    'name' => 'form_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_FORM_NAME',
    'save' => true,
    'id_name' => 'form_id', // foreign key field
    'link' => 'form_link',  // link field name
    'table' => 'stic_advanced_web_forms',
    'module' => 'stic_Advanced_Web_Forms',
    'rname' => 'name', // related field to display
    'inline_edit' => false,
  ),
  'response_id' => 
  array(
    'name' => 'response_id',
    'type' => 'id',
    'vname' => 'LBL_RESPONSE_ID',
    'required' => true,
    'reportable' => true,
    'comments' => '',
    'help' => '',
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
  ),
  'response_link' => 
  array(
    'name' => 'response_link',
    'type' => 'link',
    'relationship' => 'stic_awf_responses_answers',
    'vname' => 'LBL_RESPONSE_LINK',
    'link_type' => 'one',
    'module' => 'stic_Advanced_Web_Forms_Responses',
    'bean_name' => 'stic_Advanced_Web_Forms_Responses',
    'source' => 'non-db',
    'side' => 'right',
    'id_name' => 'response_id', // foreign key field
    'inline_edit' => false,
  ),
  'response_name' => 
  array(
    'name' => 'response_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_RESPONSE_NAME',
    'save' => true,
    'id_name' => 'response_id', // foreign key field
    'link' => 'response_link', // link field name
    'table' => 'stic_advanced_web_forms_responses',
    'module' => 'stic_Advanced_Web_Forms_Responses',
    'rname' => 'name', // related field to display
    'inline_edit' => false,
  ),  
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary['stic_Advanced_Web_Forms_Answers']['relationships']['stic_awf_forms_answers'] = 
array(
  'lhs_module' => 'stic_Advanced_Web_Forms',
  'lhs_table' => 'stic_advanced_web_forms',
  'lhs_key' => 'id',
  'rhs_module' => 'stic_Advanced_Web_Forms_Answers',
  'rhs_table' => 'stic_advanced_web_forms_answers',
  'rhs_key' => 'form_id', 
  'relationship_type' => 'one-to-many',
);

$dictionary['stic_Advanced_Web_Forms_Answers']['relationships']['stic_awf_responses_answers'] = 
array(
  'lhs_module' => 'stic_Advanced_Web_Forms_Responses',
  'lhs_table' => 'stic_advanced_web_forms_responses',
  'lhs_key' => 'id',
  'rhs_module' => 'stic_Advanced_Web_Forms_Answers',
  'rhs_table' => 'stic_advanced_web_forms_answers',
  'rhs_key' => 'response_id', 
  'relationship_type' => 'one-to-many',
);

$dictionary['stic_Advanced_Web_Forms_Answers']['indices'] = array(
  array('name' => 'idx_awf_answers_del', 'type' => 'index', 'fields' => array('deleted')),
  // Index for grouping by response
  array('name' => 'idx_awf_answers_resp_id', 'type' => 'index', 'fields' => array('response_id', 'deleted')),
  // Index for grouping by form
  array('name' => 'idx_awf_answers_form_id', 'type' => 'index', 'fields' => array('form_id', 'deleted')),
  // Index for grouping by field
  array('name' => 'idx_awf_answers_q_key', 'type' => 'index', 'fields' => array('question_key', 'deleted')),
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Advanced_Web_Forms_Answers', 'stic_Advanced_Web_Forms_Answers', array('basic','assignable','security_groups'));

