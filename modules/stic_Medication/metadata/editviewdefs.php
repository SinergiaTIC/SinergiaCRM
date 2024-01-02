<?php
$module_name = 'stic_Medication';
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
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_default_panel' => 
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
            'name' => 'active_principle',
            'label' => 'LBL_ACTIVE_PRINCIPLE',
          ),
          1 => 
          array (
            'name' => 'presentation',
            'label' => 'LBL_PRESENTATION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'active',
            'label' => 'LBL_ACTIVE',
          ),
          1 => 
          array (
            'name' => 'stock_depletion',
            'label' => 'LBL_STOCK_DEPLETION',
          ),
        ),
        3 => 
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
