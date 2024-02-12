<?php
/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2018 Marnus van Niekerk, crm@mjvn.net
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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
