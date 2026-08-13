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

$mod_strings = array(
    // Module info
    'LBL_MODULE_NAME' => 'Recycle Bin',
    'LBL_MODULE_TITLE' => 'Recycle Bin',
    'LBL_HOMEPAGE_TITLE' => 'Recycle Bin',

    // General
    'LBL_SEARCH_FORM_TITLE' => 'Search Recycle Bin',
    'LBL_LIST_FORM_TITLE' => 'Recycle Bin List',
    'LBL_NEW_FORM_TITLE' => 'New Recycle Bin Entry',
    'LNK_LIST' => 'Recycle Bin',

    // Panels
    'LBL_DEFAULT_PANEL' => 'Deleted Record Information',

    // Field labels
    'LBL_NAME' => 'Record Name',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Date Created',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_MODIFIED' => 'Modified By',
    'LBL_CREATED' => 'Created By',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_DELETED' => 'Deleted',
    'LBL_ASSIGNED_TO' => 'Assigned To',
    'LBL_ASSIGNED_TO_ID' => 'Assigned User ID',
    'LBL_MODIFIED_USER' => 'Modified User',
    'LBL_CREATED_USER' => 'Created User',
    'LBL_SECURITYGROUPS' => 'Security Groups',

    // Custom fields
    'LBL_RECYCLE_MODULE' => 'Module',
    'LBL_RECYCLE_RECORD_ID' => 'Record ID',
    'LBL_RECYCLE_RECORD_NAME' => 'Record Name',
    'LBL_RECYCLE_DATE_DELETED' => 'Deletion Date',
    'LBL_RECYCLE_USER_DELETED' => 'Deleted By',
    'LBL_RECYCLE_DATE_RESTORED' => 'Restore Date',
    'LBL_RECYCLE_USER_RESTORED' => 'Restored By',
    'LBL_RECYCLE_RESTORED' => 'Restored',

    // Relationships submodule
    'LBL_RECYCLEBIN' => 'Recycle Bin',
    'LBL_RECYCLEBIN_ID' => 'Recycle Bin ID',
    'LBL_RECYCLEBIN_NAME' => 'Recycle Bin',
    'LBL_RECYCLE_RELATIONSHIP_NAME' => 'Relationship Name',
    'LBL_RECYCLE_JOIN_TABLE' => 'Join Table',
    'LBL_RECYCLE_RELATED_MODULE' => 'Related Module',
    'LBL_RECYCLE_RELATED_RECORD_ID' => 'Related Record ID',
    'LBL_RECYCLE_RELATED_RECORD_NAME' => 'Related Record Name',
    'LBL_RECYCLE_BIN_RELATIONSHIPS' => 'Relationships at Time of Deletion',
    'LBL_RECYCLE_JOIN_LHS_KEY' => 'LHS Key',
    'LBL_RECYCLE_JOIN_RHS_KEY' => 'RHS Key',

    // List view
    'LBL_RELATIONSHIP_COUNT' => 'Relationships',

    // Actions
    'LBL_RESTORE' => 'Restore',
    'LBL_RESTORE_RECORD' => 'Restore Record',
    'LBL_RESTORE_CONFIRM' => 'Are you sure you want to restore this record?',
    'LBL_MASS_RESTORE' => 'Restore Selected',
    'LBL_MASS_RESTORE_CONFIRM' => 'Are you sure you want to restore the selected records?',
    'LBL_MASS_RESTORE_SUCCESS' => '%d records restored successfully.',
    'LBL_MASS_RESTORE_PARTIAL' => '%d records restored, %d skipped (already restored or invalid).',
    'LBL_MASS_RESTORE_ALL_ALREADY' => 'All %d selected records have already been restored.',
    'LBL_MASS_RESTORE_ALL_ALREADY_RESTORED' => 'The selected records have already been restored.',
    'LBL_MASS_RESTORE_MIXED_CONFIRM' => 'Some of the selected records have already been restored. Continue with the remaining records?',
    'LBL_NO_RECORDS_SELECTED' => 'No records selected.',
    'LBL_RESTORE_SUCCESS' => 'Record restored successfully.',
    'LBL_RESTORE_FAIL' => 'Failed to restore record.',
    'LBL_RESTORE_INVALID_ID' => 'Invalid record identifier.',
    'LBL_RESTORE_NOT_FOUND' => 'Record not found.',
    'LBL_RESTORE_ALREADY' => 'Record has already been restored.',
    'LBL_RESTORE_NO_TABLE' => 'Original record table not found.',
    'LBL_RESTORE_RESULTS' => 'Restore Results',
    'LBL_RESTORE_RECORDS_RESTORED' => 'records restored successfully.',
    'LBL_RESTORE_RECORDS_FAILED' => 'records failed to restore.',
    'LBL_RESTORE_RELATIONS_RESTORED' => 'relationships restored.',
    'LBL_RESTORE_RELATIONS_SKIPPED' => 'relationships skipped (related records no longer available).',

    // Generic
    'LBL_YES' => 'Yes',
    'LBL_NO' => 'No',
    'LBL_NO_RELATIONSHIPS' => 'No relationships recorded for this record.',
    'LBL_NO_ACCESS' => 'You do not have permission to access this section.',

    // Module list
    'LBL_RECYCLE_MODULE_LIST' => 'All Modules',
);
