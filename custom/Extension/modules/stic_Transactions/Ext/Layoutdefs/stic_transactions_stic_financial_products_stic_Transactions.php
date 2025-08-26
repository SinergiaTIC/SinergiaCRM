<?php
 // created: 2025-08-21 14:01:11
$layout_defs["stic_Transactions"]["subpanel_setup"]['stic_transactions_stic_financial_products'] = array (
  'order' => 100,
  'module' => 'stic_Financial_Products',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
  'get_subpanel_data' => 'stic_transactions_stic_financial_products',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
