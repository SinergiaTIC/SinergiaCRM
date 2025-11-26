<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$stic_updates_index = [
    '2.1.0' => [
        'metadata' => [
            'version' => '2.1.0',
            'prev_version' => '2.0.0',
            'timestamp' => '2025-06-25 12:00:00',
            'inc_js_version' => true,
            'show_message' => true,
        ],
        'instructions' => [
            // 'repair',
            'SticUpdates/Migrations/20250130_feature_TrackerModule.sql',
            // 'sda_rebuild',
            'SticUpdates/Migrations/20250617_enhancement_glTranslations.sql',
        ],
        'finally' => [
            'repair',
            'css',
            'sda_rebuild',
            'cache_rebuild', // Borrar y regenerar (para los css de los formularios web)
        ],
    ],

    '2.0.0' => [
        'metadata' => [
            'version' => '2.0.0',
            'prev_version' => '1.8.0',
            'timestamp' => '2025-05-22 12:00:00',
            'inc_js_version' => true,
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
            'cache_rebuild',
        ],
    ],

    '1.8.0' => [
        'metadata' => [
            'version' => '1.8.0',
            'prev_version' => '1.7.5',
            'timestamp' => '2025-01-22 12:00:00',
            'inc_js_version' => true,
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
            'cache_rebuild',
        ],
    ],
];
