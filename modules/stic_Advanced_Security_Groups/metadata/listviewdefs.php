<?php
$module_name = 'stic_Advanced_Security_Groups';
$listViewDefs[$module_name] =
array(
    'NAME' => array(
        'width' => '32%',
        'label' => 'LBL_NAME',
        'default' => false,
        'link' => true,
        'type' => 'readonly',
    ),
    'NAME_LBL' => array(
        'width' => '32%',
        'label' => 'LBL_NAME_LBL',
        'default' => true,
        'link' => true,

    ),
    'ACTIVE' => array(
        'type' => 'bool',
        'align' => 'center',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ACTIVE',
        'width' => '10%',
    ),
    'INHERIT_ASSIGNED' => array(
        'type' => 'bool',
        'align' => 'center',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_INHERIT_ASSIGNED',
        'width' => '10%',
    ),
    'INHERIT_CREATOR' => array(
        'type' => 'bool',
        'align' => 'center',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_INHERIT_CREATOR',
        'width' => '10%',
    ),
    'INHERIT_PARENT' => array(
        'type' => 'bool',
        'align' => 'center',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_INHERIT_PARENT',
        'width' => '10%',
    ),
    'INHERIT_FROM_MODULES' => array(
        'type' => 'multienum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_INHERIT_FROM_MODULES',
        'width' => '10%',
    ),
    'NON_INHERIT_FROM_SECURITY_GROUPS' => array(
        'type' => 'multienum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_NON_INHERIT_FROM_SECURITY_GROUPS',
        'width' => '10%',
    ),
    // 'CREATED_BY_NAME' => array(
    //     'type' => 'relate',
    //     'link' => true,
    //     'label' => 'LBL_CREATED',
    //     'id' => 'CREATED_BY',
    //     'width' => '10%',
    //     'default' => false,
    // ),
    // 'MODIFIED_BY_NAME' => array(
    //     'type' => 'relate',
    //     'link' => true,
    //     'label' => 'LBL_MODIFIED_NAME',
    //     'id' => 'MODIFIED_USER_ID',
    //     'width' => '10%',
    //     'default' => false,
    // ),
    // 'DATE_MODIFIED' => array(
    //     'type' => 'datetime',
    //     'label' => 'LBL_DATE_MODIFIED',
    //     'width' => '10%',
    //     'default' => true,
    // ),
    // 'DATE_ENTERED' => array(
    //     'type' => 'datetime',
    //     'label' => 'LBL_DATE_ENTERED',
    //     'width' => '10%',
    //     'default' => true,
    // ),
);
