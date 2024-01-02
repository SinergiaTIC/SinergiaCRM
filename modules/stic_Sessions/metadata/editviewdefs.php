<?php
$module_name = 'stic_Sessions';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => false,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_sessions_stic_events_name',
                        'label' => 'LBL_STIC_SESSIONS_STIC_EVENTS_FROM_STIC_EVENTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'color',
                        'label' => 'LBL_COLOR',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'responsible',
                        'studio' => 'visible',
                        'label' => 'LBL_STIC_RESPONSIBLE',
                    ),
                    1 => array(
                        'name' => 'activity_type',
                        'studio' => 'visible',
                        'label' => 'LBL_ACTIVITY_TYPE',
                    ),
                ),
                4 => array(
                    0 => 'description',
                ),
            ),
        ),
    ),
);

?>
