<?php
$module_name = 'stic_Events';
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
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_ECONOMY_PANEL' => 
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
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        2 => 
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
        3 => 
        array (
          0 => 
          array (
            'name' => 'stic_events_project_name',
            'label' => 'LBL_STIC_EVENTS_PROJECT_FROM_PROJECT_TITLE',
          ),
          1 => 
          array (
            'name' => 'stic_events_fp_event_locations_name',
            'label' => 'LBL_STIC_EVENTS_FP_EVENT_LOCATIONS_FROM_FP_EVENT_LOCATIONS_TITLE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'max_attendees',
            'label' => 'LBL_MAX_ATTENDEES',
          ),
          1 => 
          array (
            'name' => 'attendees',
            'label' => 'LBL_ATTENDEES',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'timetable',
            'label' => 'LBL_TIMETABLE',
          ),
          1 => 
          array (
            'name' => 'total_hours',
            'label' => 'LBL_TOTAL_HOURS',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'discard_reason',
            'studio' => 'visible',
            'label' => 'LBL_DISCARD_REASON',
          ),
          1 => 
          array (
            'name' => 'session_amount',
            'label' => 'LBL_SESSION_AMOUNT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'session_color',
            'label' => 'LBL_SESSION_COLOR',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'lbl_economy_panel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'expected_cost',
            'label' => 'LBL_EXPECTED_COST',
          ),
          1 => 
          array (
            'name' => 'actual_cost',
            'label' => 'LBL_ACTUAL_COST',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'price',
            'label' => 'LBL_PRICE',
          ),
          1 => 
          array (
            'name' => 'budget',
            'label' => 'LBL_BUDGET',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'expected_income',
            'label' => 'LBL_EXPECTED_INCOME',
          ),
          1 => 
          array (
            'name' => 'actual_income',
            'label' => 'LBL_ACTUAL_INCOME',
          ),
        ),
      ),
    ),
  ),
);
;
?>
