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

$listViewDefs['stic_Ledger_Accounts'] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'ACTIVE' => array(
        'width' => '8%',
        'label' => 'LBL_ACTIVE',
        'default' => true,
    ),
    'LEDGER_GROUP' => array(
        'width' => '12%',
        'label' => 'LBL_GROUP',
        'default' => true,
    ),
    'SUBGROUP' => array(
        'width' => '12%',
        'label' => 'LBL_SUBGROUP',
        'default' => true,
    ),
    'ACCOUNT' => array(
        'width' => '12%',
        'label' => 'LBL_ACCOUNT',
        'default' => true,
    ),
    'SUBACCOUNT' => array(
        'width' => '12%',
        'label' => 'LBL_SUBACCOUNT',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
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