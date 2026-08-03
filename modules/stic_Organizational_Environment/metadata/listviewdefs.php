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

$module_name = 'stic_Organizational_Environment';
$listViewDefs[$module_name] =
array(
    'NAME' => array(
        'width' => '30%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'RELATIONSHIP_TYPE' => array(
        'type' => 'dynamicenum',
        'parentenum' => 'network_type_c',
        'studio' => 'visible',
        'label' => 'LBL_RELATIONSHIP_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'BASE_ORGANIZATION' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_BASE_ORGANIZATION',
        'id' => 'BASE_ORGANIZATION_ID_C',
        'width' => '10%',
        'default' => true,
    ),
    'NETWORK_ORGANIZATION' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_NETWORK_ORGANIZATION',
        'id' => 'NETWORK_ORGANIZATION_ID_C',
        'width' => '10%',
        'default' => true,
    ),
    'NETWORK_PERSON' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_NETWORK_PERSON',
        'id' => 'NETWORK_PERSON_ID_C',
        'width' => '10%',
        'default' => true,
    ),
    'START_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'END_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'REFERENCE_ORGANIZATION' => array(
        'type' => 'bool',
        'studio' => 'visible',
        'label' => 'LBL_REFERENCE_ORGANIZATION',
        'width' => '10%',
        'default' => true,
    ),
    'ACTIVE' => array(
        'type' => 'bool',
        'studio' => 'visible',
        'label' => 'LBL_ACTIVE',
        'width' => '10%',
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'DESCRIPTION' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);