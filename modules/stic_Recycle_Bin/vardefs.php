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

$dictionary['stic_Recycle_Bin'] = array(
    'table' => 'stic_recycle_bin',
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
        'recycle_module' => array(
            'name' => 'recycle_module',
            'vname' => 'LBL_RECYCLE_MODULE',
            'type' => 'varchar',
            'len' => 100,
        ),
        'recycle_record_id' => array(
            'name' => 'recycle_record_id',
            'vname' => 'LBL_RECYCLE_RECORD_ID',
            'type' => 'varchar',
            'len' => 36,
        ),
        'recycle_record_name' => array(
            'name' => 'recycle_record_name',
            'vname' => 'LBL_RECYCLE_RECORD_NAME',
            'type' => 'varchar',
            'len' => 255,
        ),
        'recycle_date_deleted' => array(
            'name' => 'recycle_date_deleted',
            'vname' => 'LBL_RECYCLE_DATE_DELETED',
            'type' => 'datetime',
        ),
        'recycle_user_deleted_id' => array(
            'name' => 'recycle_user_deleted_id',
            'vname' => 'LBL_RECYCLE_USER_DELETED',
            'type' => 'id',
        ),
        'recycle_user_deleted_name' => array(
            'name' => 'recycle_user_deleted_name',
            'vname' => 'LBL_RECYCLE_USER_DELETED',
            'type' => 'relate',
            'source' => 'non-db',
            'module' => 'Users',
            'rname' => 'user_name',
            'id_name' => 'recycle_user_deleted_id',
        ),
        'recycle_date_restored' => array(
            'name' => 'recycle_date_restored',
            'vname' => 'LBL_RECYCLE_DATE_RESTORED',
            'type' => 'datetime',
        ),
        'recycle_user_restored_id' => array(
            'name' => 'recycle_user_restored_id',
            'vname' => 'LBL_RECYCLE_USER_RESTORED',
            'type' => 'id',
        ),
        'recycle_user_restored_name' => array(
            'name' => 'recycle_user_restored_name',
            'vname' => 'LBL_RECYCLE_USER_RESTORED',
            'type' => 'relate',
            'source' => 'non-db',
            'module' => 'Users',
            'rname' => 'user_name',
            'id_name' => 'recycle_user_restored_id',
        ),
        'recycle_restored' => array(
            'name' => 'recycle_restored',
            'vname' => 'LBL_RECYCLE_RESTORED',
            'type' => 'bool',
            'default' => 0,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_stic_rb_module',
            'type' => 'index',
            'fields' => array('recycle_module'),
        ),
        array(
            'name' => 'idx_stic_rb_record_id',
            'type' => 'index',
            'fields' => array('recycle_record_id'),
        ),
        array(
            'name' => 'idx_stic_rb_restored',
            'type' => 'index',
            'fields' => array('recycle_restored'),
        ),
        array(
            'name' => 'idx_stic_rb_user_deleted',
            'type' => 'index',
            'fields' => array('recycle_user_deleted_id'),
        ),
    ),
);


