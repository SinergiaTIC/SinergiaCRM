<?php
$dashletData['stic_Work_ExperienceDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'stic_work_experience_contacts_name' => 
  array (
    'default' => '',
  ),
  'stic_work_experience_accounts_name' => 
  array (
    'default' => '',
  ),
  'position' => 
  array (
    'default' => '',
  ),
  'start_date' => 
  array (
    'default' => '',
  ),
  'contract_type' => 
  array (
    'default' => '',
  ),
  'sector' => 
  array (
    'default' => '',
  ),
  'date_entered' => 
  array (
    'default' => '',
  ),
  'date_modified' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
);
$dashletData['stic_Work_ExperienceDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'stic_work_experience_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_WORK_EXPERIENCE_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_work_experience_contacts_name',
  ),
  'stic_work_experience_accounts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'id' => 'STIC_WORK_EXPERIENCE_ACCOUNTSACCOUNTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_work_experience_accounts_name',
  ),
  'position' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_POSITION',
    'width' => '10%',
    'default' => true,
    'name' => 'position',
  ),
  'position_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_POSITION_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'position_type',
  ),
  'sector' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SECTOR',
    'width' => '10%',
    'default' => false,
    'name' => 'sector',
  ),
  'subsector' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_SUBSECTOR',
    'width' => '10%',
    'default' => false,
    'name' => 'subsector',
  ),
  'schedule' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'default' => false,
    'name' => 'schedule',
  ),
  'workday_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_WORKDAY_TYPE',
    'width' => '10%',
    'default' => false,
    'name' => 'workday_type',
  ),
  'contract_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CONTRACT_TYPE',
    'width' => '10%',
    'default' => false,
    'name' => 'contract_type',
  ),
  'achieved' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_ACHIEVED',
    'width' => '10%',
    'name' => 'achieved',
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
    'name' => 'description',
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
    'name' => 'modified_by_name',
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
    'name' => 'created_by_name',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
    'name' => 'date_entered',
  ),
);
