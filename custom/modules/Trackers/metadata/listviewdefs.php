<?php
$module_name = 'Trackers';
$listViewDefs[$module_name] =
array(
    'DATE_MODIFIED' => array(
        'sort_order' => 'asc',
        'type' => 'datetimecombo',
        'label' => 'LBL_DATE_LAST_ACTION',
        'width' => '5%',
        'default' => true,
    ),
    'TRACKER_USER' => array(
        'type' => 'varchar',
        'width' => '10%',
        'label' => 'LBL_TRACKER_USER',
        'default' => true,
        'id' => 'USER_ID',
    ),
    'USER_ID' => array(
        'type' => 'varchar',
        'studio' => 'visible',
        'label' => 'LBL_USER_ID',
        'id' => 'USER_ID',
        'link' => true,
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
        'default' => false,
    ),
    'ITEM_SUMMARY' => array(
        'type' => 'varchar',
        'label' => 'LBL_ITEM_SUMMARY',
        'width' => '20%',
        'default' => true,
        'id' => 'ITEM_ID',
        'link' => false,
    ),
    'SESSION_ID' => array(
        'name' => 'session_id',
        'label' => 'LBL_SESSION_ID',
        'type' => 'varchar',
        'width' => '10%',
        'default' => false,
    ),
);