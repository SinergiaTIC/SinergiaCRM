<?php
$module_name = 'stic_Financial_Products';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
            'studio' => 'visible',
            'label' => 'LBL_ACTIVE',
          ),
          1 => 
          array (
            'name' => 'stic_financial_products_contacts_name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'product_type',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_TYPE',
          ),
          1 => 
          array (
            'name' => 'iban',
            'label' => 'LBL_IBAN',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'opening_date',
            'label' => 'LBL_OPENING_DATE',
          ),
          1 => 
          array (
            'name' => 'balance_error',
            'studio' => 'visible',
            'label' => 'LBL_BALANCE_ERROR',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'initial_balance',
            'label' => 'LBL_INITIAL_BALANCE',
          ),
          1 => 
          array (
            'name' => 'current_balance',
            'label' => 'LBL_CURRENT_BALANCE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'bank_entity',
            'label' => 'LBL_BANK_ENTITY',
          ),
          1 => 
          array (
            'name' => 'bank_account_holders',
            'label' => 'LBL_BANK_ACCOUNT_HOLDERS',
          ),
        ),
        6 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
