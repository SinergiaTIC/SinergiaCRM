<?php
$module_name = 'Trackers';
$listViewDefs[$module_name] =
array(
    'MONITOR_ID' => array(
        'type' => 'id',
        'width' => '5%',
        'label' => 'LBL_MONITOR_ID',
        'default' => true,
    ),
    'TRACKER_USER' => array(
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_TRACKER_USER',
        'id' => 'USER_ID',
        'link' => true,
        'width' => '10%',
        'default' => true,
    ),
    'DATE_MODIFIED' => array(
        'sort_order' => 'asc',
        'type' => 'datetime',
        'label' => 'LBL_DATE_LAST_ACTION',
        'width' => '10%',
        'default' => true,
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
