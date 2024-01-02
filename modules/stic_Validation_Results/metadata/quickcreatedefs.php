<?php
$module_name = 'stic_Validation_Results';
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
                'default' => array(
                    0 => array(
                        0 => 'reviewed',
                        1 => 'assigned_user_name',
                    ),
                    1 => array(
                        0 => 'description',
                ),
            ),
        ),
    ),
);
