<?php
// created: 2026-09-29 16:53:54
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
