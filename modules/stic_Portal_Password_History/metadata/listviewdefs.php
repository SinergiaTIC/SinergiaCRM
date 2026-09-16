<?php
$module_name = 'stic_Portal_Password_History';
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
    'PASSWORD_HASH' => array(
        'type' => 'varchar',
        'label' => 'LBL_PASSWORD_HASH',
        'width' => '12%',
        'default' => true,
    ),
);
