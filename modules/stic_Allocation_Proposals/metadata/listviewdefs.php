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
    'PROPOSAL_STATUS' => array(
        'width' => '15%',
        'label' => 'LBL_PROPOSAL_STATUS',
        'default' => true,
    ),
    'PROPOSAL_TYPE' => array(
        'width' => '15%',
        'label' => 'LBL_PROPOSAL_TYPE',
        'default' => true,
    ),
    'PROPOSAL_DATE' => array(
        'width' => '15%',
        'label' => 'LBL_PROPOSAL_DATE',
        'default' => true,
    ),
    'AMOUNT' => array(
        'width' => '10%',
        'label' => 'LBL_AMOUNT',
        'default' => true,
    ),
    'PRIORITY' => array(
        'width' => '10%',
        'label' => 'LBL_PRIORITY',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
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