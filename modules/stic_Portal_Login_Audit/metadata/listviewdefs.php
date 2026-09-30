<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'stic_Portal_Login_Audit';
$listViewDefs[$module_name] = array(
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '12%',
        'default' => true,
        'sortable' => true,
    ),
    'USERNAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_USERNAME',
        'width' => '13%',
        'default' => true,
        'sortable' => true,
    ),
    'PARENT_TYPE' => array(
        'type' => 'parent_type',
        'label' => 'LBL_PARENT_TYPE',
        'width' => '7%',
        'default' => true,
        'sortable' => true,
    ),
    'OAUTH_CLIENT_NAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_OAUTH_CLIENT_NAME',
        'width' => '11%',
        'default' => true,
        'sortable' => true,
    ),
    'OAUTH_CLIENT_ID' => array(
        'type' => 'varchar',
        'label' => 'LBL_OAUTH_CLIENT_ID',
        'width' => '13%',
        'default' => true,
        'sortable' => true,
    ),
    'IP_ADDRESS' => array(
        'type' => 'varchar',
        'label' => 'LBL_IP_ADDRESS',
        'width' => '8%',
        'default' => true,
        'sortable' => true,
    ),
    'SUCCESS' => array(
        'type' => 'bool',
        'label' => 'LBL_SUCCESS',
        'width' => '7%',
        'default' => true,
        'sortable' => true,
    ),
    'FAILURE_REASON' => array(
        'type' => 'varchar',
        'label' => 'LBL_FAILURE_REASON',
        'width' => '10%',
        'default' => true,
        'sortable' => true,
    ),
    'AUTH_METHOD' => array(
        'type' => 'varchar',
        'label' => 'LBL_AUTH_METHOD',
        'width' => '8%',
        'default' => true,
        'sortable' => true,
    ),
    'USER_AGENT' => array(
        'type' => 'varchar',
        'label' => 'LBL_USER_AGENT',
        'width' => '11%',
        'default' => true,
        'customCode' => '{$USER_AGENT|escape|truncate:80}',
    ),
);
