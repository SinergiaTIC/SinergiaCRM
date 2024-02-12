<?php
$module_name = 'adrep_menu_link';
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
          1 => 
          array (
            'name' => 'active_flag',
            'label' => 'LBL_ACTIVE_FLAG',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'menu_module',
            'studio' => 'visible',
            'label' => 'LBL_MENU_MODULE',
          ),
          1 => 
          array (
            'name' => 'menu_view',
            'studio' => 'visible',
            'label' => 'LBL_MENU_VIEW',
          ),
        ),
        2 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'id_parameter',
            'studio' => 'visible',
            'label' => 'LBL_ID_PARAMETER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'format',
            'studio' => 'visible',
            'label' => 'LBL_FORMAT',
          ),
          1 => 
          array (
            'name' => 'date_range',
            'studio' => 'visible',
            'label' => 'LBL_DATE_RANGE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'from_date_paramter',
            'studio' => 'visible',
            'label' => 'LBL_FROM_DATE_PARAMTER',
          ),
          1 => 
          array (
            'name' => 'to_date_parameter',
            'studio' => 'visible',
            'label' => 'LBL_TO_DATE_PARAMETER',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'additional_parameters',
            'label' => 'LBL_ADDITIONAL_PARAMETERS',
          ),
          1 => 
          array (
            'name' => 'run_flag',
            'label' => 'LBL_RUN_FLAG',
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
?>
