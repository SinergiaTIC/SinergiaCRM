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

$popupMeta = array(
    'moduleMain' => 'stic_Assets',
    'varName' => 'stic_Assets',
    'orderBy' => 'stic_assets.name',
    'whereClauses' => array(
        'name' => 'stic_assets.name',
        'stic_assets_contacts_name' => 'stic_assets.stic_assets_contacts_name',
        'code' => 'stic_assets.code',
        'type' => 'stic_assets.type',
        'start_date' => 'stic_assets.start_date',
        'address_city' => 'stic_assets.address_city',
        'address_street' => 'stic_assets.address_street',
    ),
    'searchInputs' => array(
        1 => 'name',
        4 => 'stic_assets_contacts_name',
        5 => 'code',
        6 => 'type',
        7 => 'start_date',
        8 => 'address_city',
        9 => 'address_street',
    ),
    'searchdefs' => array(
        'name' => array(
            'type' => 'name',
            'link' => true,
            'label' => 'LBL_NAME',
            'width' => '10%',
            'name' => 'name',
        ),
        'stic_assets_contacts_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_STIC_ASSETS_CONTACTS_FROM_CONTACTS_TITLE',
            'id' => 'STIC_ASSETS_CONTACTSCONTACTS_IDA',
            'width' => '10%',
            'name' => 'stic_assets_contacts_name',
        ),
        'code' => array(
            'type' => 'varchar',
            'label' => 'LBL_CODE',
            'width' => '10%',
            'name' => 'code',
        ),
        'type' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'name' => 'type',
        ),
        'start_date' => array(
            'type' => 'date',
            'label' => 'LBL_START_DATE',
            'width' => '10%',
            'name' => 'start_date',
        ),
        'address_city' => array(
            'type' => 'varchar',
            'label' => 'LBL_ADDRESS_CITY',
            'width' => '10%',
            'name' => 'address_city',
        ),
        'address_street' => array(
            'type' => 'varchar',
            'label' => 'LBL_ADDRESS_STREET',
            'width' => '10%',
            'name' => 'address_street',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'width' => '32%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ),
        'STIC_ASSETS_CONTACTS_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_STIC_ASSETS_CONTACTS_FROM_CONTACTS_TITLE',
            'id' => 'STIC_ASSETS_CONTACTSCONTACTS_IDA',
            'width' => '10%',
            'default' => true,
            'name' => 'stic_assets_contacts_name',
        ),
        'TYPE' => array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'default' => true,
            'name' => 'type',
        ),
        'CODE' => array(
            'type' => 'varchar',
            'label' => 'LBL_CODE',
            'width' => '10%',
            'default' => true,
            'name' => 'code',
        ),
        'START_DATE' => array(
            'type' => 'date',
            'label' => 'LBL_START_DATE',
            'width' => '10%',
            'default' => true,
            'name' => 'start_date',
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'name' => 'assigned_user_name',
        ),
    ),
);
