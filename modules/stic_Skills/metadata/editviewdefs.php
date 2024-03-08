<?php
$module_name = 'stic_Skills';
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
        'LBL_PANEL_LANGUAGE' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
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
            'name' => 'stic_skills_contacts_name',
          ),
          1 => 
          array (
            'name' => 'skill',
            'label' => 'LBL_SKILL',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'level',
            'studio' => 'visible',
            'label' => 'LBL_LEVEL',
          ),
        ),
        3 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_panel_language' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'language',
            'studio' => 'visible',
            'label' => 'LBL_LANGUAGE',
          ),
          1 => 
          array (
            'name' => 'other',
            'label' => 'LBL_OTHER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'oral',
            'studio' => 'visible',
            'label' => 'LBL_ORAL ',
          ),
          1 => 
          array (
            'name' => 'written',
            'studio' => 'visible',
            'label' => 'LBL_WRITTEN ',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'certificate',
            'studio' => 'visible',
            'label' => 'LBL_CERTIFICATE',
          ),
          1 => 
          array (
            'name' => 'certificate_date',
            'label' => 'LBL_CERTIFICATE_DATE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
