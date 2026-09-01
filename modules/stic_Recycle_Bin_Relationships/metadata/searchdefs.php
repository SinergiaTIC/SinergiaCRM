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

$searchdefs['stic_Recycle_Bin_Relationships'] = array(
    'layout' => array(
        'basic_search' => array(
            'name' => array('name' => 'name', 'type' => 'name'),
            'recycle_related_module' => array('name' => 'recycle_related_module', 'type' => 'varchar'),
            'recycle_related_record_name' => array('name' => 'recycle_related_record_name', 'type' => 'varchar'),
        ),
        'advanced_search' => array(
            'name' => array('name' => 'name', 'type' => 'name'),
            'stic_recycle_bin_name' => array('name' => 'stic_recycle_bin_name', 'type' => 'relate'),
            'recycle_related_module' => array('name' => 'recycle_related_module', 'type' => 'varchar'),
            'recycle_related_record_name' => array('name' => 'recycle_related_record_name', 'type' => 'varchar'),
            'recycle_relationship_name' => array('name' => 'recycle_relationship_name', 'type' => 'varchar'),
            'recycle_join_table' => array('name' => 'recycle_join_table', 'type' => 'varchar'),
            'recycle_restored' => array('name' => 'recycle_restored', 'type' => 'bool'),
        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);
