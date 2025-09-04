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

$dictionary['stic_Financial_Products'] = array(
    'table' => 'stic_financial_products',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'active' => 
  array (
    'required' => true,
    'name' => 'active',
    'vname' => 'LBL_ACTIVE',
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
    'options' => 'checkbox_dom',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'iban' => 
  array (
    'required' => false,
    'name' => 'iban',
    'vname' => 'LBL_IBAN',
    'type' => 'varchar',
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
    'len' => '255',
    'size' => '20',
  ),
  'opening_date' => 
  array (
    'required' => false,
    'name' => 'opening_date',
    'vname' => 'LBL_OPENING_DATE',
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
    'enable_range_search' => false,
  ),
  'initial_balance' => 
  array (
    'required' => true,
    'name' => 'initial_balance',
    'vname' => 'LBL_INITIAL_BALANCE',
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
    'len' => '10',
    'size' => '20',
    'enable_range_search' => false,
    'precision' => '2',
  ),
  'current_balance' => 
  array (
    'required' => true,
    'name' => 'current_balance',
    'vname' => 'LBL_CURRENT_BALANCE',
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
    'len' => '10',
    'size' => '20',
    'enable_range_search' => false,
    'precision' => '2',
  ),
  'balance_error' => 
  array (
    'required' => true,
    'name' => 'balance_error',
    'vname' => 'LBL_BALANCE_ERROR',
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
    'options' => 'checkbox_dom',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'product_type' => 
  array (
    'required' => false,
    'name' => 'product_type',
    'vname' => 'LBL_PRODUCT_TYPE',
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
    'options' => 'stic_financial_products_product_types_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'bank_entity' => 
  array (
    'required' => false,
    'name' => 'bank_entity',
    'vname' => 'LBL_BANK_ENTITY',
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
  ),
  'bank_account_holders' => 
  array (
    'required' => false,
    'name' => 'bank_account_holders',
    'vname' => 'LBL_BANK_ACCOUNT_HOLDERS',
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
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full text of the note',
    'rows' => '6',
    'cols' => '80',
    'required' => false,
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
    'studio' => 'visible',
  ),

  // Relationships
  'stic_transactions_stic_financial_products' =>
  array (
    'name' => 'stic_transactions_stic_financial_products',
    'type' => 'link',
    'relationship' => 'stic_transactions_stic_financial_products',
    'source' => 'non-db',
    'module' => 'stic_Transactions',
    'bean_name' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_TRANSACTIONS_TITLE',
  ),
  'stic_financial_products_contacts' =>
  array (
    'name' => 'stic_financial_products_contacts',
    'type' => 'link',
    'relationship' => 'stic_financial_products_contacts',
    'source' => 'non-db',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'vname' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
    'id_name' => 'stic_financial_products_contactscontacts_ida',
  ),
  'stic_financial_products_contacts_name' =>
  array (
    'name' => 'stic_financial_products_contacts_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
    'save' => true,
    'id_name' => 'stic_financial_products_contactscontacts_ida',
    'link' => 'stic_financial_products_contacts',
    'table' => 'contacts',
    'module' => 'Contacts',
    'rname' => 'name',
    'db_concat_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
    ),
  ),
  'stic_financial_products_contactscontacts_ida' =>
  array (
    'name' => 'stic_financial_products_contactscontacts_ida',
    'type' => 'link',
    'relationship' => 'stic_financial_products_contacts',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
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
VardefManager::createVardef('stic_Financial_Products', 'stic_Financial_Products', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Financial_Products']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Financial_Products']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Financial_Products']['fields']['name']['inline_edit'] = false; // Name can not edit inline in this module
