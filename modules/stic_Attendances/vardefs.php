<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

$dictionary['stic_Attendances'] = array(
    'table' => 'stic_attendances',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'start_date' => array(
            'required' => 0,
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'datetimecombo',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'dbType' => 'datetime',
            'enable_range_search' => 1,
            'options' => 'date_range_search_dom',
            'inline_edit' => 1,
        ),
        'duration' => array(
            'audited' => 0,
            'comments' => '',
            'enable_range_search' => 1,
            'help' => '',
            'importable' => 1,
            'precision' => '2',
            'len' => '10',
            'size' => '10',
            'massupdate' => 1,
            'name' => 'duration',
            'no_default' => 0,
            'options' => 'numeric_range_search_dom',
            'reportable' => 1,
            'required' => 0,
            'type' => 'decimal',
            'unified_search' => 0,
            'vname' => 'LBL_DURATION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'popupHelp' => 'LBL_DURATION_INFO',
        ),
        'status' => array(
            'required' => 0,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_attendances_status_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'payment_exception' => array(
            'required' => 0,
            'name' => 'payment_exception',
            'vname' => 'LBL_PAYMENT_EXCEPTION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_attendances_payment_exceptions_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'amount' => array(
            'required' => 0,
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => '1',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'inline_edit' => 1,
            'popupHelp' => 'LBL_AMOUNT_INFO',
        ),
        'stic_attendances_stic_sessions' => array(
            'name' => 'stic_attendances_stic_sessions',
            'type' => 'link',
            'relationship' => 'stic_attendances_stic_sessions',
            'source' => 'non-db',
            'module' => 'stic_Sessions',
            'bean_name' => 'stic_Sessions',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_SESSIONS_FROM_STIC_SESSIONS_TITLE',
            'id_name' => 'stic_attendances_stic_sessionsstic_sessions_ida',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'stic_attendances_stic_sessions_name' => array(
            'name' => 'stic_attendances_stic_sessions_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_SESSIONS_FROM_STIC_SESSIONS_TITLE',
            'save' => true,
            'id_name' => 'stic_attendances_stic_sessionsstic_sessions_ida',
            'link' => 'stic_attendances_stic_sessions',
            'table' => 'stic_sessions',
            'module' => 'stic_Sessions',
            'rname' => 'name',
            'massupdate' => 1,
            'inline_edit' => 1,
            'required' => 1,
            'importable' => 'required',
        ),
        'stic_attendances_stic_sessionsstic_sessions_ida' => array(
            'name' => 'stic_attendances_stic_sessionsstic_sessions_ida',
            'type' => 'link',
            'relationship' => 'stic_attendances_stic_sessions',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_SESSIONS_FROM_STIC_ATTENDANCES_TITLE',
        ),
        'stic_attendances_stic_registrations' => array(
            'name' => 'stic_attendances_stic_registrations',
            'type' => 'link',
            'relationship' => 'stic_attendances_stic_registrations',
            'source' => 'non-db',
            'module' => 'stic_Registrations',
            'bean_name' => 'stic_Registrations',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_REGISTRATIONS_FROM_STIC_REGISTRATIONS_TITLE',
            'id_name' => 'stic_attendances_stic_registrationsstic_registrations_ida',
        ),
        'stic_attendances_stic_registrations_name' => array(
            'name' => 'stic_attendances_stic_registrations_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_REGISTRATIONS_FROM_STIC_REGISTRATIONS_TITLE',
            'save' => true,
            'id_name' => 'stic_attendances_stic_registrationsstic_registrations_ida',
            'link' => 'stic_attendances_stic_registrations',
            'table' => 'stic_registrations',
            'module' => 'stic_Registrations',
            'rname' => 'name',
            'massupdate' => 1,
            'inline_edit' => 1,
            'required' => 1,
            'importable' => 'required',
        ),
        'stic_attendances_stic_registrationsstic_registrations_ida' => array(
            'name' => 'stic_attendances_stic_registrationsstic_registrations_ida',
            'type' => 'link',
            'relationship' => 'stic_attendances_stic_registrations',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_ATTENDANCES_STIC_REGISTRATIONS_FROM_STIC_ATTENDANCES_TITLE',
        ),
        'stic_payments_stic_attendances' => array(
            'name' => 'stic_payments_stic_attendances',
            'type' => 'link',
            'relationship' => 'stic_payments_stic_attendances',
            'source' => 'non-db',
            'module' => 'stic_Payments',
            'bean_name' => 'stic_Payments',
            'vname' => 'LBL_STIC_PAYMENTS_STIC_ATTENDANCES_FROM_STIC_PAYMENTS_TITLE',
            'id_name' => 'stic_payments_stic_attendancesstic_payments_ida',
        ),
        'stic_payments_stic_attendances_name' => array(
            'name' => 'stic_payments_stic_attendances_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENTS_STIC_ATTENDANCES_FROM_STIC_PAYMENTS_TITLE',
            'save' => true,
            'id_name' => 'stic_payments_stic_attendancesstic_payments_ida',
            'link' => 'stic_payments_stic_attendances',
            'table' => 'stic_payments',
            'module' => 'stic_Payments',
            'rname' => 'name',
            'studio' => array(
                'editview' => false,
                'quickcreate' => false,
            ),
        ),
        'stic_payments_stic_attendancesstic_payments_ida' => array(
            'name' => 'stic_payments_stic_attendancesstic_payments_ida',
            'type' => 'link',
            'relationship' => 'stic_payments_stic_attendances',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENTS_STIC_ATTENDANCES_FROM_STIC_ATTENDANCES_TITLE',
        ),

    ),
    'relationships' => array(
    ),
    'optimistic_locking' => 1,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Attendances', 'stic_Attendances', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Attendances']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Attendances']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Attendances']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
