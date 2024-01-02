<?php
$dashletData['AOS_ProductsDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'maincode' => array(
        'default' => '',
    ),
    'part_number' => array(
        'default' => '',
    ),
    'category' => array(
        'default' => '',
    ),
    'date_entered' => array(
        'default' => '',
    ),
    'date_modified' => array(
        'default' => '',
    ),
    'assigned_user_id' => array(
        'default' => '',
    ),
);
$dashletData['AOS_ProductsDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'type' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'name' => 'type',
    ),
    'maincode' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MAINCODE',
        'width' => '10%',
        'name' => 'maincode',
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
        'name' => 'date_entered',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'part_number' => array(
        'type' => 'varchar',
        'label' => 'LBL_PART_NUMBER',
        'width' => '10%',
        'default' => false,
        'name' => 'part_number',
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
    'category' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_CATEGORY',
        'width' => '10%',
        'default' => false,
        'name' => 'category',
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'LBL_CREATED',
        'name' => 'created_by',
        'default' => false,
    ),
);
