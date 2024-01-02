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
$dictionary['stic_Validation_Results'] = array(
    'table' => 'stic_validation_results',
    'audited' => true,
    'inline_edit' => 0,
    'duplicate_merge' => true,
    'fields' => array(
        'execution_date' => array(
            'required' => false,
            'name' => 'execution_date',
            'vname' => 'LBL_EXECUTION_DATE',
            'type' => 'datetimecombo',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => false,
            'dbType' => 'datetime',
            'options' => 'date_range_search_dom',
            'enable_range_search' => '1',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'log' => array(
            'required' => 0,
            'name' => 'log',
            'vname' => 'LBL_LOG',
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'disabled',
            'type' => 'html',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'rows' => 40,
            'cols' => 90,
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),

        'reviewed' => array(
            'required' => true,
            'name' => 'reviewed',
            'vname' => 'LBL_REVIEWED',
            'type' => 'enum',
            'massupdate' => 1,
            'default' => '0',
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
            'options' => 'stic_validation_results_reviewed_list',
            'popupHelp' => 'LBL_REVIEWED_HELP',
        ),

        'stic_validation_actions_id_c' => array(
            'required' => false,
            'name' => 'stic_validation_actions_id_c',
            'vname' => 'LBL_VALIDATION_ACTION_STIC_VALIDATION_ACTIONS_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 0,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
        ),
        'validation_action' => array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'validation_action',
            'vname' => 'LBL_VALIDATION_ACTION',
            'type' => 'relate',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
            'id_name' => 'stic_validation_actions_id_c',
            'ext2' => 'stic_Validation_Actions',
            'module' => 'stic_Validation_Actions',
            'rname' => 'name',
            'quicksearch' => 'enabled',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'schedulers_id_c' => array(
            'required' => false,
            'name' => 'schedulers_id_c',
            'vname' => 'LBL_SCHEDULER_SCHEDULER_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 0,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
        ),
        'scheduler' => array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'scheduler',
            'vname' => 'LBL_SCHEDULER',
            'type' => 'relate',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
            'id_name' => 'schedulers_id_c',
            'ext2' => 'Schedulers',
            'module' => 'Schedulers',
            'rname' => 'name',
            'quicksearch' => 'enabled',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'parent_name' => array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'parent_name',
            'vname' => 'LBL_FLEX_RELATE',
            'type' => 'parent',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => 25,
            'size' => '20',
            'options' => 'parent_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'parent_type' => 'record_type_display',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'parent_type' => array(
            'required' => false,
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'dbType' => 'varchar',
            'studio' => 'hidden',
        ),
        'parent_id' => array(
            'required' => false,
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Validation_Results', 'stic_Validation_Results', array('basic', 'assignable', 'security_groups'));

$dictionary['stic_Validation_Results']['fields']['name']['inline_edit'] = 0;
$dictionary['stic_Validation_Results']['fields']['name']['readonly'] = 1;
$dictionary['stic_Validation_Results']['fields']['description']['inline_edit'] = 1;
$dictionary['stic_Validation_Results']['fields']['description']['importable'] = true;
$dictionary['stic_Validation_Results']['fields']['description']['rows'] = '2';
