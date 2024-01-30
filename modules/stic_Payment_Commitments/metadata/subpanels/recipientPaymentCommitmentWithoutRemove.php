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
// $module_name = 'stic_Payment_Commitments';
$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '25%',
        'default' => true,
    ),
    'payment_type' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_PAYMENT_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'payment_method' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_PAYMENT_METHOD',
        'width' => '10%',
        'default' => true,
    ),
    'amount' => array(
        'type' => 'decimal',
        'align' => 'right',
        'vname' => 'LBL_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'first_payment_date' => array(
        'type' => 'date',
        'vname' => 'LBL_FIRST_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'end_date' => array(
        'type' => 'date',
        'vname' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'active' => array(
        'type' => 'boolean',
        'vname' => 'LBL_ACTIVE',
        'width' => '10%',
        'default' => true,
    ),
    'periodicity' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_PERIODICITY',
        'width' => '10%',
        'default' => true,
    ),
    'stic_payment_commitments_contacts_1_name' => array(
        'type' => 'relate',
        'link' => true,
        'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_1_FROM_CONTACTS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_CONTACTS_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Contacts',
        'target_record_key' => 'stic_payment_commitments_contacts_1contacts_ida',
    ),
    'stic_payment_commitments_accounts_1_name' => array(
        'type' => 'relate',
        'link' => true,
        'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1ACCOUNTS_IDA',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Accounts',
        'target_record_key' => 'stic_payment_commitments_accounts_1accounts_ida',
    ),
    'assigned_user_name' => array(
        'link' => true,
        'type' => 'relate',
        'vname' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Users',
        'target_record_key' => 'assigned_user_id',
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelQuickEditButton',
        'module' => 'stic_Payment_Commitments',
        'width' => '4%',
        'default' => true,
    ),
    'remove_button' => array(
        // 'vname' => 'LBL_REMOVE',
        // 'widget_class' => 'SubPanelRemoveButton',
        // 'module' => 'stic_Payment_Commitments',
        // 'width' => '5%',
        // 'default' => true,
    ),
);
