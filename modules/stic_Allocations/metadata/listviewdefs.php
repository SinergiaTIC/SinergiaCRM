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

$listViewDefs['stic_Allocations'] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'JUSTIFIED' => array(
        'width' => '8%',
        'label' => 'LBL_JUSTIFIED',
        'default' => true,
    ),
    'BLOCKED' => array(
        'width' => '8%',
        'label' => 'LBL_BLOCKED',
        'default' => true,
    ),
    'TYPE' => array(
        'width' => '10%',
        'label' => 'LBL_TYPE',
        'default' => true,
    ),
    'DATE' => array(
        'width' => '12%',
        'label' => 'LBL_DATE',
        'default' => true,
    ),
    'AMOUNT' => array(
        'width' => '12%',
        'label' => 'LBL_AMOUNT',
        'default' => true,
    ),
    'HOURS' => array(
        'width' => '10%',
        'label' => 'LBL_HOURS',
        'default' => true,
    ),
    'STIC_ALLOCATION_PROPOSALS_NAME' => array(
        'width' => '12%',
        'label' => 'LBL_STIC_ALLOCATION_PROPOSALS_STIC_ALLOCATIONS_FROM_STIC_ALLOCATIONS_TITLE',
        'default' => false,
    ),
    'STIC_PAYMENTS_NAME' => array(
        'width' => '12%',
        'label' => 'LBL_STIC_PAYMENTS_STIC_ALLOCATIONS_FROM_STIC_ALLOCATIONS_TITLE',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
);