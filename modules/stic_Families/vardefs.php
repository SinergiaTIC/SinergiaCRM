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

$dictionary['stic_Families'] = array(
    'table' => 'stic_families',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'code' => array(
            'required' => false,
            'name' => 'code',
            'vname' => 'LBL_CODE',
            'type' => 'int',
            'massupdate' => 0,
            'readonly' => 1,
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'len' => '11',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'importable' => false,
            'comments' => '',
            'help' => '',
            'no_default' => false,
            'merge_filter' => 'enabled',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => true,
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'type' => array(
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'stic_families_types_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'income' => array(
            'required' => false,
            'name' => 'income',
            'vname' => 'LBL_INCOME',
            'type' => 'decimal',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '26',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => '2',
        ),
        'members_amount' => array(
            'required' => false,
            'name' => 'members_amount',
            'vname' => 'LBL_MEMBERS_AMOUNT',
            'type' => 'int',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => true,
            'min' => 0,
            'max' => false,
            'validation' => array(
                'type' => 'range',
                'min' => 0,
                'max' => false,
            ),
        ),
        'start_date' => array(
            'required' => true,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'display_default' => 'today',
        ),
        'end_date' => array(
            'required' => false,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'active' => array(
            'required' => false,
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'massupdate' => 0,
            'default' => '0',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'popupHelp' => 'LBL_ACTIVE_HELP',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
        ),
        'stic_families_stic_personal_environment' => array(
            'name' => 'stic_families_stic_personal_environment',
            'type' => 'link',
            'relationship' => 'stic_families_stic_personal_environment',
            'source' => 'non-db',
            'module' => 'stic_Personal_Environment',
            'bean_name' => 'stic_Personal_Environment',
            'side' => 'right',
            'vname' => 'LBL_STIC_FAMILIES_STIC_PERSONAL_ENVIRONMENT_FROM_STIC_PERSONAL_ENVIRONMENT_TITLE',
        ),
        'stic_families_stic_followups' => array(
            'name' => 'stic_families_stic_followups',
            'type' => 'link',
            'relationship' => 'stic_families_stic_followups',
            'source' => 'non-db',
            'module' => 'stic_FollowUps',
            'bean_name' => 'stic_FollowUps',
            'side' => 'right',
            'vname' => 'LBL_STIC_FAMILIES_STIC_FOLLOWUPS_FROM_STIC_FOLLOWUPS_TITLE',
        ),
        'stic_families_documents' => array(
            'name' => 'stic_families_documents',
            'type' => 'link',
            'relationship' => 'stic_families_documents',
            'source' => 'non-db',
            'module' => 'Documents',
            'bean_name' => 'Document',
            'side' => 'right',
            'vname' => 'LBL_STIC_FAMILIES_DOCUMENTS_FROM_DOCUMENTS_TITLE',
        ),
        'stic_families_stic_assessments' => array(
            'name' => 'stic_families_stic_assessments',
            'type' => 'link',
            'relationship' => 'stic_families_stic_assessments',
            'source' => 'non-db',
            'module' => 'stic_Assessments',
            'bean_name' => 'stic_Assessments',
            'side' => 'right',
            'vname' => 'LBL_STIC_FAMILIES_STIC_ASSESSMENTS_FROM_STIC_ASSESSMENTS_TITLE',
        ),
        'stic_families_stic_goals' => array(
            'name' => 'stic_families_stic_goals',
            'type' => 'link',
            'relationship' => 'stic_families_stic_goals',
            'source' => 'non-db',
            'module' => 'stic_Goals',
            'bean_name' => 'stic_Goals',
            'side' => 'right',
            'vname' => 'LBL_STIC_FAMILIES_STIC_GOALS_FROM_STIC_GOALS_TITLE',
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Families', 'stic_Families', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Families']['fields']['description']['rows'] = '2'; // Make textarea fields shorter