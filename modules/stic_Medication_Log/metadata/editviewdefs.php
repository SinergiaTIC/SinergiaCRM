<?php
$module_name = 'stic_Medication_Log';
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
          'newTab' => true,
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
            'name' => 'stic_medication_log_stic_prescription_name',
            'displayParams' => 
            array (
              'initial_filter' => '&active_advanced="+"1"+"',
            ),
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'dosage',
            'label' => 'LBL_DOSAGE',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'intake_date',
            'label' => 'LBL_INTAKE_DATE',
          ),
          1 => 
          array (
            'name' => 'schedule',
            'studio' => 'visible',
            'label' => 'LBL_SCHEDULE',
          ),
        ),
        4 => 
        array (
          0 => '',
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'administered',
            'studio' => 'visible',
            'label' => 'LBL_ADMINISTERED',
          ),
          1 => 
          array (
            'name' => 'time',
            'label' => 'LBL_TIME',
          ),
        ),
        6 => 
        array (
          0 => '',
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'stock_depletion',
            'label' => 'LBL_STOCK_DEPLETION',
          ),
          1 => '',
        ),
        8 => 
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
