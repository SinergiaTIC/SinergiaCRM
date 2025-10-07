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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['stic_Financial_ProductsDashlet']['searchFields'] = array(
    'name' => 
    array (
      'default' => '',
    ),
    'active' => 
    array (
      'default' => '',
    ),
    'start_date' => 
    array (
      'default' => '',
    ),
    'stic_financial_products_contacts_name' => 
    array (
      'default' => '',
    ),
    'type' => 
    array (
      'default' => '',
    ),
    'balance_error' => 
    array (
      'default' => '',
    ),
    'initial_balance' => 
    array (
      'default' => '',
    ),
    'current_balance' => 
    array (
      'default' => '',
    ),
    'entity' => 
    array (
      'default' => '',
    ),
    'iban' => 
    array (
      'default' => '',
    ),
    'holders' => 
    array (
      'default' => '',
    ),
    'description' => 
    array (
      'default' => '',
    ),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => $current_user->name
    ),
    'created_by_name' => 
    array (
      'default' => '',
    ),
    'date_entered' => 
    array (
      'default' => '',
    ),
    'modified_by_name' => 
    array (
      'default' => '',
    ),
    'date_modified' => 
    array (
      'default' => '',
    ),
);
$dashletData['stic_Financial_ProductsDashlet']['columns'] = array(
    'name' => 
    array (
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
    ),
    'start_date' => 
    array (
      'type' => 'date',
      'label' => 'LBL_START_DATE',
      'width' => '10%',
      'default' => true,
    ),
    'stic_financial_products_contacts_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
      'id' => 'STIC_FINANCIAL_PRODUCTS_CONTACTSCONTACTS_IDA',
      'width' => '10%',
      'default' => true,
    ),
    'type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
    ),
    'entity' => 
    array (
      'type' => 'varchar',
      'label' => 'LBL_ENTITY',
      'width' => '10%',
      'default' => true,
    ),
    'current_balance' => 
    array (
      'type' => 'decimal',
      'label' => 'LBL_CURRENT_BALANCE',
      'width' => '10%',
      'default' => true,
    ),
    'iban' => 
    array (
      'type' => 'varchar',
      'label' => 'LBL_IBAN',
      'width' => '10%',
      'default' => true,
    ),
    'assigned_user_name' => 
    array (
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
    ),
    'balance_error' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_BALANCE_ERROR',
      'width' => '10%',
      'default' => false,
    ),
    'holders' => 
    array (
      'type' => 'varchar',
      'label' => 'LBL_HOLDERS',
      'width' => '10%',
      'default' => false,
    ),
    'description' => 
    array (
      'type' => 'text',
      'studio' => 'visible',
      'label' => 'LBL_DESCRIPTION',
      'sortable' => false,
      'width' => '10%',
      'default' => false,
    ),
    'active' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_ACTIVE',
      'width' => '10%',
      'default' => false,
    ),
    'initial_balance' => 
    array (
      'type' => 'decimal',
      'label' => 'LBL_INITIAL_BALANCE',
      'width' => '10%',
      'default' => false,
    ),
    'created_by' => 
    array (
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
    ),
    'date_entered' => 
    array (
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
    ),
    'modified_by_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
    ),
    'date_modified' => 
    array (
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
    ),
);
