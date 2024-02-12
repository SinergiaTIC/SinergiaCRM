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

$dictionary['stic_Time_Availability'] = array(
    'table' => 'stic_time_availability',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
        'type' => array(
            'required' => 1,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_time_availability_types_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
            'default' => '',
        ),        
        'start_date' => array(
            'required' => 1,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'datetimecombo',
            'massupdate' => 0, // dangerous
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'enable_range_search' => 1,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
            'display_default' => 'now',
            'inline_edit' => 1,
            'validation' => array('type' => 'isbefore', 'compareto' => 'end_date', 'blank' => 0),
        ),
        'end_date' => array(
            'required' => 0,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'datetimecombo',
            'massupdate' => 0, // dangerous
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'enable_range_search' => 1,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
            'display_default' => '',
            'inline_edit' => 1,
            'validation' => array('type' => 'isafter', 'compareto' => 'start_date', 'blank' => 0),
        ),
        'duration' => array(
            'required' => 0,
            'name' => 'duration',
            'vname' => 'LBL_DURATION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 0, // autocalc
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'len' => '10',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'dbType' => 'decimal(10,2)',
            'size' => '10',
            'precision' => '2',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'inline_edit' => 0,
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),        
        'stic_time_availability_users' => array (
            'name' => 'stic_time_availability_users',
            'type' => 'link',
            'relationship' => 'stic_time_availability_users',
            'source' => 'non-db',
            'module' => 'Users',
            'bean_name' => 'User',
            'vname' => 'LBL_STIC_TIME_AVAILABILITY_USERS_FROM_USERS_TITLE',
            'id_name' => 'stic_time_availability_usersusers_ida',
        ),
        'stic_time_availability_users_name' => array (
            'name' => 'stic_time_availability_users_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_TIME_AVAILABILITY_USERS_FROM_USERS_TITLE',
            'save' => true,
            'id_name' => 'stic_time_availability_usersusers_ida',
            'link' => 'stic_time_availability_users',
            'table' => 'users',
            'module' => 'Users',
            'rname' => 'name',
        ),
        'stic_time_availability_usersusers_ida' => array (
            'name' => 'stic_time_availability_usersusers_ida',
            'type' => 'link',
            'relationship' => 'stic_time_availability_users',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_TIME_AVAILABILITY_USERS_FROM_STIC_TIME_AVAILABILITY_TITLE',
        ),
    ),
    'relationships' => array (
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => false,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Time_Availability', 'stic_Time_Availability', array('basic','assignable','security_groups'));


// Set special values for SuiteCRM base fields
$dictionary['stic_Time_Availability']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Time_Availability']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Time_Availability']['fields']['description']['rows'] = '2'; // Make textarea fields shorter