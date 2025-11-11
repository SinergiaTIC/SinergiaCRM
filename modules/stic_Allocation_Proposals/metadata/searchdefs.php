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
$module_name = 'stic_Allocation_Proposals';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'active' => array(
                'type' => 'bool',
                'label' => 'LBL_ACTIVE',
                'width' => '10%',
                'default' => true,
                'name' => 'active',
            ),
            'type' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'type',
            ),
            'payment_amount_field' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_PAYMENT_AMOUNT_FIELD',
                'width' => '10%',
                'default' => true,
                'name' => 'payment_amount_field',
            ),
            'percentage' => array(
                'type' => 'decimal',
                'label' => 'LBL_PERCENTAGE',
                'width' => '10%',
                'default' => true,
                'name' => 'percentage',
            ),
            'stic_payment_commitments_name' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_ALLOCATION_PROPOSALS_FROM_STIC_ALLOCATION_PROPOSALS_TITLE',
                'width' => '10%',
                'default' => true,
                'id' => 'STIC_PAYME4A62ITMENTS_IDA',
                'name' => 'stic_payment_commitments_name',
            ),
            'stic_ledger_accounts_name' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_STIC_LEDGER_ACCOUNTS',
                'width' => '10%',
                'default' => true,
                'id' => 'STIC_LEDGER_ACCOUNTS_IDA',
                'name' => 'stic_ledger_accounts_name',
            ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'active' => array(
                'type' => 'bool',
                'label' => 'LBL_ACTIVE',
                'width' => '10%',
                'default' => true,
                'name' => 'active',
            ),
            'type' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'type',
            ),
            'payment_amount_field' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_PAYMENT_AMOUNT_FIELD',
                'width' => '10%',
                'default' => true,
                'name' => 'payment_amount_field',
            ),
            'percentage' => array(
                'type' => 'decimal',
                'label' => 'LBL_PERCENTAGE',
                'width' => '10%',
                'default' => true,
                'name' => 'percentage',
            ),
            'hours' => array(
                'type' => 'decimal',
                'label' => 'LBL_HOURS',
                'width' => '10%',
                'default' => true,
                'name' => 'hours',
            ),
            'stic_payment_commitments_name' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_ALLOCATION_PROPOSALS_FROM_STIC_ALLOCATION_PROPOSALS_TITLE',
                'width' => '10%',
                'default' => true,
                'id' => 'STIC_PAYME4A62ITMENTS_IDA',
                'name' => 'stic_payment_commitments_name',
            ),
            'stic_ledger_accounts_name' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_STIC_LEDGER_ACCOUNTS',
                'width' => '10%',
                'default' => true,
                'id' => 'STIC_LEDGER_ACCOUNTS_IDA',
                'name' => 'stic_ledger_accounts_name',
            ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'LBL_DESCRIPTION',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'description',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'modified_user_id' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'created_by' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);