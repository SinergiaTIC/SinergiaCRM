<?php
// created: 2025-08-21 14:01:11
$dictionary["stic_Financial_Products"]["fields"]["stic_transactions_stic_financial_products"] = array (
  'name' => 'stic_transactions_stic_financial_products',
  'type' => 'link',
  'relationship' => 'stic_transactions_stic_financial_products',
  'source' => 'non-db',
  'module' => 'stic_Transactions',
  'bean_name' => 'stic_Transactions',
  'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_TRANSACTIONS_TITLE',
  'id_name' => 'stic_transactions_stic_financial_productsstic_transactions_ida',
);
$dictionary["stic_Financial_Products"]["fields"]["stic_transactions_stic_financial_products_name"] = array (
  'name' => 'stic_transactions_stic_financial_products_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_TRANSACTIONS_TITLE',
  'save' => true,
  'id_name' => 'stic_transactions_stic_financial_productsstic_transactions_ida',
  'link' => 'stic_transactions_stic_financial_products',
  'table' => 'stic_transactions',
  'module' => 'stic_Transactions',
  'rname' => 'document_name',
);
$dictionary["stic_Financial_Products"]["fields"]["stic_transactions_stic_financial_productsstic_transactions_ida"] = array (
  'name' => 'stic_transactions_stic_financial_productsstic_transactions_ida',
  'type' => 'link',
  'relationship' => 'stic_transactions_stic_financial_products',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
);
