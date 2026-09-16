<?php
$searchdefs['stic_Portal_Password_History'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'password_hash' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_PASSWORD_HASH', 'width' => '10%', 'name' => 'password_hash'),
        ),
        'advanced_search' => array(
            'password_hash' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_PASSWORD_HASH', 'width' => '10%', 'name' => 'password_hash'),
            'name' => array('type' => 'plainname', 'default' => true, 'label' => 'LBL_NAME', 'width' => '10%', 'name' => 'name'),
        ),
    ),
);
