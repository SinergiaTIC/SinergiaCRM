<?php
$module_name = 'stic_Skills';
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_QUICKCREATE_PANEL1' => 
        array (
          'newTab' => true,
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
            'name' => 'stic_skills_contacts_name',
            'label' => 'LBL_STIC_SKILLS_CONTACTS_FROM_CONTACTS_TITLE',
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
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'lbl_quickcreate_panel1' => 
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
