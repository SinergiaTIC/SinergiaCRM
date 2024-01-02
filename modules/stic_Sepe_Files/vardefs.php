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

$dictionary['stic_Sepe_Files'] = array(
    'table' => 'stic_sepe_files',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'status' =>
        array(
            'required' => true,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => '1',
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'enabled', 
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_sepe_file_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'type' =>
        array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'enabled', 
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_sepe_file_types_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'reported_month' =>
        array(
            'required' => true,
            'name' => 'reported_month',
            'vname' => 'LBL_REPORTED_MONTH',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'enabled', 
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => '1',
            'options' => 'date_range_search_dom',
            'popupHelp' => 'LBL_REPORTED_MONTH_HELP',
        ),
        'log' =>
        array(
            'required' => false,
            'name' => 'log',
            'vname' => 'LBL_LOG',
            'type' => 'html',
            'massupdate' => 0,
            'inline_edit' => 0,
            'rows' => 40,
            'cols' => 90,
            'studio' => array(
                'no_duplicate' => true,
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'agreement' =>
        array(
            'required' => false,
            'name' => 'agreement',
            'vname' => 'LBL_AGREEMENT',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'enabled', 
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_sepe_agreement_list',
            'studio' => 'visible',
            'dependency' => false,
            'popupHelp' => 'LBL_AGREEMENT_HELP',
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Sepe_Files', 'stic_Sepe_Files', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Sepe_Files']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Sepe_Files']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Sepe_Files']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
