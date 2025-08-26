<?php
$module_name = 'stic_Transactions';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
        'hidden' => 
        array (
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
      'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	{sugar_getscript file="modules/Documents/documents.js"}',
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
          0 => 'document_name',
          1 => 
          array (
            'name' => 'uploadfile',
            'displayParams' => 
            array (
              'onchangeSetFileNameTo' => 'document_name',
            ),
          ),
        ),
        1 => 
        array (
          0 => 'category_id',
          1 => 'subcategory_id',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'date_transaction',
            'label' => 'LBL_DATE_TRANSACTION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'payment_method',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_METHOD',
          ),
          1 => 
          array (
            'name' => 'amount',
            'label' => 'LBL_AMOUNT',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'destination_account',
            'label' => 'LBL_DESTINATION_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'accounting_account',
            'label' => 'LBL_ACCOUNTING_ACCOUNT',
          ),
        ),
        5 => 
        array (
          0 => 'assigned_user_name',
        ),
        6 => 
        array (
          0 => 'active_date',
          1 => 'exp_date',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
      ),
    ),
  ),
);
;
?>
