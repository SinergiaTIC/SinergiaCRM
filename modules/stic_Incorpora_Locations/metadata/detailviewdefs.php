<?php
$module_name = 'stic_Incorpora_Locations';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => ''
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
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'LBL_DEFAULT_PANEL' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'town',
            'label' => 'LBL_TOWN',
          ),
          1 => 
          array (
            'name' => 'town_code',
            'label' => 'LBL_TOWN_CODE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'municipality',
            'label' => 'LBL_MUNICIPALITY',
          ),
          1 => 
          array (
            'name' => 'municipality_code',
            'label' => 'LBL_MUNICIPALITY_CODE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'state',
            'label' => 'LBL_STATE',
          ),
          1 => 
          array (
            'name' => 'state_code',
            'label' => 'LBL_STATE_CODE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
