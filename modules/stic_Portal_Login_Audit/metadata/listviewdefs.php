<?php
$module_name = 'stic_Portal_Login_Audit';
$listViewDefs [$module_name] =
array (
    'NAME' => array(
        'width' => '15%',
        'label' => 'LBL_NAME',
        'default' => false,
        'link' => true,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '15%',
        'default' => true,
    ),
    'USERNAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_USERNAME',
        'width' => '12%',
        'default' => true,
    ),
    'IP_ADDRESS' => array(
        'type' => 'varchar',
        'label' => 'LBL_IP_ADDRESS',
        'width' => '12%',
        'default' => true,
    ),
    'SUCCESS' => array(
        'type' => 'bool',
        'label' => 'LBL_SUCCESS',
        'width' => '12%',
        'default' => true,
    ),
    'FAILURE_REASON' => array(
        'type' => 'varchar',
        'label' => 'LBL_FAILURE_REASON',
        'width' => '12%',
        'default' => true,
    ),
    'AUTH_METHOD' => array(
        'type' => 'varchar',
        'label' => 'LBL_AUTH_METHOD',
        'width' => '12%',
        'default' => true,
    ),
);
