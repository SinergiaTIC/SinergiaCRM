<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

$dictionary['adrep_report'] = array(
	'table'=>'adrep_report',
	'audited'=>true,
    'inline_edit'=>true,
		'duplicate_merge'=>true,
		'fields'=>array (
  'primary_module' =>
  array (
    'required' => false,
    'name' => 'primary_module',
    'vname' => 'LBL_PRIMARY_MODULE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Primary module this report evolves around.',
    'help' => 'Primary module this report evolves around. (Not required.)',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
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
  'query' =>
  array (
    'required' => true,
    'name' => 'query',
    'vname' => 'LBL_QUERY',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'SQL query for the report.',
    'help' => 'SQL query for the report.  (May include simple or complex queries, smarty style paramters/variables or even calling a stored procedure.',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'studio' => 'visible',
    'rows' => '10',
    'cols' => '40',
  ),
  'css_file' =>
  array (
    'required' => true,
    'name' => 'css_file',
    'vname' => 'LBL_CSS_FILE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Basic',
    'no_default' => false,
    'comments' => 'Style / CSS stylesheet to use for the report.',
    'help' => 'Style / CSS stylesheet to use for the report.',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'adrep_css_file_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'custom_css' =>
  array (
    'required' => false,
    'name' => 'custom_css',
    'vname' => 'LBL_CUSTOM_CSS',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Additional or customised CSS styling for the report.',
    'help' => 'Additional or customised CSS styling for the report.',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'studio' => 'visible',
    'rows' => '10',
    'cols' => '40',
  ),
  'permission' =>
  array (
    'required' => true,
    'name' => 'permission',
    'vname' => 'LBL_PERMISSION',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'Private',
    'no_default' => false,
    'comments' => 'Who may execute this report.',
    'help' => 'Who may execute this report.  Private=assigned user only, public=everybody, Roles=users with at least one of the selected roles.',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'adrep_permission_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full text of the note',
    'rows' => '6',
    'cols' => '40',
    'required' => false,
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Full text of the note',
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
    'studio' => 'visible',
  ),
  'portal_flag' => 
  array (
    'required' => false,
    'name' => 'portal_flag',
    'vname' => 'LBL_PORTAL_FLAG',
    'type' => 'bool',
    'massupdate' => 0,
    'default' => '0',
    'no_default' => false,
    'comments' => 'Show this report in the portal.',
    'help' => 'Show this report in the portal.',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
	'chart_type' =>
	array (
		'required' => false,
		'name' => 'chart_type',
		'vname' => 'LBL_CHART_TYPE_FIELD',
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
		'options' => 'adrep_chart_method_list',
		'studio' => 'visible',
		'dependency' => false,
	),
  'subpanel_adrep_parameter' =>
  array (
    'name' => 'subpanel_adrep_parameter',
    'type' => 'link',
    'relationship' => 'rel_adrep_report2adrep_parameter',
    'module'=>'adrep_parameter',
    'bean_name'=>'adrep_parameter',
    'source'=>'non-db',
    'vname'=>'LBL_SUBPANEL_adrep_report2adrep_parameter',
  ),

  'subpanel_adrep_column' =>
  array (
    'name' => 'subpanel_adrep_column',
    'type' => 'link',
    'relationship' => 'rel_adrep_report2adrep_column',
    'module'=>'adrep_column',
    'bean_name'=>'adrep_column',
    'source'=>'non-db',
    'vname'=>'LBL_SUBPANEL_adrep_report2adrep_column',
  ),

  'subpanel_adrep_role' =>
  array (
    'name' => 'subpanel_adrep_role',
    'type' => 'link',
    'relationship' => 'rel_adrep_report2adrep_role',
    'module'=>'adrep_role',
    'bean_name'=>'adrep_role',
    'source'=>'non-db',
    'vname'=>'LBL_SUBPANEL_adrep_report2adrep_role',
  ),

  'subpanel_adrep_menu_link' =>
  array (
    'name' => 'subpanel_adrep_menu_link',
    'type' => 'link',
    'relationship' => 'rel_adrep_report2adrep_menu_link',
    'module'=>'adrep_menu_link',
    'bean_name'=>'adrep_menu_link',
    'source'=>'non-db',
    'vname'=>'LBL_SUBPANEL_adrep_report2adrep_menu_link',
  ),

  'subpanel_adrep_chart' =>
  array (
    'name' => 'subpanel_adrep_chart',
    'type' => 'link',
    'relationship' => 'rel_adrep_report2adrep_chart',
    'module'=>'adrep_chart',
    'bean_name'=>'adrep_chart',
    'source'=>'non-db',
    'vname'=>'LBL_SUBPANEL_adrep_report2adrep_chart',
  ),

),
	'relationships'=>array (

		'rel_adrep_report2adrep_parameter' =>
		array('lhs_module'=> 'adrep_report', 'lhs_table'=> 'adrep_report', 'lhs_key' => 'id',
              'rhs_module'=> 'adrep_parameter', 'rhs_table'=> 'adrep_parameter', 'rhs_key' => 'report_id',
              'relationship_type'=>'one-to-many'),

		'rel_adrep_report2adrep_column' =>
		array('lhs_module'=> 'adrep_report', 'lhs_table'=> 'adrep_report', 'lhs_key' => 'id',
              'rhs_module'=> 'adrep_column', 'rhs_table'=> 'adrep_column', 'rhs_key' => 'report_id',
              'relationship_type'=>'one-to-many'),

		'rel_adrep_report2adrep_role' =>
		array('lhs_module'=> 'adrep_report', 'lhs_table'=> 'adrep_report', 'lhs_key' => 'id',
              'rhs_module'=> 'adrep_role', 'rhs_table'=> 'adrep_role', 'rhs_key' => 'report_id',
              'relationship_type'=>'one-to-many'),

		'rel_adrep_report2adrep_menu_link' =>
		array('lhs_module'=> 'adrep_report', 'lhs_table'=> 'adrep_report', 'lhs_key' => 'id',
              'rhs_module'=> 'adrep_menu_link', 'rhs_table'=> 'adrep_menu_link', 'rhs_key' => 'report_id',
              'relationship_type'=>'one-to-many'),

		'rel_adrep_report2adrep_chart' =>
		array('lhs_module'=> 'adrep_report', 'lhs_table'=> 'adrep_report', 'lhs_key' => 'id',
              'rhs_module'=> 'adrep_chart', 'rhs_table'=> 'adrep_chart', 'rhs_key' => 'report_id',
              'relationship_type'=>'one-to-many'),

),
	'optimistic_locking'=>true,
		'unified_search'=>true,
	);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('adrep_report','adrep_report', array('basic','assignable','security_groups'));
