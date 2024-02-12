<?php
$module_name = 'adrep_report';
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
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'primary_module',
            'studio' => 'visible',
            'label' => 'LBL_PRIMARY_MODULE',
          ),
          1 =>
          array (
            'name' => 'css_file',
            'studio' => 'visible',
            'label' => 'LBL_CSS_FILE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'query',
            'studio' => 'visible',
            'label' => 'LBL_QUERY',
          ),
          1 =>
          array (
            'name' => 'custom_css',
            'studio' => 'visible',
            'label' => 'LBL_CUSTOM_CSS',
          ),
        ),
				3 =>
        array (
          0 =>
          array (
            'name' => 'chart_type',
            'studio' => 'visible',
            'label' => 'LBL_CHART_TYPE',
          ),
        ),
        4 =>
        array (
          0 => 'description',
          1 =>
          array (
            'name' => 'permission',
            'studio' => 'visible',
            'label' => 'LBL_PERMISSION',
          ),
        ),
      ),
    ),
  ),
);
?>
