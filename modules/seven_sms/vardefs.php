<?php

$dictionary['seven_sms'] = [
    'audited' => false,
    'comment' => 'SMS sent via seven',
    'duplicate_merge' => false,
    'fields' => [
        'account_id' => [
            'audited' => false,
            'comment' => 'The receiving account',
            'isnull' => true,
            'len' => '36',
            'name' => 'account_id',
            'type' => 'char',
            'vname' => 'LBL_ACCOUNT_ID',
        ],
        'contact_id' => [
            'audited' => false,
            'comment' => 'The receiving contact',
            'isnull' => true,
            'len' => '36',
            'name' => 'contact_id',
            'type' => 'char',
            'vname' => 'LBL_CONTACT_ID',
        ],
        'employee_id' => [
            'audited' => false,
            'comment' => 'The receiving employee',
            'isnull' => true,
            'len' => '36',
            'name' => 'employee_id',
            'type' => 'char',
            'vname' => 'LBL_EMPLOYEE_ID',
        ],
        'lead_id' => [
            'audited' => false,
            'comment' => 'The receiving lead',
            'isnull' => true,
            'len' => '36',
            'name' => 'lead_id',
            'type' => 'char',
            'vname' => 'LBL_LEAD_ID',
        ],
        'recipient' => [
            'audited' => false,
            'comment' => 'The message recipient(s)',
            'isnull' => false,
            'name' => 'recipient',
            'type' => 'text',
            'vname' => 'LBL_SEVEN_RECIPIENTS',
        ],
        'sender' => [
            'audited' => false,
            'comment' => 'The sender identifier',
            'isnull' => true,
            'name' => 'sender',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_FROM',
        ],
        'text' => [
            'audited' => false,
            'comment' => 'The message text',
            'isnull' => false,
            'name' => 'text',
            'type' => 'text',
            'vname' => 'LBL_SEVEN_SENDER',
        ],
    ],
    'table' => 'seven_sms',
    'unified_search' => false,
    'unified_search_default_enabled' => false,
    'optimistic_locking' => true,
];

if (!class_exists('VardefManager'))
    require_once 'include/SugarObjects/VardefManager.php';

VardefManager::createVardef(
    'seven_sms',
    'seven_sms',
    [
        'basic',
        'security_groups',
    ]
);
