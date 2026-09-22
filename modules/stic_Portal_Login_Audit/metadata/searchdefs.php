<?php
$searchdefs['stic_Portal_Login_Audit'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'username' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_USERNAME', 'width' => '10%', 'name' => 'username'),
            'ip_address' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IP_ADDRESS', 'width' => '10%', 'name' => 'ip_address'),
            'failure_reason' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_FAILURE_REASON', 'width' => '10%', 'name' => 'failure_reason'),
            'auth_method' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_AUTH_METHOD', 'width' => '10%', 'name' => 'auth_method'),
        ),
        'advanced_search' => array(
            'username' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_USERNAME', 'width' => '10%', 'name' => 'username'),
            'ip_address' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IP_ADDRESS', 'width' => '10%', 'name' => 'ip_address'),
            'failure_reason' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_FAILURE_REASON', 'width' => '10%', 'name' => 'failure_reason'),
            'auth_method' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_AUTH_METHOD', 'width' => '10%', 'name' => 'auth_method'),
            'name' => array('type' => 'plainname', 'default' => true, 'label' => 'LBL_NAME', 'width' => '10%', 'name' => 'name'),
        ),
    ),
);
