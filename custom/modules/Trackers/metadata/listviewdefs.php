<?php
$module_name = 'Trackers';
$listViewDefs[$module_name] =
array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'USER_ID' => array(
        'width' => '10%',
        'label' => 'LBL_USER_ID',
        'default' => true,
        'module' => 'Users'
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'ACTION' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ACTION',
        'width' => '10%',
        'default' => true,
    ),
    'MODULE_NAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_MODULE_NAME',
        'width' => '10%',
        'default' => true,
    ),
    'ITEM_ID' => array(
        'type' => 'varchar',
        'label' => 'LBL_ITEM_ID',
        'width' => '10%',
        'default' => true,
    ),
    'ITEM_SUMMARY' => array(
        'type' => 'varchar',
        'label' => 'LBL_ITEM_SUMMARY',
        'width' => '10%',
        'default' => true,
    ),
);
