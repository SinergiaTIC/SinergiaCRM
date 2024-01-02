<?php
$module_name = 'stic_Prescription';
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_RECORD_DETAILS' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
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
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_prescription_stic_medication_name',
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
            'name' => 'active',
            'label' => 'LBL_ACTIVE',
          ),
          1 => '',
        ),
        6 => 
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
        7 => 
        array (
          0 => 
          array (
            'name' => 'skip_intake',
            'studio' => 'visible',
            'label' => 'LBL_SKIP_INTAKE',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'stock_depletion_date',
            'label' => 'LBL_STOCK_DEPLETION_DATE',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
      ),
      'lbl_panel_record_details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
;
?>
