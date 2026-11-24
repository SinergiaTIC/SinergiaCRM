<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'OAuth2Clients';
$listViewDefs[$module_name] = [
    'name' => [
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ],
    'allowed_grant_type' => [
        'label' => 'LBL_ALLOWED_GRANT_TYPE',
        'default' => true,
    ],
    'is_confidential' => [
        'label' => 'LBL_IS_CONFIDENTIAL',
        'default' => true,
    ],
    'redirect_url' => [
        'label' => 'LBL_REDIRECT_URL',
        'default' => true,
    ],
    'assigned_user_name' => [
        'label' => 'LBL_USER',
        'module' => 'Users',
        'id' => 'USER_ID',
        'default' => true,
        'sortable' => true,
    ],
];
