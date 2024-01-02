<?php

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

$dictionary['stic_Incorpora_Locations'] = array(
    'table' => 'stic_incorpora_locations',
    'audited' => true,
    'inline_edit' => 0,
    'duplicate_merge' => true,
    'fields' => array(
        'state' => array(
            'required' => true,
            'name' => 'state',
            'vname' => 'LBL_STATE',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
        ),
        'state_code' => array(
            'required' => true,
            'name' => 'state_code',
            'vname' => 'LBL_STATE_CODE',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'municipality' => array(
            'required' => true,
            'name' => 'municipality',
            'vname' => 'LBL_MUNICIPALITY',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
        ),
        'municipality_code' => array(
            'required' => true,
            'name' => 'municipality_code',
            'vname' => 'LBL_MUNICIPALITY_CODE',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'town' => array(
            'required' => true,
            'name' => 'town',
            'vname' => 'LBL_TOWN',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
        ),
        'town_code' => array(
            'required' => true,
            'name' => 'town_code',
            'vname' => 'LBL_TOWN_CODE',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => 0,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Incorpora_Locations', 'stic_Incorpora_Locations', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Incorpora_Locations']['fields']['name']['inline_edit'] = '0'; // Name can't be edited inline in this module
$dictionary['stic_Incorpora_Locations']['fields']['assigned_user_name']['inline_edit'] = '0'; // Name can't be edited inline in this module
$dictionary['stic_Incorpora_Locations']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
