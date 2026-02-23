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

$popupMeta = array(
  'moduleMain' => 'stic_Financial_Products',
  'varName' => 'stic_Financial_Products',
  'orderBy' => 'stic_financial_products.name',
  'whereClauses' => array(
    'name' => 'stic_financial_products.name',
    'start_date' => 'stic_financial_products.start_date',
    'active' => 'stic_financial_products.active',
    'stic_financial_products_contacts_name' => 'stic_financial_products.stic_financial_products_contacts_name',
    'type' => 'stic_financial_products.type',
    'balance_error' => 'stic_financial_products.balance_error',
    'initial_balance' => 'stic_financial_products.initial_balance',
    'current_balance' => 'stic_financial_products.current_balance',
    'entity' => 'stic_financial_products.entity',
    'iban' => 'stic_financial_products.iban',
    'holders' => 'stic_financial_products.holders',
    'assigned_user_name' => 'stic_financial_products.assigned_user_name',
  ),
  'searchInputs' => array(
    1 => 'name',
    5 => 'start_date',
    6 => 'active',
    7 => 'stic_financial_products_contacts_name',
    8 => 'type',
    9 => 'balance_error',
    10 => 'initial_balance',
    11 => 'current_balance',
    12 => 'entity',
    13 => 'iban',
    14 => 'holders',
    16 => 'assigned_user_name',
  ),
  'searchdefs' => array(
    'name' =>
    array(
      'name' => 'name',
      'width' => '10%',
    ),
    'start_date' =>
    array(
      'type' => 'date',
      'label' => 'LBL_START_DATE',
      'width' => '10%',
      'name' => 'start_date',
    ),
    'active' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_ACTIVE',
      'width' => '10%',
      'name' => 'active',
    ),
    'stic_financial_products_contacts_name' =>
    array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
      'id' => 'STIC_FINANCIAL_PRODUCTS_CONTACTSCONTACTS_IDA',
      'width' => '10%',
      'name' => 'stic_financial_products_contacts_name',
    ),
    'type' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'name' => 'type',
    ),
    'balance_error' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_BALANCE_ERROR',
      'width' => '10%',
      'name' => 'balance_error',
    ),
    'initial_balance' =>
    array(
      'type' => 'decimal',
      'label' => 'LBL_INITIAL_BALANCE',
      'width' => '10%',
      'name' => 'initial_balance',
    ),
    'current_balance' =>
    array(
      'type' => 'decimal',
      'label' => 'LBL_CURRENT_BALANCE',
      'width' => '10%',
      'name' => 'current_balance',
    ),
    'entity' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_ENTITY',
      'width' => '10%',
      'name' => 'entity',
    ),
    'iban' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_IBAN',
      'width' => '10%',
      'name' => 'iban',
    ),
    'holders' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_HOLDERS',
      'width' => '10%',
      'name' => 'holders',
    ),
    'assigned_user_name' =>
    array(
      'link' => true,
      'type' => 'relate',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'id' => 'ASSIGNED_USER_ID',
      'width' => '10%',
      'name' => 'assigned_user_name',
    ),
  ),
  'listviewdefs' => array(
    'NAME' =>
    array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
      'name' => 'name',
    ),
    'START_DATE' =>
    array(
      'type' => 'date',
      'label' => 'LBL_START_DATE',
      'width' => '10%',
      'default' => true,
      'name' => 'start_date',
    ),
    'STIC_FINANCIAL_PRODUCTS_CONTACTS_NAME' =>
    array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
      'id' => 'STIC_FINANCIAL_PRODUCTS_CONTACTSCONTACTS_IDA',
      'width' => '10%',
      'default' => true,
      'name' => 'stic_financial_products_contacts_name',
    ),
    'PRODUCT_TYPE' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
      'name' => 'type',
    ),
    'BANK_ENTITY' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_ENTITY',
      'width' => '10%',
      'default' => true,
      'name' => 'entity',
    ),
    'IBAN' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_IBAN',
      'width' => '10%',
      'default' => true,
      'name' => 'iban',
    ),
    'CURRENT_BALANCE' =>
    array(
      'type' => 'decimal',
      'label' => 'LBL_CURRENT_BALANCE',
      'width' => '10%',
      'default' => true,
      'name' => 'current_balance',
    ),
    'ASSIGNED_USER_NAME' =>
    array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
      'name' => 'assigned_user_name',
    ),
  ),
);
