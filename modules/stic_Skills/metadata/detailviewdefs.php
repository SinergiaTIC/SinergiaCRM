<?php
$module_name = 'stic_Skills';
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
        'LBL_PANEL_LANGUAGE' => 
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
      'LBL_PANEL_RECORD_DETAILS' => 
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
