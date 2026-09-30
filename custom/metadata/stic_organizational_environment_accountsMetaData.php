<?php
// created: 2026-09-29 16:53:54
$dictionary["stic_organizational_environment_accounts"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'stic_organizational_environment_accounts' => 
    array (
      'lhs_module' => 'Accounts',
      'lhs_table' => 'accounts',
      'lhs_key' => 'id',
      'rhs_module' => 'stic_Organizational_Environment',
      'rhs_table' => 'stic_organizational_environment',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'stic_organizational_environment_accounts_c',
      'join_key_lhs' => 'stic_organizational_environment_accountsaccounts_ida',
      'join_key_rhs' => 'stic_organ548cronment_idb',
    ),
  ),
  'table' => 'stic_organizational_environment_accounts_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'stic_organizational_environment_accountsaccounts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'stic_organ548cronment_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'stic_organizational_environment_accountsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'stic_organizational_environment_accounts_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'stic_organizational_environment_accountsaccounts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'stic_organizational_environment_accounts_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'stic_organ548cronment_idb',
      ),
    ),
  ),
);