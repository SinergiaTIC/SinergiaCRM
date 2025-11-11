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

$dictionary['stic_Allocation_Proposals'] = array(
    'table' => 'stic_allocation_proposals',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'proposal_status' => array(
            'required' => 1,
            'name' => 'proposal_status',
            'vname' => 'LBL_PROPOSAL_STATUS',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_allocation_proposals_status_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'proposal_date' => array(
            'required' => 1,
            'name' => 'proposal_date',
            'vname' => 'LBL_PROPOSAL_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 1,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
        ),
        'amount' => array(
            'required' => 0,
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'inline_edit' => 1,
        ),
        'proposal_type' => array(
            'required' => 0,
            'name' => 'proposal_type',
            'vname' => 'LBL_PROPOSAL_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_allocation_proposals_type_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'priority' => array(
            'required' => 0,
            'name' => 'priority',
            'vname' => 'LBL_PRIORITY',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_allocation_proposals_priority_list',
            'studio' => 'visible',
            'dependency' => 0,
        ),
        'notes' => array(
            'required' => 0,
            'name' => 'notes',
            'vname' => 'LBL_NOTES',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'text',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'rows' => '4',
            'cols' => 80,
        ),
        'approval_date' => array(
            'required' => 0,
            'name' => 'approval_date',
            'vname' => 'LBL_APPROVAL_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
        ),
        'approved_by' => array(
            'required' => 0,
            'name' => 'approved_by',
            'vname' => 'LBL_APPROVED_BY',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
        ),
    ),
    'indices' => array(
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => 1,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Allocation_Proposals', 'stic_Allocation_Proposals', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Allocation_Proposals']['fields']['name']['required'] = '1'; // Name is required in this module
$dictionary['stic_Allocation_Proposals']['fields']['name']['importable'] = 'required'; // Name is required for import
$dictionary['stic_Allocation_Proposals']['fields']['name']['audited'] = 1;
$dictionary['stic_Allocation_Proposals']['fields']['description']['rows'] = '2'; // Make textarea fields shorter