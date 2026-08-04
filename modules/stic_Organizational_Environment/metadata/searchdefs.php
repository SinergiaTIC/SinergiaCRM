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
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'network_type_c' => array(
                'type' => 'enum',
                'label' => 'LBL_NETWORK_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'network_type_c',
            ),
            'relationship_type' => array(
                'type' => 'enum',
                'label' => 'LBL_RELATIONSHIP_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'relationship_type',
            ),
            'network_organization' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_NETWORK_ORGANIZATION',
                'id' => 'NETWORK_ORGANIZATION_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'network_organization',
            ),
            'network_person' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_NETWORK_PERSON',
                'id' => 'NETWORK_PERSON_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'network_person',
            ),
            'base_organization' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_BASE_ORGANIZATION',
                'id' => 'BASE_ORGANIZATION_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'base_organization',
            ),
            'active' => array(
                'type' => 'bool',
                'label' => 'LBL_ACTIVE',
                'width' => '10%',
                'default' => true,
                'name' => 'active',
            ),
            'start_date' => array(
                'type' => 'date',
                'label' => 'LBL_START_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'start_date',
            ),
            'end_date' => array(
                'type' => 'date',
                'label' => 'LBL_END_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'end_date',
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
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'network_type_c' => array(
                'type' => 'enum',
                'label' => 'LBL_NETWORK_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'network_type_c',
            ),
            'relationship_type' => array(
                'type' => 'enum',
                'label' => 'LBL_RELATIONSHIP_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'relationship_type',
            ),
            'base_organization' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_BASE_ORGANIZATION',
                'id' => 'BASE_ORGANIZATION_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'base_organization',
            ),
            'reference_organization' => array(
                'type' => 'bool',
                'label' => 'LBL_REFERENCE_ORGANIZATION',
                'width' => '10%',
                'default' => true,
                'name' => 'reference_organization',
            ),
            'network_organization' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_NETWORK_ORGANIZATION',
                'id' => 'NETWORK_ORGANIZATION_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'network_organization',
            ),
            'network_person' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_NETWORK_PERSON',
                'id' => 'NETWORK_PERSON_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'network_person',
            ),
            'active' => array(
                'type' => 'bool',
                'label' => 'LBL_ACTIVE',
                'width' => '10%',
                'default' => true,
                'name' => 'active',
            ),
            'start_date' => array(
                'type' => 'date',
                'label' => 'LBL_START_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'start_date',
            ),
            'end_date' => array(
                'type' => 'date',
                'label' => 'LBL_END_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'end_date',
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
                        'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
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

?>