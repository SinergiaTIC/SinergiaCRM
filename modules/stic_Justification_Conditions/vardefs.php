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

$dictionary['stic_Justification_Conditions'] = array(
    'table' => 'stic_justification_conditions',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => 255,
            'unified_search' => true,
            'required' => false,
            'vname' => 'LBL_NAME',
        ),
        'active' => array(
            'name' => 'active',
            'type' => 'bool',
            'default' => 1,
            'vname' => 'LBL_ACTIVE',
        ),
        'blocked' => array(
            'name' => 'blocked',
            'type' => 'bool',
            'default' => 0,
            'vname' => 'LBL_BLOCKED',
        ),
        'allocation_type' => array(
            'name' => 'allocation_type',
            'type' => 'enum',
            'options' => 'stic_allocations_types_list',
            'len' => 100,
            'vname' => 'LBL_ALLOCATION_TYPE',
            'required' => true,
        ),
        'max_allocable_percentage' => array(
            'name' => 'max_allocable_percentage',
            'type' => 'int',
            'vname' => 'LBL_MAX_ALLOCABLE_PERCENTAGE',
            'required' => true,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
        ),
        'max_allocable_percentage_grant' => array(
            'name' => 'max_allocable_percentage_grant',
            'type' => 'int',
            'vname' => 'LBL_MAX_ALLOCABLE_PERCENTAGE_GRANT',
            'required' => true,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
        ),
        'max_allocable_amount_grant' => array(
            'name' => 'max_allocable_amount_grant',
            'type' => 'decimal',
            'len' => 26,
            'precision' => 2,
            'vname' => 'LBL_MAX_ALLOCABLE_AMOUNT_GRANT',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
        ),
        'justified_percentage' => array(
            'name' => 'justified_percentage',
            'type' => 'decimal',
            'len' => 5,
            'precision' => 2,
            'vname' => 'LBL_JUSTIFIED_PERCENTAGE',
            'inline_edit' => false,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
        ),
        'justified_amount' => array(
            'name' => 'justified_amount',
            'type' => 'decimal',
            'len' => 26,
            'precision' => 2,
            'vname' => 'LBL_JUSTIFIED_AMOUNT',
            'inline_edit' => false,
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
        ),
        'stic_justification_conditions_stic_justifications' => array(
            'name' => 'stic_justification_conditions_stic_justifications',
            'type' => 'link',
            'relationship' => 'stic_justification_conditions_stic_justifications',
            'source' => 'non-db',
            'module' => 'stic_Justifications',
            'bean_name' => 'stic_Justifications',
            'side' => 'left',
            'vname' => 'LBL_STIC_JUSTIFICATION_CONDITIONS_STIC_JUSTIFICATIONS_FROM_STIC_JUSTIFICATIONS_TITLE',
        ),
        'opportunities_stic_justification_conditions' => array(
            'name' => 'opportunities_stic_justification_conditions',
            'type' => 'link',
            'relationship' => 'opportunities_stic_justification_conditions',
            'source' => 'non-db',
            'module' => 'Opportunities',
            'bean_name' => 'Opportunities',
            'side' => 'right',
            'vname' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE',
        ),
        'opportunit378funities_ida' => array(
            'name' => 'opportunit378funities_ida',
            'type' => 'id',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE_ID',
            'id_name' => 'opportunit378funities_ida',
            'link' => 'opportunities_stic_justification_conditions',
            'table' => 'opportunities',
            'module' => 'Opportunities',
            'rname' => 'name',
            'massupdate' => 0,
        ),
        'opportunities_stic_justification_conditions_name' => array(
            'name' => 'opportunities_stic_justification_conditions_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE',
            'save' => true,
            'id_name' => 'opportunit378funities_ida',
            'link' => 'opportunities_stic_justification_conditions',
            'table' => 'opportunities',
            'module' => 'Opportunities',
            'rname' => 'name',
            'required' => true,
        ),
        'stic_ledger_accounts_name' => array(
            'required' => false,
            'source' => 'non-db',
            'name' => 'stic_ledger_accounts_name',
            'vname' => 'LBL_STIC_LEDGER_ACCOUNTS',
            'type' => 'relate',
            'massupdate' => 1,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'len' => '255',
            'size' => '20',
            'id_name' => 'stic_ledger_accounts_ida',
            'ext2' => 'stic_Ledger_Accounts',
            'module' => 'stic_Ledger_Accounts',
            'rname' => 'name',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
        'stic_ledger_accounts_ida' => array(
            'name' => 'stic_ledger_accounts_ida',
            'type' => 'id',
            'source' => 'db',
            'vname' => 'LBL_STIC_LEDGER_ACCOUNTS_ID',
            'reportable' => false,
        ),
    ),
    'relationships' => array(
        'stic_justification_conditions_stic_justifications' => array(
            'lhs_module' => 'stic_Justification_Conditions',
            'lhs_table' => 'stic_justification_conditions',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Justifications',
            'rhs_table' => 'stic_justifications',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'stic_justification_conditions_stic_justifications_c',
            'join_key_lhs' => 'stic_justi13ccditions_ida',
            'join_key_rhs' => 'stic_justi2c00cations_idb',
        ),
        'opportunities_stic_justification_conditions' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Justification_Conditions',
            'rhs_table' => 'stic_justification_conditions',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'opportunities_stic_justification_conditions_c',
            'join_key_lhs' => 'opportunit378funities_ida',
            'join_key_rhs' => 'opportunita6e5ditions_idb',
        ),
    ),
    'indices' => array(
        array('name' => 'idx_stic_justification_conditions_pk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_stic_justification_conditions_name', 'type' => 'index', 'fields' => array('name')),
    ),
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Justification_Conditions', 'stic_Justification_Conditions', array('basic', 'assignable', 'security_groups'));