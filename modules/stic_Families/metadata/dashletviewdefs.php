<?php
$dashletData['stic_FamiliesDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'code' => array(
        'default' => '',
    ),
    'type' => array(
        'default' => '',
    ),
    'active' => array(
        'default' => '',
    ),
    'start_date' => array(
        'default' => '',
    ),
    'end_date' => array(
        'default' => '',
    ),
    'members_amount' => array(
        'default' => '',
    ),
    'income' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
);
$dashletData['stic_FamiliesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'code' => array(
        'type' => 'int',
        'label' => 'LBL_CODE',
        'width' => '10%',
        'default' => true,
    ),
    'type' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'active' => array(
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_ACTIVE',
        'width' => '10%',
    ),
    'members_amount' => array(
        'type' => 'int',
        'label' => 'LBL_MEMBERS_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'income' => array(
        'type' => 'decimal',
        'label' => 'LBL_INCOME',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'start_date' => array(
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'end_date' => array(
        'type' => 'date',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'LBL_CREATED',
        'name' => 'created_by',
        'default' => false,
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
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
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
);
