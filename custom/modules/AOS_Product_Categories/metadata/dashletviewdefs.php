<?php
$dashletData['AOS_Product_CategoriesDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'is_parent' => array(
        'default' => '',
    ),
    'parent_category_name' => array(
        'default' => '',
    ),
    'created_by_name' => array(
        'default' => '',
    ),
    'date_entered' => array(
        'default' => '',
    ),
    'modified_by_name' => array(
        'default' => '',
    ),
    'date_modified' => array(
        'default' => '',
    ),
    'assigned_user_id' => array(
        'default' => '',
    ),
);
$dashletData['AOS_Product_CategoriesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'is_parent' => array(
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_IS_PARENT',
        'width' => '10%',
        'name' => 'is_parent',
    ),
    'parent_category_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PRODUCT_CATEGORYS_NAME',
        'id' => 'PARENT_CATEGORY_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'parent_category_name',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
        'name' => 'modified_by_name',
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
        'name' => 'created_by_name',
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
        'name' => 'description',
    ),
);
