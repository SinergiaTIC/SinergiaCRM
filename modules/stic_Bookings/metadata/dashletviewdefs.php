<?php
$dashletData['stic_BookingsDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'code' => array(
        'default' => '',
    ),
    'status' => array(
        'default' => '',
    ),
    'start_date' => array(
        'default' => '',
    ),
    'end_date' => array(
        'default' => '',
    ),
    'stic_bookings_accounts_name' => array(
        'default' => '',
    ),
    'stic_bookings_contacts_name' => array(
        'default' => '',
    ),
    'description' => array(
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
    'assigned_user_name' => array(
        'default' => '',
    ),
);
$dashletData['stic_BookingsDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'code' => array(
        'width' => '10%',
        'label' => 'LBL_CODE',
        'link' => true,
        'default' => true,
        'name' => 'code',
    ),
    'status' => array(
        'width' => '10%',
        'label' => 'LBL_STATUS',
        'link' => true,
        'default' => true,
        'name' => 'status',
    ),
    'start_date' => array(
        'type' => 'datetimecombo',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
    ),
    'end_date' => array(
        'type' => 'datetimecombo',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'end_date',
    ),
    'stic_bookings_contacts_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_BOOKINGS_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'STIC_BOOKINGS_CONTACTSCONTACTS_IDA',
        'width' => '10%',
        'default' => true,
    ),
    'stic_bookings_accounts_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_BOOKINGS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
        'id' => 'STIC_BOOKINGS_ACCOUNTSACCOUNTS_IDA',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'parent_name' => array(
        'type' => 'parent',
        'studio' => 'visible',
        'label' => 'LBL_FLEX_RELATE',
        'link' => true,
        'sortable' => false,
        'ACLTag' => 'PARENT',
        'dynamic_module' => 'PARENT_TYPE',
        'id' => 'PARENT_ID',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'width' => '10%',
        'default' => false,
        'name' => 'parent_name',
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
        'name' => 'description',
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
);
