<?php
$module_name = 'adrep_parameter';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'report_name' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_REPORT_NAME',
        'id' => 'REPORT_ID',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'report_name',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'parameter_label' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_PARAMETER_LABEL',
        'width' => '10%',
        'name' => 'parameter_label',
      ),
      'report_name' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_REPORT_NAME',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'id' => 'REPORT_ID',
        'name' => 'report_name',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'dropdown_name' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_DROPDOWN_NAME',
        'width' => '10%',
        'name' => 'dropdown_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
