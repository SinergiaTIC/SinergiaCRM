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

$module_name = 'OAuth2Clients';

$viewdefs[$module_name]['DetailView'] = [
    'templateMeta' => [
        'maxColumns' => '1',
        'widths' => [
            ['label' => '30', 'field' => '70'],
        ],
    ],
    'panels' => [
        'default' =>
            [
                0 =>
                    [
                        0 => 'name',
                    ],
                1 =>
                    [
                        0 => 'redirect_url',
                    ],
                2 =>
                    [
                        0 => 'is_confidential',
                    ],
                3 =>
                    [
                        0 => 'id',
                    ],
                4 =>
                    [
                        0 => 'allowed_grant_type',
                    ],
                5 =>
                    [
                        0 =>
                            [
                                'name' => 'duration_amount',
                            ],
                        1 =>
                            [
                                'name' => 'duration_unit',
                            ],
                    ],
            ],
        'LBL_PANEL_ASSIGNMENT' =>
            [
                0 =>
                    [
                        0 =>
                            [
                                'name' => 'date_entered',
                                'customCode' =>
                                    '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                            ],
                        1 =>
                            [
                                'name' => 'date_modified',
                                'label' => 'LBL_DATE_MODIFIED',
                                'customCode' =>
                                    '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                            ],
                    ],
            ],
    ],
];
