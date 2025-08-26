<?php
// created: 2025-08-21 14:01:11
$dictionary["stic_transactions_stic_financial_products"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'stic_transactions_stic_financial_products' => 
    array (
      'lhs_module' => 'stic_Transactions',
      'lhs_table' => 'stic_transactions',
      'lhs_key' => 'id',
      'rhs_module' => 'stic_Financial_Products',
      'rhs_table' => 'stic_financial_products',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'stic_transactions_stic_financial_products_c',
      'join_key_lhs' => 'stic_transactions_stic_financial_productsstic_transactions_ida',
      'join_key_rhs' => 'stic_transc9a2roducts_idb',
    ),
  ),
  'table' => 'stic_transactions_stic_financial_products_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'stic_transactions_stic_financial_productsstic_transactions_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'stic_transc9a2roducts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'stic_transactions_stic_financial_productsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'stic_transactions_stic_financial_products_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'stic_transactions_stic_financial_productsstic_transactions_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'stic_transactions_stic_financial_products_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'stic_transc9a2roducts_idb',
      ),
    ),
  ),
);