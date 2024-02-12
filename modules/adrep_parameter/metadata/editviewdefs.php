<?php
$module_name = 'adrep_parameter';
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
      'syncDetailEditViews' => false,
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
      ),
    ),
  ),
);
?>
