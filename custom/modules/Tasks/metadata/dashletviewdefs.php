<?php
$dashletData['TasksDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'priority' => array(
        'default' => '',
    ),
    'status' => array(
        'default' => '',
    ),
    'date_start' => array(
        'default' => '',
    ),
    'date_due' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
);
$dashletData['TasksDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_SUBJECT',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'status' => array(
        'width' => '8%',
        'label' => 'LBL_STATUS',
        'default' => true,
        'name' => 'status',
    ),
    'date_start' => array(
        'width' => '15%',
        'label' => 'LBL_START_DATE',
        'default' => true,
        'name' => 'date_start',
    ),
    'date_due' => array(
        'width' => '15%',
        'label' => 'LBL_DUE_DATE',
        'default' => true,
        'name' => 'date_due',
    ),
    'parent_name' => array(
        'width' => '30%',
        'label' => 'LBL_LIST_RELATED_TO',
        'sortable' => false,
        'dynamic_module' => 'PARENT_TYPE',
        'link' => true,
        'id' => 'PARENT_ID',
        'ACLTag' => 'PARENT',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'default' => true,
        'name' => 'parent_name',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'contact_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_CONTACT',
        'link' => true,
        'id' => 'CONTACT_ID',
        'module' => 'Contacts',
        'ACLTag' => 'CONTACT',
        'related_fields' => array(
            0 => 'contact_id',
        ),
        'name' => 'contact_name',
        'default' => false,
    ),
    'priority' => array(
        'width' => '10%',
        'label' => 'LBL_PRIORITY',
        'default' => false,
        'name' => 'priority',
    ),
    'set_complete' => array(
        'width' => '1%',
        'label' => 'LBL_LIST_CLOSE',
        'default' => false,
        'sortable' => false,
        'name' => 'set_complete',
    ),
    'time_start' => array(
        'width' => '15%',
        'label' => 'LBL_START_TIME',
        'default' => false,
        'name' => 'time_start',
    ),
    'time_due' => array(
        'type' => 'datetime',
        'label' => 'LBL_DUE_TIME',
        'width' => '10%',
        'default' => false,
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'Created by',
        'sortable' => false,
        'name' => 'created_by',
        'default' => false,
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'name' => 'date_entered',
        'default' => false,
    ),
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
);
