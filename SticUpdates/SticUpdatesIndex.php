<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$stic_updates_index = [
    '2.1.0' => [
        'metadata' => [
            'prev_version' => '2.0.0',
            'timestamp' => '2025-06-25 12:00:00',
            'jv_version' => '16',
            'show_message' => true,
        ],
        'instructions' => [
            // 'repair',
            'SticUpdates/Migrations/20250130_feature_TrackerModule.sql',
            // 'sda_rebuild',
            'SticUpdates/Migrations/20250617_enhancement_glTranslations.sql'
        ],
        'finally' => [
            'repair',
            'css',
            'sda_rebuild',
            'delete_cache',
        ],
    ],

    '2.0.0' => [
        'metadata' => [
            'prev_version' => '1.8.0',
            'timestamp' => '2025-05-22 12:00:00',
            'jv_version' => '15',
            'show_message' => true,
        ],
        'instructions' => [
            'SticUpdates/Scripts/CopyReplyInfoFromInboundToOutbound.php',
            'SticUpdates/Scripts/MigrateInboundEmailsToSuiteCRM7.14.6.php',
            'SticUpdates/Migrations/20250507_enhancement_newFieldsIncorporaAPI.sql',
        ],
        'finally' => [
            'repair',
            // 'css',
            'sda_rebuild',
            'delete_cache',
        ],
    ],

    '1.8.0' => [
        'metadata' => [
            'prev_version' => '1.7.5',
            'timestamp' => '2025-01-22 12:00:00',
            'jv_version' => '14',
            'show_message' => true,
        ],
        'instructions' => [
            // 'repair',
            'SticUpdates/Scripts/ConvertSuiteCRMMenuToAdvancedMenu.php',
        ],
        'finally' => [
            'repair',
            'css',
            'sda_rebuild',
            'delete_cache',
        ],
    ],
];
