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

$dictionary['stic_Organizational_Environment'] = array(
    'table' => 'stic_organizational_environment',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  'relationship_type' => 
  array (
    'required' => true,
    'name' => 'relationship_type',
    'vname' => 'LBL_RELATIONSHIP_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_organizational_environment_relationships_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'end_date' => 
  array (
    'required' => false,
    'name' => 'end_date',
    'vname' => 'LBL_END_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
  ),
  'start_date' => 
  array (
    'required' => true,
    'name' => 'start_date',
    'vname' => 'LBL_START_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
    'display_default' => 'now',
  ),
  'reference_account' => 
  array (
    'required' => false,
    'name' => 'reference_account',
    'vname' => 'LBL_REFERENCE_ACCOUNT',
    'type' => 'bool',
    'massupdate' => 0,
    'default' => '0',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'active' => 
  array (
    'required' => false,
    'name' => 'active',
    'vname' => 'LBL_ACTIVE',
    'type' => 'bool',
    'massupdate' => 0,
    'default' => '1',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);

$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accounts_1"] = array (
  'name' => 'stic_organizational_environment_accounts_1',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_accounts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'stic_organizational_environment_accounts_1accounts_ida',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accounts_1_name"] = array (
  'name' => 'stic_organizational_environment_accounts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'stic_organizational_environment_accounts_1accounts_ida',
  'link' => 'stic_organizational_environment_accounts_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accounts_1accounts_ida"] = array (
  'name' => 'stic_organizational_environment_accounts_1accounts_ida',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_accounts_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1_FROM_STIC_ORGANIZATIONAL_ENVIRONMENT_TITLE',
);

$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accounts"] = array (
  'name' => 'stic_organizational_environment_accounts',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_accounts',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'id_name' => 'stic_organizational_environment_accountsaccounts_ida',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accounts_name"] = array (
  'name' => 'stic_organizational_environment_accounts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'stic_organizational_environment_accountsaccounts_ida',
  'link' => 'stic_organizational_environment_accounts',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_accountsaccounts_ida"] = array (
  'name' => 'stic_organizational_environment_accountsaccounts_ida',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_accounts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_FROM_STIC_ORGANIZATIONAL_ENVIRONMENT_TITLE',
);

$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contacts"] = array (
  'name' => 'stic_organizational_environment_contacts',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_contacts',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
  'id_name' => 'stic_organizational_environment_contactscontacts_ida',
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contacts_name"] = array (
  'name' => 'stic_organizational_environment_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'stic_organizational_environment_contactscontacts_ida',
  'link' => 'stic_organizational_environment_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["stic_Organizational_Environment"]["fields"]["stic_organizational_environment_contactscontacts_ida"] = array (
  'name' => 'stic_organizational_environment_contactscontacts_ida',
  'type' => 'link',
  'relationship' => 'stic_organizational_environment_contacts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_STIC_ORGANIZATIONAL_ENVIRONMENT_TITLE',
);

if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Organizational_Environment', 'stic_Organizational_Environment', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Organizational_Environment']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Organizational_Environment']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Organizational_Environment']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
