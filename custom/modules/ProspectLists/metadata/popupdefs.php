<?php
$popupMeta = array(
    'moduleMain' => 'ProspectList',
    'varName' => 'PROSPECTLIST',
    'orderBy' => 'name',
    'whereClauses' => array(
        'list_type' => 'prospect_lists.list_type',
        0 => 'prospectlists.0',
        'current_user_only' => 'prospectlists.current_user_only',
        'assigned_user_name' => 'prospectlists.assigned_user_name',
    ),
    'searchInputs' => array(

    ),
    'searchdefs' => array(
        'assigned_user_name' => array(
            'link' => true,
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'name' => 'assigned_user_name',
        ),
        0 => array(
            'name' => 'name',
            'label' => 'LBL_PROSPECT_LIST_NAME',
            'width' => '10%',
        ),
        'list_type' => array(
            'name' => 'list_type',
            'label' => 'LBL_LIST_TYPE',
            'type' => 'enum',
            'width' => '10%',
        ),
        'current_user_only' => array(
            'name' => 'current_user_only',
            'label' => 'LBL_CURRENT_USER_FILTER',
            'type' => 'bool',
            'width' => '10%',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'width' => '25',
            'label' => 'LBL_LIST_PROSPECT_LIST_NAME',
            'link' => true,
            'default' => true,
        ),
        'LIST_TYPE' => array(
            'width' => '15',
            'label' => 'LBL_LIST_TYPE_LIST_NAME',
            'default' => true,
        ),
        'DESCRIPTION' => array(
            'width' => '50',
            'label' => 'LBL_LIST_DESCRIPTION',
            'default' => true,
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '10',
            'label' => 'LBL_LIST_ASSIGNED_USER',
            'module' => 'Employees',
            'default' => true,
        ),
    ),
);
