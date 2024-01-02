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

$dictionary['stic_Sessions'] = array(
    'table' => 'stic_sessions',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(

        'contact_id_c' => array(
            'required' => 0,
            'name' => 'contact_id_c',
            'vname' => '',
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'disabled',
            'type' => 'id',
            'massupdate' => 0,
            'inline_edit' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 0,
            'unified_search' => 0,
            'len' => 36,
            'size' => '20',
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
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'enable_range_search' => 1,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
            'display_default' => 'now&09:00am',
            'inline_edit' => 1,
            'validation' => array('type' => 'isbefore', 'compareto' => 'end_date', 'blank' => 0),
        ),
        'end_date' => array(
            'required' => 1,
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
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'enable_range_search' => 1,
            'options' => 'date_range_search_dom',
            'dbType' => 'datetime',
            'display_default' => 'now&10:00am',
            'inline_edit' => 1,
            'validation' => array('type' => 'isafter', 'compareto' => 'start_date', 'blank' => 0),
        ),
        'responsible' => array(
            'required' => 0,
            'source' => 'non-db',
            'name' => 'responsible',
            'vname' => 'LBL_STIC_RESPONSIBLE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'relate',
            'massupdate' => 1,
            'inline_edit' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'id_name' => 'contact_id_c',
            'module' => 'Contacts',
            'rname' => 'name',
            'ext2'=>'Contacts',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
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
        'total_attendances' => array(
            'type' => 'int',
            'required' => 0,
            'name' => 'total_attendances',
            'vname' => 'LBL_TOTAL_ATTENDANCES',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'massupdate' => 0, // autocalc
            'no_default' => 0,
            'default' => 0,
            'comments' => '',
            'help' => '',
            'dbType' => 'int',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '10',
            'enable_range_search' => 1,
            'options' => 'numeric_range_search_dom',
            'inline_edit' => 0,
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'validated_attendances' => array(
            'type' => 'int',
            'size' => '10',
            'audited' => 0,
            'comments' => '',
            'default' => 0,
            'dbType' => 'int',
            'help' => '',
            'importable' => 1,
            'massupdate' => 0, // autocalc
            'name' => 'validated_attendances',
            'no_default' => 0,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'reportable' => 1,
            'required' => 0,
            'unified_search' => 0,
            'vname' => 'LBL_VALIDATED_ATTENDANCES',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'inline_edit' => 0,
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'weekday' => array(
            'required' => 0,
            'name' => 'weekday',
            'vname' => 'LBL_WEEKDAY',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 0, // autocalc
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'options' => 'stic_weekdays_list',
            'studio' => 'visible',
            'enable_range_search' => 1,
            'dependency' => 0,
            'inline_edit' => 0,
            'len' => 100,
            'size' => '20',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'activity_type' => array(
            'required' => 0,
            'name' => 'activity_type',
            'vname' => 'LBL_ACTIVITY_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'multienum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'stic_sessions_activity_types_list',
            'studio' => 'visible',
            'isMultiSelect' => 1,
        ),
        'color' => array(
            'required' => false,
            'name' => 'color',
            'vname' => 'LBL_COLOR',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'options' => 'stic_colors_list',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '2',
            'inline_edit' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '50',
            'size' => '20',
            'popupHelp' => 'LBL_COLOR_INFO',
        ),
        'stic_sessions_documents' => array(
            'name' => 'stic_sessions_documents',
            'type' => 'link',
            'relationship' => 'stic_sessions_documents',
            'source' => 'non-db',
            'module' => 'Documents',
            'bean_name' => 'Document',
            'side' => 'right',
            'vname' => 'LBL_STIC_SESSIONS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
        ),
        'stic_sessions_stic_events' => array(
            'name' => 'stic_sessions_stic_events',
            'type' => 'link',
            'relationship' => 'stic_sessions_stic_events',
            'source' => 'non-db',
            'module' => 'stic_Events',
            'bean_name' => 'stic_Events',
            'vname' => 'LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_EVENTS_TITLE',
            'id_name' => 'stic_sessions_stic_eventsstic_events_ida',
        ),
        'stic_sessions_stic_events_name' => array(
            'name' => 'stic_sessions_stic_events_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_EVENTS_TITLE',
            'save' => 1,
            'id_name' => 'stic_sessions_stic_eventsstic_events_ida',
            'link' => 'stic_sessions_stic_events',
            'table' => 'stic_events',
            'module' => 'stic_Events',
            'rname' => 'name',
            'massupdate' => 1,
            'inline_edit' => 1,
            'required' => 1,
            'importable' => 'required',
        ),
        'stic_sessions_stic_eventsstic_events_ida' => array(
            'name' => 'stic_sessions_stic_eventsstic_events_ida',
            'type' => 'link',
            'relationship' => 'stic_sessions_stic_events',
            'source' => 'non-db',
            'reportable' => 0,
            'side' => 'right',
            'vname' => 'LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_SESSIONS_TITLE',
        ),
        'stic_attendances_stic_sessions' => array(
            'name' => 'stic_attendances_stic_sessions',
            'type' => 'link',
            'relationship' => 'stic_attendances_stic_sessions',
            'source' => 'non-db',
            'module' => 'stic_Attendances',
            'bean_name' => 'stic_Attendances',
            'side' => 'right',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_SESSIONS_FROM_STIC_ATTENDANCES_TITLE',
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
VardefManager::createVardef('stic_Sessions', 'stic_Sessions', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Sessions']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Sessions']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Sessions']['fields']['name']['popupHelp'] = 'LBL_NAME_INFO'; // Information about name changing effects
$dictionary['stic_Sessions']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
