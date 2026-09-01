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

$dictionary['stic_Recycle_Bin_Relationships'] = array(
    'table' => 'stic_recycle_bin_relationships',
    'audited' => false,
    'inline_edit' => false,
    'duplicate_merge' => false,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 255,
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'vname' => 'LBL_MODIFIED',
            'type' => 'id',
        ),
        'created_by' => array(
            'name' => 'created_by',
            'vname' => 'LBL_CREATED',
            'type' => 'id',
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'default' => 0,
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'vname' => 'LBL_ASSIGNED_TO_ID',
            'type' => 'id',
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'relate',
            'source' => 'non-db',
            'module' => 'Users',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
        ),
        'stic_recycle_bin_id' => array(
            'name' => 'stic_recycle_bin_id',
            'vname' => 'LBL_STIC_RECYCLE_BIN_ID',
            'type' => 'id',
        ),
        'stic_recycle_bin_name' => array(
            'name' => 'stic_recycle_bin_name',
            'vname' => 'LBL_STIC_RECYCLE_BIN',
            'type' => 'relate',
            'source' => 'non-db',
            'module' => 'stic_Recycle_Bin',
            'rname' => 'name',
            'id_name' => 'stic_recycle_bin_id',
            'link' => 'stic_recycle_bin_recycle_bin_relationships',
        ),
        'stic_recycle_bin_recycle_bin_relationships' => array(
            'name' => 'stic_recycle_bin_recycle_bin_relationships',
            'type' => 'link',
            'relationship' => 'stic_recycle_bin_recycle_bin_relationships',
            'source' => 'non-db',
            'module' => 'stic_Recycle_Bin',
            'bean_name' => 'stic_Recycle_Bin',
            'vname' => 'LBL_STIC_RECYCLE_BIN',
        ),
        'recycle_record_id' => array(
            'name' => 'recycle_record_id',
            'vname' => 'LBL_RECYCLE_RECORD_ID',
            'type' => 'varchar',
            'len' => 36,
        ),
        'recycle_relationship_name' => array(
            'name' => 'recycle_relationship_name',
            'vname' => 'LBL_RECYCLE_RELATIONSHIP_NAME',
            'type' => 'varchar',
            'len' => 255,
        ),
        'recycle_join_table' => array(
            'name' => 'recycle_join_table',
            'vname' => 'LBL_RECYCLE_JOIN_TABLE',
            'type' => 'varchar',
            'len' => 255,
        ),
        'recycle_related_module' => array(
            'name' => 'recycle_related_module',
            'vname' => 'LBL_RECYCLE_RELATED_MODULE',
            'type' => 'varchar',
            'len' => 100,
        ),
        'recycle_related_record_id' => array(
            'name' => 'recycle_related_record_id',
            'vname' => 'LBL_RECYCLE_RELATED_RECORD_ID',
            'type' => 'varchar',
            'len' => 36,
        ),
        'recycle_related_record_name' => array(
            'name' => 'recycle_related_record_name',
            'vname' => 'LBL_RECYCLE_RELATED_RECORD_NAME',
            'type' => 'varchar',
            'len' => 255,
        ),
        'recycle_restored' => array(
            'name' => 'recycle_restored',
            'vname' => 'LBL_RECYCLE_RESTORED',
            'type' => 'bool',
            'default' => 0,
        ),
        'recycle_join_lhs_key' => array(
            'name' => 'recycle_join_lhs_key',
            'vname' => 'LBL_RECYCLE_JOIN_LHS_KEY',
            'type' => 'varchar',
            'len' => 100,
        ),
        'recycle_join_rhs_key' => array(
            'name' => 'recycle_join_rhs_key',
            'vname' => 'LBL_RECYCLE_JOIN_RHS_KEY',
            'type' => 'varchar',
            'len' => 100,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_stic_rbr_recycle_bin_id',
            'type' => 'index',
            'fields' => array('stic_recycle_bin_id'),
        ),
        array(
            'name' => 'idx_stic_rbr_record_id',
            'type' => 'index',
            'fields' => array('recycle_record_id'),
        ),
        array(
            'name' => 'idx_stic_rbr_related_id',
            'type' => 'index',
            'fields' => array('recycle_related_record_id'),
        ),
        array(
            'name' => 'idx_stic_rbr_restored',
            'type' => 'index',
            'fields' => array('recycle_restored'),
        ),
    ),
    'relationships' => array(
        'stic_recycle_bin_recycle_bin_relationships' => array(
            'lhs_module' => 'stic_Recycle_Bin',
            'lhs_table' => 'stic_recycle_bin',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Recycle_Bin_Relationships',
            'rhs_table' => 'stic_recycle_bin_relationships',
            'rhs_key' => 'stic_recycle_bin_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
);
