<?php
$module_name = 'stic_Sepe_Incidents';
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'LBL_DEFAULT_PANEL' => 
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
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'incident_date',
            'label' => 'LBL_INCIDENT_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_sepe_incidents_contacts_name',
          ),
          1 => '',
        ),
        3 =>
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
