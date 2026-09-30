<?php
$module_name = 'stic_Portal_Login_Attempts';
$listViewDefs [$module_name] =
array (
    'NAME' => array(
        'width' => '15%',
        'label' => 'LBL_NAME',
        'default' => false,
        'link' => true,
    ),
    'IP_ADDRESS' => array(
        'type' => 'varchar',
        'label' => 'LBL_IP_ADDRESS',
        'width' => '12%',
        'default' => true,
    ),
    'FAILED_ATTEMPTS' => array(
        'type' => 'int',
        'label' => 'LBL_FAILED_ATTEMPTS',
        'width' => '12%',
        'default' => true,
    ),
    'LOCKED_UNTIL' => array(
        'type' => 'datetime',
        'label' => 'LBL_' + 'LOCKED_UNTIL',
        'width' => '12%',
        'default' => true,
    ),
    'LAST_ATTEMPT' => array(
        'type' => 'datetime',
        'label' => 'LBL_' + 'LAST_ATTEMPT',
        'width' => '12%',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '15%',
        'default' => true,
    ),
);
