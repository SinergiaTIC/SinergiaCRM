<?php

$dictionary['seven_sms_inbound'] = [
    'audited' => false,
    'comment' => 'SMS received via seven',
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
        'inbound_number' => [
            'audited' => false,
            'comment' => 'The inbound number',
            'isnull' => false,
            'name' => 'inbound_number',
            'type' => 'text',
            'vname' => 'LBL_SEVEN_RECIPIENTS',
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
        'msg_id' => [
            'audited' => false,
            'comment' => 'The gateway message ID',
            'isnull' => false,
            'name' => 'msg_id',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_ID',
        ],
        'msg_text' => [
            'audited' => false,
            'comment' => 'The message text',
            'isnull' => false,
            'name' => 'msg_text',
            'type' => 'text',
            'vname' => 'LBL_SEVEN_SENDER',
        ],
        'msg_time' => [
            'audited' => false,
            'comment' => 'The timestamp for receiving the message',
            'isnull' => false,
            'name' => 'msg_time',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_TIMESTAMP',
        ],
        'sender' => [
            'audited' => false,
            'comment' => 'The sender identifier',
            'isnull' => true,
            'name' => 'sender',
            'type' => 'varchar',
            'vname' => 'LBL_SEVEN_FROM',
        ],
    ],
    'table' => 'seven_sms_inbound',
    'unified_search' => false,
    'unified_search_default_enabled' => false,
    'optimistic_locking' => true,
];

if (!class_exists('VardefManager'))
    require_once 'include/SugarObjects/VardefManager.php';

VardefManager::createVardef(
    'seven_sms_inbound',
    'seven_sms_inbound',
    [
        'basic',
        'security_groups',
    ]
);
