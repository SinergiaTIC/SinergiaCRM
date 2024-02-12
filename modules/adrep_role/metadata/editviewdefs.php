<?php
$module_name = 'adrep_role';
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
            'name' => 'role_id',
            'studio' => 'visible',
            'label' => 'LBL_ROLE_ID',
          ),
          1 => 
          array (
            'name' => 'allowed_flag',
            'label' => 'LBL_ALLOWED_FLAG',
          ),
        ),
      ),
    ),
  ),
);
?>
