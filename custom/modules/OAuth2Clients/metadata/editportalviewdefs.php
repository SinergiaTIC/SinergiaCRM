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

$viewdefs[$module_name]['EditView'] = [
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
                        0 =>
                            [
                                'name' => 'new_secret',
                                'label' => 'LBL_SECRET_HASHED',
                                'customCode' => '<input type="password" name="new_secret" id="new_secret" placeholder="{$MOD.LBL_LEAVE_BLANK}" size="30">'
                                    . '<input type="hidden" name="allowed_grant_type" id="allowed_grant_type" value="portal_authorization_code">'
                                    . '<br /><span>{$MOD.LBL_REMEMBER_SECRET}</span>',
                            ],
                    ],
                2 =>
                    [
                        0 => 'redirect_url',
                    ],
                3 =>
                    [
                        0 => 'is_confidential',
                    ],
                4 =>
                    [
                        0 => 'assigned_user_name',
                    ],
            ],
    ],
];
