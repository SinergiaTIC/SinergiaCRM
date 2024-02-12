<?php
$module_name = 'adrep_schedule';
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
            'name' => 'report_name',
            'studio' => 'visible',
            'label' => 'LBL_REPORT_NAME',
          ),
          1 => 
          array (
            'name' => 'format',
            'studio' => 'visible',
            'label' => 'LBL_FORMAT',
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
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'email_column',
            'label' => 'LBL_EMAIL_COLUMN',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'dow',
            'studio' => 'visible',
            'label' => 'LBL_DOW',
          ),
          1 => 
          array (
            'name' => 'tod',
            'label' => 'LBL_TOD',
          ),
        ),
        5 => 
        array (
          0 => 'description',
        ),
        6 => 
        array (
          0 => 'date_entered',
          1 => 'date_modified',
        ),
      ),
    ),
  ),
);
?>
