<?php
$module_name = 'stic_Advanced_Security_Groups';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'maxColumnsBasic' => '2',
            'widths' => array(
                0 => array(
                    'label' => '25',
                    'field' => '25',
                ),
                1 => array(
                    'label' => '25',
                    'field' => '25',
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
                        'customCode' => '<span style="padding:2px 4px;font-weight:bold;background-color:green;color:white;">{$fields.name_lbl.value}</span>',

                    ),
                    1 => array('name' => 'active',
                        'label' => 'LBL_ACTIVE'


                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'inherit_assigned',
                        'label' => 'LBL_INHERIT_ASSIGNED',
                    ),
                    1 => array(
                        'name' => 'inherit_creator',
                        'label' => 'LBL_INHERIT_CREATOR',
                    ),
                    // 2 => array(
                    //     'name' => 'inherit_parent',
                    //     'studio' => 'visible',
                    //     'label' => 'LBL_INHERIT_PARENT',
                    // ),

                ),
                4 => array(
                    0 => array(
                        'name' => 'inherit_from_modules',
                        'studio' => 'visible',
                        'label' => 'LBL_INHERIT_FROM_MODULES',
                    ),
                    1 => array(
                        'name' => 'non_inherit_from_security_groups',
                        'studio' => 'visible',
                        'label' => 'LBL_NON_INHERIT_FROM_SECURITY_GROUPS',
                    ),

                ),
                

            ),

        ),
    ),
);
