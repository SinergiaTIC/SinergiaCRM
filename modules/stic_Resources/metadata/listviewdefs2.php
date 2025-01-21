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
$module_name = 'stic_Resources';
$placesListViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
        'related_fields' => array(),
        'table_key' => 'stic_resources',
        'db_key' => 'name'
    ),
    'CODE' => array(
        'type' => 'varchar',
        'label' => 'LBL_CODE',
        'width' => '10%',
        'default' => true,
    ),       
    'PLACE_TYPE' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PLACE_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'USER_TYPE' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_USER_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'GENDER' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_GENDER',
        'width' => '10%',
        'default' => true,
    ),
);