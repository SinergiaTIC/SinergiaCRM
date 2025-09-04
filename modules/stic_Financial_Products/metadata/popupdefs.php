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
    'opening_date' => 'stic_financial_products.opening_date',
    'active' => 'stic_financial_products.active',
    'stic_financial_products_contacts_name' => 'stic_financial_products.stic_financial_products_contacts_name',
    'product_type' => 'stic_financial_products.product_type',
    'balance_error' => 'stic_financial_products.balance_error',
    'initial_balance' => 'stic_financial_products.initial_balance',
    'current_balance' => 'stic_financial_products.current_balance',
    'bank_entity' => 'stic_financial_products.bank_entity',
    'iban' => 'stic_financial_products.iban',
    'bank_account_holders' => 'stic_financial_products.bank_account_holders',
    'assigned_user_name' => 'stic_financial_products.assigned_user_name',
  ),
  'searchInputs' => array(
    1 => 'name',
    5 => 'opening_date',
    6 => 'active',
    7 => 'stic_financial_products_contacts_name',
    8 => 'product_type',
    9 => 'balance_error',
    10 => 'initial_balance',
    11 => 'current_balance',
    12 => 'bank_entity',
    13 => 'iban',
    14 => 'bank_account_holders',
    16 => 'assigned_user_name',
  ),
  'searchdefs' => array(
    'name' =>
    array(
      'name' => 'name',
      'width' => '10%',
    ),
    'opening_date' =>
    array(
      'type' => 'date',
      'label' => 'LBL_OPENING_DATE',
      'width' => '10%',
      'name' => 'opening_date',
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
    'product_type' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_PRODUCT_TYPE',
      'width' => '10%',
      'name' => 'product_type',
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
    'bank_entity' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_BANK_ENTITY',
      'width' => '10%',
      'name' => 'bank_entity',
    ),
    'iban' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_IBAN',
      'width' => '10%',
      'name' => 'iban',
    ),
    'bank_account_holders' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_BANK_ACCOUNT_HOLDERS',
      'width' => '10%',
      'name' => 'bank_account_holders',
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
    'OPENING_DATE' =>
    array(
      'type' => 'date',
      'label' => 'LBL_OPENING_DATE',
      'width' => '10%',
      'default' => true,
      'name' => 'opening_date',
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
      'label' => 'LBL_PRODUCT_TYPE',
      'width' => '10%',
      'default' => true,
      'name' => 'product_type',
    ),
    'BANK_ENTITY' =>
    array(
      'type' => 'varchar',
      'label' => 'LBL_BANK_ENTITY',
      'width' => '10%',
      'default' => true,
      'name' => 'bank_entity',
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
