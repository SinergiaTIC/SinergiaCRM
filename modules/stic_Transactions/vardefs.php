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

$dictionary['stic_Transactions'] = array(
    'table' => 'stic_transactions',
    'audited' => true,
    'inline_edit' => true,
    'fields' => array (
  'transaction_date' => 
  array (
    'required' => true,
    'name' => 'transaction_date',
    'vname' => 'LBL_TRANSACTION_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'date_range_search_dom',
    'enable_range_search' => true,
  ),
  'type' => 
  array (
    'required' => true,
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_payments_transaction_types_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'amount' => 
  array (
    'required' => true,
    'name' => 'amount',
    'vname' => 'LBL_AMOUNT',
    'type' => 'decimal',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '19',
    'size' => '20',
    'enable_range_search' => false,
    'precision' => '2',
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'required' => true,
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_transactions_status_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'payment_method' => 
  array (
    'required' => false,
    'name' => 'payment_method',
    'vname' => 'LBL_PAYMENT_METHOD',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_payments_methods_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'destination_account' => 
  array (
    'required' => false,
    'name' => 'destination_account',
    'vname' => 'LBL_DESTINATION_ACCOUNT',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'popupHelp' => 'LBL_DESTINATION_ACCOUNT_INFO',
  ),
  'accounting_account' => 
  array (
    'required' => false,
    'name' => 'accounting_account',
    'vname' => 'LBL_ACCOUNTING_ACCOUNT',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'popupHelp' => 'LBL_ACCOUNTING_ACCOUNT_INFO',
  ),
  'category' => 
  array (
    'required' => true,
    'name' => 'category',
    'vname' => 'LBL_CATEGORY',
    'type' => 'dynamicenum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => true,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_transactions_categories_list',
    'studio' => 'visible',
    'dbType' => 'enum',
    'parentenum' => 'type',
    'popupHelp' => 'LBL_CATEGORY_INFO',
  ),
  'subcategory' => 
  array (
    'required' => false,
    'name' => 'subcategory',
    'vname' => 'LBL_SUBCATEGORY',
    'type' => 'dynamicenum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_transactions_subcategories_list',
    'studio' => 'visible',
    'dbType' => 'enum',
    'parentenum' => 'category',
    'popupHelp' => 'LBL_CATEGORY_INFO',
  ),
  'transaction_hash' =>
  array (
    'name' => 'transaction_hash',
    'vname' => 'LBL_TRANSACTION_HASH',
    'type' => 'varchar',
    'len' => 64,
    'audited' => false,
    'duplicate_merge' => 'disabled',
    'reportable' => true,
    'unified_search' => false,
    'massupdate' => false,
    'importable' => 'true',
    'studio' => true,
    'required' => false,
    'popupHelp' => 'LBL_TRANSACTION_HASH_INFO',
  ),

  // Relationships
  'stic_transactions_stic_financial_products' =>
  array (
    'name' => 'stic_transactions_stic_financial_products',
    'type' => 'link',
    'relationship' => 'stic_transactions_stic_financial_products',
    'source' => 'non-db',
    'module' => 'stic_Financial_Products',
    'bean_name' => false,
    'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
    'id_name' => 'stic_trans4a5broducts_ida',
  ),
  'stic_transactions_stic_financial_products_name' =>
  array (
    'name' => 'stic_transactions_stic_financial_products_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
    'save' => true,
    'id_name' => 'stic_trans4a5broducts_ida',
    'link' => 'stic_transactions_stic_financial_products',
    'table' => 'stic_financial_products',
    'module' => 'stic_Financial_Products',
    'rname' => 'name',
  ),
  'stic_trans4a5broducts_ida' =>
  array (
    'name' => 'stic_trans4a5broducts_ida',
    'type' => 'link',
    'relationship' => 'stic_transactions_stic_financial_products',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_TRANSACTIONS_TITLE',
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Transactions', 'stic_Transactions', array('basic','assignable','security_groups','file'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Transactions']['fields']['document_name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Transactions']['fields']['document_name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Transactions']['fields']['document_name']['inline_edit'] = false; // Name can not edit inline in this module
