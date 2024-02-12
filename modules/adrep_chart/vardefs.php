<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2017 SalesAgility Ltd.
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
 */

$dictionary['adrep_chart'] = array(
    'table' => 'adrep_chart',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'report_id' =>
  array (
    'required' => false,
    'name' => 'report_id',
    'vname' => 'LBL_REPORT_NAME_ADREP_REPORT_ID',
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
  'report_name' =>
  array (
    'required' => true,
    'source' => 'non-db',
    'name' => 'report_name',
    'vname' => 'LBL_REPORT_NAME',
    'type' => 'relate',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Report this parameter is for.',
    'help' => 'Report this parameter is for.',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'id_name' => 'report_id',
    'ext2' => 'adrep_report',
    'module' => 'adrep_report',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'x_label' =>
  array (
    'required' => false,
    'name' => 'x_label',
    'vname' => 'LBL_X_LABEL',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'y_label' =>
  array (
    'required' => false,
    'name' => 'y_label',
    'vname' => 'LBL_Y_LABEL',
    'type' => 'varchar',
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
  ),
	'width' =>
  array (
		'required' => false,
		'name' => 'width',
		'vname' => 'LBL_WIDTH_FIELD',
		'type' => 'int',
		'massupdate' => 0,
		'default' => '0',
		'no_default' => false,
		'comments' => 'Width of the chart',
		'help' => 'Width of the chart',
		'importable' => 'true',
		'duplicate_merge' => 'disabled',
		'duplicate_merge_dom_value' => '0',
		'audited' => true,
		'inline_edit' => '',
		'reportable' => true,
		'unified_search' => false,
		'merge_filter' => 'disabled',
		'len' => '5',
		'size' => '20',
		'enable_range_search' => false,
		'disable_num_format' => '1',
  ),
	'height' =>
  array (
		'required' => false,
    'name' => 'height',
    'vname' => 'LBL_HEIGHT_FIELD',
    'type' => 'int',
    'massupdate' => 0,
    'default' => '0',
    'no_default' => false,
    'comments' => 'Height of the chart',
    'help' => 'Height of the chart',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '5',
    'size' => '20',
    'enable_range_search' => false,
    'disable_num_format' => '1',

  ),
  'chart_type' =>
  array (
    'required' => false,
    'name' => 'chart_type',
    'vname' => 'LBL_CHART_TYPE',
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
    'options' => 'adrep_chart_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'group1_field' =>
  array (
    'required' => true,
    'name' => 'group1_field',
    'vname' => 'LBL_GROUP1_FIELD',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'adrep_column_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'group2_field' =>
  array (
    'required' => false,
    'name' => 'group2_field',
    'vname' => 'LBL_GROUP2_FIELD',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'adrep_column_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
    'value_field' =>
    array (
      'required' => true,
      'name' => 'value_field',
      'vname' => 'LBL_VALUE_FIELD',
      'type' => 'enum',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => true,
      'inline_edit' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'adrep_column_list',
      'studio' => 'visible',
      'dependency' => false,
    ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('adrep_chart', 'adrep_chart', array('basic','assignable','security_groups'));
