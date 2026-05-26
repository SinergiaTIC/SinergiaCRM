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

$dictionary['stic_Whistleblowing'] = array(
    'table' => 'stic_whistleblowing',
    'audited' => true,
    'studio' => false,
    'reportable' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array (
    'stic_category' => 
    array (
      'required' => false,
      'name' => 'stic_category',
      'vname' => 'LBL_STIC_CATEGORY',
      'type' => 'enum',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'stic_whistleblowing_type_list',
      'studio' => 'false',
      'dependency' => false,
    ),
    'stic_start_date' => 
    array (
      'required' => false,
      'name' => 'stic_start_date',
      'vname' => 'LBL_STIC_START_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'size' => '20',
      'enable_range_search' => false,
    ),
    'stic_code' => 
    array (
      'required' => false,
      'name' => 'stic_code',
      'vname' => 'LBL_STIC_CODE',
      'type' => 'varchar',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => false,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
    ),
    'stic_status' => 
    array (
      'required' => false,
      'name' => 'stic_status',
      'vname' => 'LBL_STIC_STATUS',
      'type' => 'enum',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => true,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'stic_whistleblowing_status_list',
      'studio' => 'false',
      'dependency' => false,
    ),
    'stic_status_detail' => 
    array (
      'required' => false,
      'name' => 'stic_status_detail',
      'vname' => 'LBL_STIC_STATUS_DETAIL',
      'type' => 'varchar',
      'massupdate' => 0,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => true,
      'reportable' => false,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '255',
      'size' => '20',
    ),
  ),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => false,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Whistleblowing', 'stic_Whistleblowing', array('basic','assignable','security_groups'));

$dictionary['stic_Whistleblowing']['fields']['name']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['name']['massupdate'] = 0;
$dictionary['stic_Whistleblowing']['fields']['description']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['description']['massupdate'] = 0;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['inline_edit'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['audited'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['reportable'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['unified_search'] = false;
$dictionary['stic_Whistleblowing']['fields']['assigned_user_name']['massupdate'] = 0;