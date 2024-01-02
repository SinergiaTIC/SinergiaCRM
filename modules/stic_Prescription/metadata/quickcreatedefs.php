<?php
$module_name = 'stic_Prescription';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
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
            'name' => 'stic_prescription_contacts_name',
            'label' => 'LBL_STIC_PRESCRIPTION_CONTACTS_FROM_CONTACTS_TITLE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_prescription_stic_medication_name',
            'label' => 'LBL_STIC_PRESCRIPTION_STIC_MEDICATION_FROM_STIC_MEDICATION_TITLE',
            'displayParams' =>
            array(
              'initial_filter' => '&active_advanced="+"1"+"',
            ),
          ),
          1 => 
          array (
            'name' => 'dosage',
            'label' => 'LBL_DOSAGE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'frequency',
            'studio' => 'visible',
            'label' => 'LBL_FREQUENCY',
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
          0 => 
          array (
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
          1 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'prescription',
            'label' => 'LBL_PRESCRIPTION',
          ),
          1 => 
          array (
            'name' => 'prescriber',
            'studio' => 'visible',
            'label' => 'LBL_PRESCRIBER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'skip_intake',
            'studio' => 'visible',
            'label' => 'LBL_SKIP_INTAKE',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'stock_depletion_date',
            'label' => 'LBL_STOCK_DEPLETION_DATE',
          ),
          1 => '',
        ),
        9 => 
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
