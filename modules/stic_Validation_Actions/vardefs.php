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

$dictionary['stic_Validation_Actions'] = array(
    'table' => 'stic_validation_actions',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'last_execution' => array(
            'required' => 0,
            'name' => 'last_execution',
            'vname' => 'LBL_LAST_EXECUTION',
            'type' => 'datetimecombo',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'dbType' => 'datetime',
            'inline_edit' => true,
            'options' => 'date_range_search_dom',
            'enable_range_search' => '1',
        ),
        'function' => array(
            'required' => 1,
            'name' => 'function',
            'vname' => 'LBL_FUNCTION',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_validation_actions_list',
            'studio' => 'visible',
            'dependency' => 0,
        ),
        'report_always' => array(
            'required' => 0,
            'name' => 'report_always',
            'vname' => 'LBL_REPORT_ALWAYS',
            'type' => 'bool',
            'massupdate' => 1,
            'default' => '0',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
        ),
        'priority' => array(
            'required' => 1,
            'name' => 'priority',
            'vname' => 'LBL_PRIORITY',
            'type' => 'int',
            'massupdate' => 0,
            'default' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'options' => 'numeric_range_search_dom',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'enable_range_search' => 1,
            'disable_num_format' => '',
            'min' => 0,
            'max' => 500,
            'validation' => array(
                'type' => 'range',
                'min' => 0,
                'max' => 500,
            ),
        ),
        'stic_validation_actions_schedulers' => array(
            'name' => 'stic_validation_actions_schedulers',
            'type' => 'link',
            'relationship' => 'stic_validation_actions_schedulers',
            'source' => 'non-db',
            'module' => 'Schedulers',
            'bean_name' => 'Scheduler',
            'vname' => 'LBL_STIC_VALIDATION_ACTIONS_SCHEDULERS_FROM_SCHEDULERS_TITLE',
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => 1,
    'unified_search' => 1,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Validation_Actions', 'stic_Validation_Actions', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Validation_Actions']['fields']['description']['rows'] = '2'; // Make textarea fields shorter