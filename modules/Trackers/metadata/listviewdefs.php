<?php
$module_name = 'Trackers';
$listViewDefs[$module_name] =
array(
    'DATE_MODIFIED' => array(
        'type' => 'datetimecombo',
        'label' => 'LBL_DATE_LAST_ACTION',
        'width' => '5%',
        'default' => true,
    ),
    'USER_ID' => array(
        'type' => 'varchar',
        'studio' => 'visible',
        'label' => 'LBL_USER_ID',
        'id' => 'USER_ID',
        'link' => true,
        'width' => '10%',
        'default' => false,
        'customCode' => '<a href="index.php?module=Users&action=DetailView&record={$USER_ID}">{$USER_ID}</a>',
    ),
    'ASSIGNED_USER_LINK' => array(
        'type' => 'relate',
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO_USER',
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
        'type' => 'enum',
        'label' => 'LBL_MODULE_NAME',
        'width' => '10%',
        'default' => true,
    ),
    'ITEM_ID' => array(
        'type' => 'varchar',
        'label' => 'LBL_ITEM_ID',
        'width' => '10%',
        'default' => false,
        'customCode' => '<a href="index.php?module={$MODULE_NAME}&action=DetailView&record={$ITEM_ID}">{$ITEM_ID}</a>',
    ),
    'ITEM_SUMMARY' => array(
        'type' => 'varchar',
        'label' => 'LBL_ITEM_SUMMARY',
        'width' => '20%',
        'default' => true,
        'id' => 'ITEM_ID',
        'link' => false,
        'customCode' => '<a href="index.php?module={$MODULE_NAME}&action=DetailView&record={$ITEM_ID}">{$ITEM_SUMMARY}</a>',
    ),
    'SESSION_ID' => array(
        'name' => 'session_id',
        'label' => 'LBL_SESSION_ID',
        'type' => 'varchar',
        'width' => '10%',
        'default' => false,
    ),
    'MONITOR_ID'=>array(
        'name' => 'monitor_id',
        'label' => 'LBL_MONITOR_ID',
        'type' => 'id',
        'width' => '10%',
        'default' => false,
    ),
);