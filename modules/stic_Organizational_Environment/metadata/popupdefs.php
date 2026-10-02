<?php
$popupMeta = array (
    'moduleMain' => 'stic_Organizational_Environment',
    'varName' => 'stic_Organizational_Environment',
    'orderBy' => 'stic_organizational_environment.name',
    'whereClauses' => array (
  'name' => 'stic_organizational_environment.name',
  'stic_organizational_environment_accounts_name' => 'stic_organizational_environment.stic_organizational_environment_accounts_name',
  'stic_organizational_environment_contacts_name' => 'stic_organizational_environment.stic_organizational_environment_contacts_name',
  'relationship_type' => 'stic_organizational_environment.relationship_type',
  'stic_organizational_environment_accounts_1_name' => 'stic_organizational_environment.stic_organizational_environment_accounts_1_name',
  'reference_account' => 'stic_organizational_environment.reference_account',
  'start_date' => 'stic_organizational_environment.start_date',
  'end_date' => 'stic_organizational_environment.end_date',
  'active' => 'stic_organizational_environment.active',
  'description' => 'stic_organizational_environment.description',
  'created_by' => 'stic_organizational_environment.created_by',
  'created_by_name' => 'stic_organizational_environment.created_by_name',
  'date_entered' => 'stic_organizational_environment.date_entered',
  'date_modified' => 'stic_organizational_environment.date_modified',
  'modified_user_id' => 'stic_organizational_environment.modified_user_id',
  'modified_by_name' => 'stic_organizational_environment.modified_by_name',
  'assigned_user_name' => 'stic_organizational_environment.assigned_user_name',
  'current_user_only' => 'stic_organizational_environment.current_user_only',
  'securitygroups_name' => 'stic_organizational_environment.securitygroups_name',
  'assigned_user_id' => 'stic_organizational_environment.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'stic_organizational_environment_accounts_name',
  5 => 'stic_organizational_environment_contacts_name',
  6 => 'relationship_type',
  7 => 'stic_organizational_environment_accounts_1_name',
  8 => 'reference_account',
  9 => 'start_date',
  10 => 'end_date',
  11 => 'active',
  12 => 'description',
  13 => 'created_by',
  14 => 'created_by_name',
  15 => 'date_entered',
  16 => 'date_modified',
  17 => 'modified_user_id',
  18 => 'modified_by_name',
  19 => 'assigned_user_name',
  20 => 'current_user_only',
  21 => 'securitygroups_name',
  22 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'stic_organizational_environment_accounts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'width' => '10%',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTSACCOUNTS_IDA',
    'name' => 'stic_organizational_environment_accounts_name',
  ),
  'stic_organizational_environment_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
    'width' => '10%',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTSCONTACTS_IDA',
    'name' => 'stic_organizational_environment_contacts_name',
  ),
  'relationship_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RELATIONSHIP_TYPE',
    'width' => '10%',
    'name' => 'relationship_type',
  ),
  'stic_organizational_environment_accounts_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
    'width' => '10%',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1ACCOUNTS_IDA',
    'name' => 'stic_organizational_environment_accounts_1_name',
  ),
  'reference_account' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_REFERENCE_ACCOUNT',
    'width' => '10%',
    'name' => 'reference_account',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'name' => 'start_date',
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'name' => 'end_date',
  ),
  'active' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_ACTIVE',
    'width' => '10%',
    'name' => 'active',
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'name' => 'description',
  ),
  'created_by' => 
  array (
    'type' => 'assigned_user_name',
    'label' => 'LBL_CREATED',
    'width' => '10%',
    'name' => 'created_by',
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'name' => 'created_by_name',
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'name' => 'date_entered',
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'name' => 'date_modified',
  ),
  'modified_user_id' => 
  array (
    'type' => 'assigned_user_name',
    'label' => 'LBL_MODIFIED',
    'width' => '10%',
    'name' => 'modified_user_id',
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'name' => 'modified_by_name',
  ),
  'assigned_user_name' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'name' => 'assigned_user_name',
  ),
  'current_user_only' => 
  array (
    'label' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
    'width' => '10%',
    'name' => 'current_user_only',
  ),
  'securitygroups_name' => 
  array (
    'label' => 'LBL_SECURITYGROUPS_NAME',
    'width' => '10%',
    'type' => 'relate',
    'studio' => 
    array (
      'searchview' => true,
      'visible' => false,
    ),
    'id' => '1',
    'link' => true,
    'name' => 'securitygroups_name',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
);
