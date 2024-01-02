<?php
$module_name = 'stic_Resources';
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
                'LBL_PANEL_STIC_RESOURCES_INFORMATION' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_DETAILVIEW_PANEL1' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'LBL_PANEL_STIC_RESOURCES_INFORMATION' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => 'code',
                    1 => array(
                        'name' => 'color',
                        'label' => 'LBL_COLOR',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'hourly_rate',
                        'label' => 'LBL_HOURLY_RATE',
                    ),
                    1 => array(
                        'name' => 'daily_rate',
                        'label' => 'LBL_DAILY_RATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'owner_contact',
                        'studio' => 'visible',
                        'label' => 'LBL_OWNER_CONTACT',
                    ),
                    1 => array(
                        'name' => 'owner_account',
                        'studio' => 'visible',
                        'label' => 'LBL_OWNER_ACCOUNT',
                    ),
                ),
                5 => array(
                    0 => 'description',
                ),
            ),
            'lbl_detailview_panel1' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
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
                    ),
                ),
            ),
        ),
    ),
);
