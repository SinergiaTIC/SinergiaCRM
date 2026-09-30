<?php
$searchdefs['stic_Portal_Magic_Rate_Limit'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'identifier' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IDENTIFIER', 'width' => '10%', 'name' => 'identifier'),
            'identifier_type' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IDENTIFIER_TYPE', 'width' => '10%', 'name' => 'identifier_type'),
        ),
        'advanced_search' => array(
            'identifier' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IDENTIFIER', 'width' => '10%', 'name' => 'identifier'),
            'identifier_type' => array('type' => 'varchar', 'default' => true, 'label' => 'LBL_IDENTIFIER_TYPE', 'width' => '10%', 'name' => 'identifier_type'),
            'name' => array('type' => 'plainname', 'default' => true, 'label' => 'LBL_NAME', 'width' => '10%', 'name' => 'name'),
        ),
    ),
);
