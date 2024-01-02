<?php
$module_name = 'AOS_Products';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
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
        ),
        'panels' => array(
            'LBL_DEFAULT_PANEL' => array(
                0 => array(
                    0 => array(
                        'name' => 'product_image',
                        'label' => 'LBL_PRODUCT_IMAGE',
                        'customCode' => '<img src="{$fields.product_image.value}" style="max-width: 160px;" height="160"/>',
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
            'LBL_PANEL_RECORD_DETAILS' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'comment' => 'Date record created',
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
                        'comment' => 'Date record last modified',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
