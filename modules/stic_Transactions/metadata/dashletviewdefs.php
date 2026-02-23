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

$dashletData['stic_TransactionsDashlet']['searchFields'] = array(
    'document_name' => 
    array (
      'default' => '',
    ),
    'transaction_date' => 
    array (
      'default' => '',
    ),
    'type' => 
    array (
      'default' => '',
    ),
    'stic_transactions_stic_financial_products_name' => 
    array (
      'default' => '',
    ),
    'status' => 
    array (
      'default' => '',
    ),
    'category' => 
    array (
      'default' => '',
    ),
    'subcategory' => 
    array (
      'default' => '',
    ),
    'payment_method' => 
    array (
      'default' => '',
    ),
    'amount' => 
    array (
      'default' => '',
    ),
    'destination_account' => 
    array (
      'default' => '',
    ),
    'accounting_account' => 
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
);

$dashletData['stic_TransactionsDashlet']['columns'] = array (
    'document_name' => 
    array (
      'width' => '40%',
      'label' => 'LBL_NAME',
      'link' => true,
      'default' => true,
      'name' => 'document_name',
    ),
    'uploadfile' => 
    array (
      'type' => 'file',
      'label' => 'LBL_FILE_UPLOAD',
      'width' => '10%',
      'default' => true,
    ),
    'transaction_date' => 
    array (
      'type' => 'date',
      'label' => 'LBL_TRANSACTION_DATE',
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
    'category' => 
    array (
      'type' => 'dynamicenum',
      'studio' => 'visible',
      'label' => 'LBL_CATEGORY',
      'width' => '10%',
      'default' => true,
    ),
    'subcategory' => 
    array (
      'type' => 'dynamicenum',
      'studio' => 'visible',
      'label' => 'LBL_SUBCATEGORY',
      'width' => '10%',
      'default' => true,
    ),
    'stic_transactions_stic_financial_products_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
      'id' => 'STIC_TRANS4A5BRODUCTS_IDA',
      'width' => '10%',
      'default' => true,
    ),
    'status' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
      'default' => true,
    ),
    'amount' => 
    array (
      'type' => 'decimal',
      'label' => 'LBL_AMOUNT',
      'width' => '10%',
      'default' => true,
    ),
    'payment_method' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_PAYMENT_METHOD',
      'width' => '10%',
      'default' => false,
    ),
    'destination_account' => 
    array (
      'type' => 'varchar',
      'label' => 'LBL_DESTINATION_ACCOUNT',
      'width' => '10%',
      'default' => false,
    ),
    'accounting_account' => 
    array (
      'type' => 'varchar',
      'label' => 'LBL_ACCOUNTING_ACCOUNT',
      'width' => '10%',
      'default' => false,
    ),
    'description' => 
    array (
      'type' => 'text',
      'label' => 'LBL_DESCRIPTION',
      'sortable' => false,
      'width' => '10%',
      'default' => false,
    ),
    'assigned_user_name' => 
    array (
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => false,
    ),
    'date_entered' => 
    array (
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
    ),
    'created_by_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
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
    'modified_by_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
    ),
  );
