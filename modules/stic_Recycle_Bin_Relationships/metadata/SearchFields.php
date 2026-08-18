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

$searchFields['stic_Recycle_Bin_Relationships'] = array(
    'name' => array(
        'query_type' => 'default',
        'operator' => 'subquery',
        'subquery' => 'SELECT stic_recycle_bin_relationships.id FROM stic_recycle_bin_relationships WHERE stic_recycle_bin_relationships.name LIKE',
        'db_field' => array('id'),
        'vname' => 'LBL_NAME',
    ),
    'stic_recycle_bin_name' => array(
        'query_type' => 'default',
        'operator' => 'subquery',
        'subquery' => 'SELECT stic_recycle_bin_relationships.id FROM stic_recycle_bin_relationships, stic_recycle_bin WHERE stic_recycle_bin_relationships.stic_recycle_bin_id = stic_recycle_bin.id AND stic_recycle_bin.name LIKE',
        'db_field' => array('id'),
        'vname' => 'LBL_STIC_RECYCLE_BIN',
    ),
    'recycle_related_module' => array(
        'query_type' => 'default',
        'operator' => '=',
        'vname' => 'LBL_RECYCLE_RELATED_MODULE',
    ),
    'recycle_related_record_name' => array(
        'query_type' => 'default',
        'operator' => 'contains',
        'vname' => 'LBL_RECYCLE_RELATED_RECORD_NAME',
    ),
    'recycle_relationship_name' => array(
        'query_type' => 'default',
        'operator' => 'contains',
        'vname' => 'LBL_RECYCLE_RELATIONSHIP_NAME',
    ),
    'recycle_join_table' => array(
        'query_type' => 'default',
        'operator' => 'contains',
        'vname' => 'LBL_RECYCLE_JOIN_TABLE',
    ),
    'recycle_restored' => array(
        'query_type' => 'default',
        'operator' => '=',
        'vname' => 'LBL_RECYCLE_RESTORED',
    ),
);
