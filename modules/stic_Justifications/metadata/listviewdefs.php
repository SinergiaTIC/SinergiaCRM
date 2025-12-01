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

$listViewDefs['stic_Justifications'] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'STATUS' => array(
        'width' => '10%',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'stic_opportunities_stic_justifications_name' => array(
        'width' => '20%',
        'label' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATIONS_FROM_OPPORTUNITIES_TITLE',
        'id' => 'STIC_OPPORTUNITIES_STIC_JUSTIFICATIONSOCCURRENCES_IDA',
        'module' => 'Opportunities',
        'default' => true,
    ),
    'STIC_LEDGER_ACCOUNTS_NAME' => array(
        'width' => '20%',
        'label' => 'LBL_STIC_LEDGER_ACCOUNTS',
        'id' => 'STIC_LEDGER_ACCOUNTS_IDA',
        'module' => 'stic_Ledger_Accounts',
        'default' => true,
    ),
    'ALLOCATION_TYPE' => array(
        'width' => '10%',
        'label' => 'LBL_ALLOCATION_TYPE',
        'default' => true,
    ),
    'AMOUNT' => array(
        'width' => '10%',
        'label' => 'LBL_AMOUNT',
        'default' => true,
    ),
    'JUSTIFIED_AMOUNT' => array(
        'width' => '10%',
        'label' => 'LBL_JUSTIFIED_AMOUNT',
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
    ),
);