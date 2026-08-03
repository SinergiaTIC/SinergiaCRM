<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$searchdefs['stic_RecycleBin'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'recycle_record_name' => array(
                'name' => 'recycle_record_name',
                'default' => true,
            ),
            'recycle_module' => array(
                'name' => 'recycle_module',
                'default' => true,
            ),
        ),
        'advanced_search' => array(
            'recycle_record_name' => array('name' => 'recycle_record_name'),
            'recycle_module' => array('name' => 'recycle_module'),
            'recycle_date_deleted' => array('name' => 'recycle_date_deleted'),
            'recycle_restored' => array('name' => 'recycle_restored'),
            'recycle_user_deleted_id' => array(
                'name' => 'recycle_user_deleted_id',
                'type' => 'enum',
                'function' => array('name' => 'get_user_array'),
            ),
        ),
    ),
);
