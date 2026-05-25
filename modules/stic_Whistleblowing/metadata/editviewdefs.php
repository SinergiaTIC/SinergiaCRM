<?php
$module_name = 'stic_Whistleblowing';
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
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'stic_category',
            'studio' => 'visible',
            'label' => 'LBL_STIC_CATEGORY',
          ),
          1 => 
          array (
            'name' => 'stic_code',
            'label' => 'LBL_STIC_CODE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_status',
            'studio' => 'visible',
            'label' => 'LBL_STIC_STATUS',
          ),
          1 => 
          array (
            'name' => 'stic_status_detail',
            'label' => 'LBL_STIC_STATUS_DETAIL',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'stic_start_date',
            'label' => 'LBL_STIC_START_DATE',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
