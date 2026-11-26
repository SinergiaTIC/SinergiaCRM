<?php
$searchdefs['stic_Portal_Login_Attempts'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'ip_address' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IP_ADDRESS', 'width' => '10%', 'name' => 'ip_address'),
        ),
        'advanced_search' => array(
            'ip_address' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IP_ADDRESS', 'width' => '10%', 'name' => 'ip_address'),
            'name' => array('type' => 'plainname', 'default' => true, 'label' => 'LBL_NAME', 'width' => '10%', 'name' => 'name'),
        ),
    ),
);
