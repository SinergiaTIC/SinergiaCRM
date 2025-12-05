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

$dictionary['stic_Ledger_Accounts'] = array(
    'table' => 'stic_ledger_accounts',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'active' => array(
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'default' => '1',
            'reportable' => true,
            'audited' => true,
            'importable' => true,
            'massupdate' => true,
            'inline_edit' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'studio' => 'visible',
        ),
        'ledger_group' => array(
            'name' => 'ledger_group',
            'vname' => 'LBL_GROUP',
            'type' => 'enum',
            'options' => 'stic_ledger_groups_list',
            'len' => 100,
            'required' => true,
            'reportable' => true,
            'audited' => true,
            'importable' => true,
            'massupdate' => false,
            'inline_edit' => false,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'studio' => 'visible',
        ),
        'subgroup' => array(
            'name' => 'subgroup',
            'vname' => 'LBL_SUBGROUP',
            'type' => 'dynamicenum',
            'dbtype' => 'enum',
            'options' => 'stic_ledger_subgroups_list',
            'parentenum' => 'ledger_group',
            'len' => 100,
            'required' => true,
            'reportable' => true,
            'audited' => true,
            'importable' => true,
            'massupdate' => false,
            'inline_edit' => false,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'studio' => 'visible',
        ),
        'account' => array(
            'name' => 'account',
            'vname' => 'LBL_ACCOUNT',
            'type' => 'dynamicenum',
            'dbtype' => 'enum',
            'options' => 'stic_ledger_accounts_list',
            'parentenum' => 'subgroup',
            'len' => 100,
            'required' => true,
            'reportable' => true,
            'audited' => true,
            'importable' => true,
            'massupdate' => false,
            'inline_edit' => false,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'studio' => 'visible',
        ),
        'subaccount' => array(
            'name' => 'subaccount',
            'vname' => 'LBL_SUBACCOUNT',
            'type' => 'dynamicenum',
            'dbtype' => 'enum',
            'options' => 'stic_ledger_subaccounts_list',
            'parentenum' => 'account',
            'len' => 100,
            'required' => false,
            'reportable' => true,
            'audited' => true,
            'importable' => true,
            'massupdate' => false,
            'inline_edit' => false,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'studio' => 'visible',
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
VardefManager::createVardef('stic_Ledger_Accounts', 'stic_Ledger_Accounts', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Ledger_Accounts']['fields']['name']['required'] = false; // Name is auto-generated in this module
$dictionary['stic_Ledger_Accounts']['fields']['name']['importable'] = 'true'; // Name is not required for import (auto-generated)
$dictionary['stic_Ledger_Accounts']['fields']['name']['audited'] = 1;
$dictionary['stic_Ledger_Accounts']['fields']['description']['rows'] = '2'; // Make textarea fields shorter