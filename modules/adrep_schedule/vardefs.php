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

$dictionary['adrep_schedule'] = array(
    'table' => 'adrep_schedule',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'query' =>
  array (
    'required' => true,
    'name' => 'query',
    'vname' => 'LBL_QUERY',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Query that will return the email addresses and parameters for the report',
    'help' => 'Query that will return the email addresses and parameters for the report',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'studio' => 'visible',
    'rows' => '4',
    'cols' => '20',
  ),
  'email_column' =>
  array (
    'required' => true,
    'name' => 'email_column',
    'vname' => 'LBL_EMAIL_COLUMN',
    'type' => 'varchar',
    'massupdate' => 0,
    'default' => 'email_address',
    'no_default' => false,
    'comments' => 'Column from query that contain the email address(es)',
    'help' => 'Column from query that contain the email address(es)',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '50',
    'size' => '20',
  ),
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
    'comments' => 'Report to run',
    'help' => 'Report to run',
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
    'id_name' => 'report_id',
    'ext2' => 'adrep_report',
    'module' => 'adrep_report',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'format' =>
  array (
    'required' => true,
    'name' => 'format',
    'vname' => 'LBL_FORMAT',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => 'html',
    'no_default' => false,
    'comments' => 'Format to attach',
    'help' => 'Format to attach',
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
    'options' => 'adrep_email_format_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'dow' =>
  array (
    'required' => true,
    'name' => 'dow',
    'vname' => 'LBL_DOW',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => 'Day of week for report',
    'help' => 'Day of week for report',
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
    'options' => 'adrep_dow_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
	'dom' =>
    array (
      'inline_edit' => '1',
      'labelValue' => 'Day of Month',
      'required' => false,
      'name' => 'dom',
      'vname' => 'LBL_TEST',
      'type' => 'multienum',
      'massupdate' => '0',
      'default' => '[All]',
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'size' => '20',
      'options' => 'adrep_dom_list',
      'studio' => 'visible',
      'isMultiSelect' => true,
      'id' => 'adrep_scheduletest_c',
      'custom_module' => 'adrep_schedule',
    ),
  'tod' =>
  array (
    'required' => true,
    'name' => 'tod',
    'vname' => 'LBL_TOD',
    'type' => 'varchar',
    'massupdate' => 0,
    'default' => '00:00',
    'no_default' => false,
    'comments' => 'Time of day for report',
    'help' => 'Time of day for report',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '5',
    'size' => '20',
  ),
  'active_flag' =>
  array (
    'required' => false,
    'name' => 'active_flag',
    'vname' => 'LBL_ACTIVE_FLAG',
    'type' => 'bool',
    'massupdate' => 0,
    'default' => '1',
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
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('adrep_schedule', 'adrep_schedule', array('basic','assignable','security_groups'));
