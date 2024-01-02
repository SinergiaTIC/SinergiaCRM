<?php
$module_name = 'stic_Advanced_Security_Groups';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '3',
            'maxColumnsBasic' => '3',
            'widths' => array(
                0 => array(
                    'label' => '15',
                    'field' => '15',
                ),
                1 => array(
                    'label' => '15',
                    'field' => '15',
                ),
                2 => array(
                    'label' => '15',
                    'field' => '15',
                ),
                
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL1' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => array('name' => 'name_lbl',
                        'type' => 'readonly',
                        'customCode' => '<h1>{$fields.name_lbl.value}</h1>',

                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'inherit_assigned',
                        'studio' => 'visible',
                        'label' => 'LBL_INHERIT_ASSIGNED',
                    ),
                    1 => array(
                        'name' => 'inherit_creator',
                        'studio' => 'visible',
                        'label' => 'LBL_INHERIT_CREATOR',
                    ),
                    2 => array(
                        'name' => 'inherit_parent',
                        'studio' => 'visible',
                        'label' => 'LBL_INHERIT_PARENT',
                    ),

                ),
                4 => array(
                    0 => array(
                        'name' => 'inherit_from_modules',
                        'studio' => 'visible',
                        'label' => 'LBL_INHERIT_FROM_MODULES',
                    ),

                ),
                5 => array(
                    0 => array(
                        'name' => 'non_inherit_from_security_groups',
                        'studio' => 'visible',
                        'label' => 'LBL_NON_INHERIT_FROM_SECURITY_GROUPS',
                    ),
                    1 => '',
                ),

            ),

        ),
    ),
);
