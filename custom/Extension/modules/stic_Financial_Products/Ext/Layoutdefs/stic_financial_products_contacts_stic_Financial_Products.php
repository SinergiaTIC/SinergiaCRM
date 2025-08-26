<?php
 // created: 2025-08-21 14:01:11
$layout_defs["stic_Financial_Products"]["subpanel_setup"]['stic_financial_products_contacts'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_STIC_FINANCIAL_PRODUCTS_CONTACTS_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'stic_financial_products_contacts',
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
