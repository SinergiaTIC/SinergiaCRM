<?php
$dashletData['FP_Event_LocationsDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'address_postalcode' => array(
        'default' => '',
    ),
    'address_city' => array(
        'default' => '',
    ),
    'address_state' => array(
        'default' => '',
    ),
    'address_country' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
);
$dashletData['FP_Event_LocationsDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'capacity' => array(
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_CAPACITY',
        'width' => '10%',
    ),
    'address' => array(
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_ADDRESS',
        'width' => '10%',
    ),
    'address_postalcode' => array(
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_ADDRESS_POSTALCODE',
        'width' => '10%',
    ),
    'address_city' => array(
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_ADDRESS_CITY',
        'width' => '10%',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'stic_address_county_c' => array(
        'type' => 'enum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_STIC_ADDRESS_COUNTY',
        'width' => '10%',
    ),
    'address_state' => array(
        'type' => 'enum',
        'default' => false,
        'label' => 'LBL_ADDRESS_STATE',
        'width' => '10%',
    ),
    'stic_address_region_c' => array(
        'type' => 'enum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_STIC_ADDRESS_REGION',
        'width' => '10%',
    ),
    'address_country' => array(
        'type' => 'varchar',
        'default' => false,
        'label' => 'LBL_ADDRESS_COUNTRY',
        'width' => '10%',
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
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
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
        'name' => 'date_entered',
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
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
);
