<?php
$module_name = 'stic_Sessions';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
                ),
            ),
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
                'LBL_PANEL_RECORD_DETAILS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
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
                        'name' => 'weekday',
                        'studio' => 'visible',
                        'label' => 'LBL_WEEKDAY',
                    ),
                    1 => array(
                        'name' => 'duration',
                        'label' => 'LBL_DURATION',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'total_attendances',
                        'label' => 'LBL_TOTAL_ATTENDANCES',
                    ),
                    1 => array(
                        'name' => 'validated_attendances',
                        'label' => 'LBL_VALIDATED_ATTENDANCES',
                    ),
                ),
                5 => array(
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
                6 => array(
                    0 => 'description',
                ),
            ),
            'lbl_panel_record_details' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_NAME',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
