<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

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
