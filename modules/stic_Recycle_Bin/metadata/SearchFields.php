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

$searchFields['stic_Recycle_Bin'] = array(
    'recycle_record_name' => array('query_type' => 'default'),
    'recycle_module' => array('query_type' => 'default'),
    'recycle_date_deleted' => array(
        'query_type' => 'default',
        'operator' => '=',
        'db_field' => array('recycle_date_deleted'),
    ),
    'recycle_restored' => array('query_type' => 'default'),
    'recycle_user_deleted_id' => array(
        'query_type' => 'default',
        'operator' => '=',
        'db_field' => array('recycle_user_deleted_id'),
    ),
);
