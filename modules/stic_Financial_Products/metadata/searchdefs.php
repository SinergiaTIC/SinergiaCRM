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

$module_name = 'stic_Financial_Products';
$searchdefs[$module_name] = array(
    'templateMeta' => 
    array (
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => 
        array (
        'label' => '10',
        'field' => '30',
        ),
    ),
    'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'start_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
      ),
      'stic_financial_products_contacts_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'STIC_FINANCIAL_PRODUCTS_CONTACTSCONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'stic_financial_products_contacts_name',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'current_balance' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_CURRENT_BALANCE',
        'width' => '10%',
        'default' => true,
        'name' => 'current_balance',
      ),
      'entity' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ENTITY',
        'width' => '10%',
        'default' => true,
        'name' => 'entity',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => array(
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'start_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
      ),
      'active' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ACTIVE',
        'width' => '10%',
        'default' => true,
        'name' => 'active',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'stic_financial_products_contacts_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'STIC_FINANCIAL_PRODUCTS_CONTACTSCONTACTS_IDA',
        'name' => 'stic_financial_products_contacts_name',
      ),
      'balance_error' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_BALANCE_ERROR',
        'width' => '10%',
        'default' => true,
        'name' => 'balance_error',
      ),
      'initial_balance' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_INITIAL_BALANCE',
        'width' => '10%',
        'default' => true,
        'name' => 'initial_balance',
      ),
      'current_balance' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_CURRENT_BALANCE',
        'width' => '10%',
        'default' => true,
        'name' => 'current_balance',
      ),
      'entity' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ENTITY',
        'width' => '10%',
        'default' => true,
        'name' => 'entity',
      ),
      'iban' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_IBAN',
        'width' => '10%',
        'default' => true,
        'name' => 'iban',
      ),
      'holders' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_HOLDERS',
        'width' => '10%',
        'default' => true,
        'name' => 'holders',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'description' => 
      array (
        'type' => 'text',
        'studio' => 'visible',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'current_user_only',
      ),
      'favorites_only' => array(
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
);
