<?php
$module_name = 'Trackers';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'tracker_user' => array(
                'type' => 'relate',
                'studio' => 'visible',
                'label' => 'LBL_TRACKER_USER',
                'id' => 'USER_ID',
                'link' => true,
                'width' => '10%',
                'default' => true,
                'name' => 'tracker_user',
            ),
            'action' => array(
              'name' => 'action',
              'vname' => 'LBL_ACTION',
              'type' => 'multienum',
              'isnull' => 'false',
                'width' => '10%',
                'default' => true,
            ),
            // ),
        ),
        'advanced_search' => array(
            'date_modified' => array (
              'type' => 'datetime',
              'label' => 'LBL_DATE_LAST_ACTION',
              'width' => '10%',
              'default' => true,
              'name' => 'date_modified',
            ),
            'tracker_user' => array(
              'type' => 'relate',
              'studio' => 'visible',
              'label' => 'LBL_TRACKER_USER',
              'id' => 'USER_ID',
              'link' => true,
              'width' => '10%',
              'default' => true,
              'name' => 'tracker_user',
            ),
            'action' => array(
              'name' => 'action',
              'vname' => 'LBL_ACTION',
              'type' => 'multienum',
              'isnull' => 'false',
                'width' => '10%',
                'default' => true,
            ),
            'module_name' => array(
                'width' => '10%',
                'default' => true,
                'name' => 'module_name',
                'vname' => 'LBL_MODULE_NAME',
                'type' => 'multienum',
            ),
            'item_id' => array(
                'width' => '10%',
                'default' => false,
                'name' => 'item_id',
                'vname' => 'LBL_ITEM_ID',
                'type' => 'varchar',
            ),
            'item_summary' => array(
              'type' => 'varchar',
              'label' => 'LBL_ITEM_SUMMARY',
              'width' => '10%',
              'default' => true,
            ),
            'current_user_only' => array (
              'label' => 'LBL_CURRENT_USER_FILTER',
              'type' => 'bool',
              'default' => true,
              'width' => '10%',
              'name' => 'current_user_only',
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
