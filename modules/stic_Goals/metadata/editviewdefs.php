<?php
$module_name = 'stic_Goals';
$viewdefs[$module_name] = array(
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
                        'name' => 'stic_goals_contacts_name',
                    ),
                    1 => array(
                        'name' => 'stic_goals_stic_assessments_name',
                        'label' => 'LBL_STIC_GOALS_STIC_ASSESSMENTS_FROM_STIC_ASSESSMENTS_TITLE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'stic_goals_stic_registrations_name',
                    ),
                    1 => array(
                        'name' => 'stic_goals_project_name',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'level',
                        'studio' => 'visible',
                        'label' => 'LBL_LEVEL',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'origin',
                        'studio' => 'visible',
                        'label' => 'LBL_ORIGIN',
                    ),
                    1 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'expected_end_date',
                        'label' => 'LBL_EXPECTED_END_DATE',
                    ),
                    1 => array(
                        'name' => 'actual_end_date',
                        'label' => 'LBL_ACTUAL_END_DATE',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'area',
                        'studio' => 'visible',
                        'label' => 'LBL_AREA',
                    ),
                    1 => array(
                        'name' => 'subarea',
                        'studio' => 'visible',
                        'label' => 'LBL_SUBAREA',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'follow_up',
                        'studio' => 'visible',
                        'label' => 'LBL_FOLLOW_UP',
                    ),
                    1 => 'description',
                ),
            ),

        ),
    ),
);

?>
