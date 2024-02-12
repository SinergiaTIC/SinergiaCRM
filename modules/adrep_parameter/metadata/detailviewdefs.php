<?php
$module_name = 'adrep_parameter';
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
          0 => 
          array (
            'name' => 'report_name',
            'studio' => 'visible',
            'label' => 'LBL_REPORT_NAME',
          ),
          1 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'parameter_label',
            'label' => 'LBL_PARAMETER_LABEL',
          ),
          1 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'decimals',
            'label' => 'LBL_DECIMALS',
          ),
          1 => 
          array (
            'name' => 'dropdown_name',
            'studio' => 'visible',
            'label' => 'LBL_DROPDOWN_NAME',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'active_flag',
            'label' => 'LBL_ACTIVE_FLAG',
          ),
          1 => 
          array (
            'name' => 'priority',
            'label' => 'LBL_PRIORITY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'default_value',
            'label' => 'LBL_DEFAULT_VALUE',
          ),
          1 => 'description',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
?>
