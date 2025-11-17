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

$listViewDefs['stic_Allocation_Proposals'] = array(
    'NAME' => array(
        'width' => '25%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'STIC_PAYMENT_COMMITMENTS_NAME' => array(
        'width' => '15%',
        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_ALLOCATION_PROPOSALS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_IDA',
        'module' => 'stic_Payment_Commitments',
        'default' => true,
    ),
    'ACTIVE' => array(
        'width' => '5%',
        'label' => 'LBL_ACTIVE',
        'default' => true,
    ),
    'OPPORTUNITIES_STIC_ALLOCATION_PROPOSALS_NAME' => array(
        'width' => '15%',
        'label' => 'LBL_OPPORTUNITIES_STIC_ALLOCATION_PROPOSALS_FROM_OPPORTUNITIES_TITLE',
        'id' => 'OPPORTUNITIES_STIC_ALLOCATION_PROPOSALSOPPORTUNITIES_IDA',
        'module' => 'Opportunities',
        'default' => true,
    ),
    'PROJECT_STIC_ALLOCATION_PROPOSALS_NAME' => array(
        'width' => '15%',
        'label' => 'LBL_PROJECT_STIC_ALLOCATION_PROPOSALS_FROM_PROJECT_TITLE',
        'id' => 'PROJECT_STIC_ALLOCATION_PROPOSALSPROJECT_IDA',
        'module' => 'Project',
        'default' => true,
    ),
    'STIC_LEDGER_ACCOUNTS_NAME' => array(
        'width' => '15%',
        'label' => 'LBL_STIC_LEDGER_ACCOUNTS',
        'id' => 'STIC_LEDGER_ACCOUNTS_IDA',
        'module' => 'stic_Ledger_Accounts',
        'default' => true,
    ),
    'TYPE' => array(
        'width' => '5%',
        'label' => 'LBL_TYPE',
        'default' => true,
    ),
    'PAYMENT_AMOUNT_FIELD' => array(
        'width' => '10%',
        'label' => 'LBL_PAYMENT_AMOUNT_FIELD',
        'default' => true,
    ),
    'PERCENTAGE' => array(
        'width' => '10%',
        'label' => 'LBL_PERCENTAGE',
        'default' => false,
    ),
    'HOURS' => array(
        'width' => '10%',
        'label' => 'LBL_HOURS',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false,
    ),
);