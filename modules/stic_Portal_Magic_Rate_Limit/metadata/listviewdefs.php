<?php
$module_name = 'stic_Portal_Magic_Rate_Limit';
$listViewDefs [$module_name] =
array (
    'NAME' => array(
        'width' => '15%',
        'label' => 'LBL_NAME',
        'default' => false,
        'link' => true,
    ),
    'IDENTIFIER' => array(
        'type' => 'varchar',
        'label' => 'LBL_IDENTIFIER',
        'width' => '12%',
        'default' => true,
    ),
    'IDENTIFIER_TYPE' => array(
        'type' => 'varchar',
        'label' => 'LBL_IDENTIFIER_TYPE',
        'width' => '12%',
        'default' => true,
    ),
    'WINDOW_START' => array(
        'type' => 'datetime',
        'label' => 'LBL_' + 'WINDOW_START',
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
