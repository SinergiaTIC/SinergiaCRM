<?php
$module_name = 'AOS_Products';
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
            'form' => array(
                'enctype' => 'multipart/form-data',
                'headerTpl' => 'modules/AOS_Products/tpls/EditViewHeader.tpl',
            ),
            'includes' => array(
                0 => array(
                    'file' => 'modules/AOS_Products/js/products.js',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'LBL_DEFAULT_PANEL' => array(
                0 => array(
                    0 => array(
                        'name' => 'product_image',
                        'customCode' => '{$PRODUCT_IMAGE}',
                    ),
                    1 => array(
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO_NAME',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 => array(
                        'name' => 'type',
                        'label' => 'LBL_TYPE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'aos_product_category_name',
                        'studio' => 'visible',
                        'label' => 'LBL_AOS_PRODUCT_CATEGORYS_NAME',
                    ),
                    1 => array(
                        'name' => 'part_number',
                        'label' => 'LBL_PART_NUMBER',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'url',
                        'label' => 'LBL_URL',
                    ),
                    1 => array(
                        'name' => 'contact',
                        'label' => 'LBL_CONTACT',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'price',
                        'label' => 'LBL_PRICE',
                    ),
                    1 => array(
                        'name' => 'cost',
                        'label' => 'LBL_COST',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
        ),
    ),
);
