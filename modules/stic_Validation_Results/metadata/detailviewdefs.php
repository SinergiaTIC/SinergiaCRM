<?php
$module_name = 'stic_Validation_Results';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DELETE',
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
                        'name' => 'execution_date',
                        'label' => 'LBL_EXECUTION_DATE',
                    ),
                    1 => array(
                        'name' => 'reviewed',
                        'label' => 'LBL_REVIEWED',
                    ),                       
                ),
                2 => array(
                    0 => array(
                        'name' => 'scheduler',
                        'studio' => 'visible',
                        'label' => 'LBL_SCHEDULER',
                    ),
                    1 => array(
                        'name' => 'validation_action',
                        'studio' => 'visible',
                        'label' => 'LBL_VALIDATION_ACTION',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                    1 => array(
                        'name' => 'log',
                        'studio' => 'visible',
                        'label' => 'LBL_LOG',

                    ),
                ),             
                4 => array(
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
