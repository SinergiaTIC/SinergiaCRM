<?php
$popupMeta = array(
    'moduleMain' => 'AOS_Product_Categories',
    'varName' => 'AOS_Product_Categories',
    'orderBy' => 'aos_product_categories.name',
    'whereClauses' => array(
        'name' => 'aos_product_categories.name',
    ),
    'searchInputs' => array(
        0 => 'aos_product_categories_number',
        1 => 'name',
        2 => 'priority',
        3 => 'status',
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'width' => '32%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ),
        'IS_PARENT' => array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_IS_PARENT',
            'width' => '10%',
            'name' => 'is_parent',
        ),
        'PARENT_CATEGORY_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_PRODUCT_CATEGORYS_NAME',
            'id' => 'PARENT_CATEGORY_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'parent_category_name',
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'name' => 'assigned_user_name',
        ),
    ),
);
