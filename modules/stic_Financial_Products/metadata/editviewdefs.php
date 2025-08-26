<?php
$module_name = 'stic_Financial_Products';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'active',
            'label' => 'LBL_ACTIVE',
          ),
          1 => 
          array (
            'name' => 'opening_date',
            'label' => 'LBL_OPENING_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'iban',
            'label' => 'LBL_IBAN',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'opening_balance',
            'label' => 'LBL_OPENING_BALANCE',
          ),
          1 => 
          array (
            'name' => 'current_balance',
            'label' => 'LBL_CURRENT_BALANCE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'balance_error',
            'label' => 'LBL_BALANCE_ERROR',
          ),
          1 => 
          array (
            'name' => 'product_type',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_TYPE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'bank',
            'label' => 'LBL_BANK',
          ),
          1 => 
          array (
            'name' => 'stic_transactions_stic_financial_products_name',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'account_holders',
            'label' => 'LBL_ACCOUNT_HOLDERS',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
