<?php
$module_name = 'adrep_report';
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
      'primary_module' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PRIMARY_MODULE',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_module',
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
      'primary_module' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PRIMARY_MODULE',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_module',
      ),
      'css_file' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CSS_FILE',
        'width' => '10%',
        'name' => 'css_file',
      ),
      'permission' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PERMISSION',
        'width' => '10%',
        'name' => 'permission',
      ),
      'query' => 
      array (
        'type' => 'text',
        'studio' => 'visible',
        'label' => 'LBL_QUERY',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'query',
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
