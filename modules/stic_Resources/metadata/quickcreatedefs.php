<?php
$module_name = 'stic_Resources';
$viewdefs[$module_name] =
array(
    'QuickCreate' => array(
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
            'useTabs' => false,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
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
                        'name' => 'daily_rate',
                        'label' => 'LBL_DAILY_RATE',
                    ),
                    1 => array(
                        'name' => 'hourly_rate',
                        'label' => 'LBL_HOURLY_RATE',
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
                    0 => array(
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
        ),
    ),
);
