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
$popupMeta = array (

    'moduleMain' => 'stic_Organizational_Environment',
    'varName' => 'stic_Organizational_Environment',
    'orderBy' => 'stic_Organizational_Environment.name',

    'whereClauses' => array (
        'name' => 'stic_Organizational_Environment.name',
        'network_type_c' => 'stic_Organizational_Environment.network_type_c',
        'relationship_type' => 'stic_Organizational_Environment.relationship_type',
        'base_organization' => 'stic_Organizational_Environment.base_organization',
        'network_organization' => 'stic_Organizational_Environment.network_organization',
        'network_person' => 'stic_Organizational_Environment.network_person',
        'active' => 'stic_Organizational_Environment.active',
        'start_date' => 'stic_Organizational_Environment.start_date',
        'end_date' => 'stic_Organizational_Environment.end_date',
    ),


    'searchInputs' => array (
        'name', 
        'network_type_c', 
        'relationship_type', 
        'base_organization',
        'network_organization', 
        'network_person', 
        'active', 
        'start_date', 
        'end_date',
    ),


    'listviewdefs' => array (
        'NAME' => array(
            'width' => '30%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
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
    ),


    'searchdefs' => array (
        'name' => array (
            'name' => 'name',
            'width' => '10%',
        ),
        'network_type_c' => array (
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_NETWORK_TYPE',
            'width' => '10%',
            'name' => 'network_type_c',
        ),
        'relationship_type' => array (
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_RELATIONSHIP_TYPE',
            'width' => '10%',
            'name' => 'relationship_type',
        ),
        'base_organization' => array (
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_BASE_ORGANIZATION',
            'id' => 'BASE_ORGANIZATION_ID_C',
            'width' => '10%',
            'name' => 'base_organization',
        ),
        'network_organization' => array (
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_NETWORK_ORGANIZATION',
            'id' => 'NETWORK_ORGANIZATION_ID_C',
            'width' => '10%',
            'name' => 'network_organization',
        ),
        'network_person' => array (
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_NETWORK_PERSON',
            'id' => 'NETWORK_PERSON_ID_C',
            'width' => '10%',
            'name' => 'network_person',
        ),
        'active' => array (
            'type' => 'bool',
            'label' => 'LBL_ACTIVE',
            'width' => '10%',
            'name' => 'active',
        ),
        'start_date' => array (
            'type' => 'date',
            'label' => 'LBL_START_DATE',
            'width' => '10%',
            'name' => 'start_date',
        ),
        'end_date' => array (
            'type' => 'date',
            'label' => 'LBL_END_DATE',
            'width' => '10%',
            'name' => 'end_date',
        ),
    ),
);