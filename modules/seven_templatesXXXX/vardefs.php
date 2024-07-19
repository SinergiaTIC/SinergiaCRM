<?php

$dictionary['seven_templates'] = [
    'audited' => false,
    'comment' => 'Templates for messaging via seven',
    'duplicate_merge' => false,
    'fields' => [
        'label' => [
            'audited' => false,
            'comment' => 'The message label',
            'isnull' => false,
            'name' => 'label',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_LABEL',
        ],
        'text' => [
            'audited' => false,
            'comment' => 'The message content',
            'isnull' => false,
            'name' => 'text',
            'type' => 'text',
            'vname' => 'LBL_SEVEN_TEXT',
        ],
        'type' => [
            'audited' => false,
            'comment' => 'The module type',
            'isnull' => false,
            'name' => 'type',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_MODULE_TYPE',
        ],
    ],
/*    'indices' => [
        [
            'fields' => ['label'],
        ],
        'name' => 'idx_seven_label',
        'type' => 'unique',
    ],*/
    'table' => 'seven_templates',
    'unified_search' => false,
    'unified_search_default_enabled' => false,
    'optimistic_locking' => true,
];

if (!class_exists('VardefManager'))
    require_once 'include/SugarObjects/VardefManager.php';

VardefManager::createVardef(
    'seven_templates',
    'seven_templates',
    [
        'basic',
        'security_groups',
    ]
);
