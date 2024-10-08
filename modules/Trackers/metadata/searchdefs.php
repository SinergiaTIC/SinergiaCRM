<?php
$module_name = 'Trackers';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'assigned_user_link' => array(
                'name' => 'assigned_user_link',
                'type' => 'relate',
                'width' => '10%',
                'default' => true,
                'vname' => 'LBL_ASSIGNED_TO_USER',
            ),
            'date_modified' => array(
                'type' => 'datetimecombo',
                'label' => 'LBL_DATE_LAST_ACTION',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'action' => array(
                'type' => 'enum',
                'label' => 'LBL_ACTION',
                'width' => '10%',
                'default' => true,
                'name' => 'action',
            ),
            'module_name' => array(
                'type' => 'enum',
                'label' => 'LBL_MODULE_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'module_name',
            ),
            'current_user_only' => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'assigned_user_link' => array(
                'name' => 'assigned_user_link',
                'type' => 'relate',
                'width' => '10%',
                'default' => true,
                'vname' => 'LBL_ASSIGNED_TO_USER',
            ),
            'date_modified' => array(
                'type' => 'datetimecombo',
                'label' => 'LBL_DATE_LAST_ACTION',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'action' => array(
                'type' => 'enum',
                'label' => 'LBL_ACTION',
                'width' => '10%',
                'default' => true,
                'name' => 'action',
            ),
            'module_name' => array(
                'type' => 'enum',
                'label' => 'LBL_MODULE_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'module_name',
            ),
            'monitor_id' => array(
                'type' => 'id',
                'width' => '5%',
                'label' => 'LBL_MONITOR_ID',
                'default' => true,
                'name' => 'monitor_id',
            ),
            'session_id' => array(
                'name' => 'session_id',
                'label' => 'LBL_SESSION_ID',
                'type' => 'varchar',
                'width' => '5%',
                'default' => true,
            ),
            'item_summary' => array(
                'type' => 'varchar',
                'label' => 'LBL_ITEM_SUMMARY',
                'width' => '10%',
                'default' => true,
                'name' => 'item_summary',
            ),
            'item_id' => array(
                'type' => 'varchar',
                'label' => 'LBL_ITEM_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'item_id',
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);
