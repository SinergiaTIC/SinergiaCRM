<?php
$module_name = 'stic_Medication_Log';
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
          ),
          1 => 
          array (
            'name' => 'stic_medication_log_contacts_name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'medication',
            'label' => 'LBL_MEDICATION',
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
