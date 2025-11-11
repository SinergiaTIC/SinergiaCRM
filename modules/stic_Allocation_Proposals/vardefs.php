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
        'active' => array(
            'required' => 0,
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'bool',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'inline_edit' => 1,
            'default' => '1',
        ),
        'type' => array(
            'required' => 0,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
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
            'options' => 'stic_allocations_types_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'payment_amount_field' => array(
            'required' => 0,
            'name' => 'payment_amount_field',
            'vname' => 'LBL_PAYMENT_AMOUNT_FIELD',
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
            'options' => 'stic_allocations_amount_fields_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'percentage' => array(
            'required' => 0,
            'name' => 'percentage',
            'vname' => 'LBL_PERCENTAGE',
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
            'len' => 5,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'inline_edit' => 1,
        ),
        'hours' => array(
            'required' => 0,
            'name' => 'hours',
            'vname' => 'LBL_HOURS',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'int',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 11,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'inline_edit' => 1,
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