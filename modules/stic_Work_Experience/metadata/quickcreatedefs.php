<?php
$module_name = 'stic_Work_Experience';
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
        'DEFAULT' => 
        array (
          'newTab' => false,
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
            'name' => 'stic_work_experience_contacts_name',
            'label' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_CONTACTS_TITLE',
          ),
          1 => 
          array (
            'name' => 'stic_work_experience_accounts_name',
            'label' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_ACCOUNTS_TITLE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'position',
            'label' => 'LBL_POSITION',
          ),
          1 => 
          array (
            'name' => 'position_type',
            'studio' => 'visible',
            'label' => 'LBL_POSITION_TYPE',
          ),
        ),
        3 => 
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
        4 => 
        array (
          0 => 
          array (
            'name' => 'contract_type',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_TYPE',
          ),
          1 => 
          array (
            'name' => 'workday_type',
            'studio' => 'visible',
            'label' => 'LBL_WORKDAY_TYPE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'sector',
            'studio' => 'visible',
            'label' => 'LBL_SECTOR',
          ),
          1 => 
          array (
            'name' => 'subsector',
            'studio' => 'visible',
            'label' => 'LBL_SUBSECTOR',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'schedule',
            'label' => 'LBL_SCHEDULE',
          ),
          1 => 
          array (
            'name' => 'achieved',
            'label' => 'LBL_ACHIEVED',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
;
?>
