<?php
$module_name = 'stic_Work_Experience';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'STIC_WORK_EXPERIENCE_CONTACTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_WORK_EXPERIENCE_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_WORK_EXPERIENCE_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'STIC_WORK_EXPERIENCE_ACCOUNTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_WORK_EXPERIENCE_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'id' => 'STIC_WORK_EXPERIENCE_ACCOUNTSACCOUNTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'POSITION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_POSITION',
    'width' => '10%',
    'default' => true,
  ),
  'POSITION_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_POSITION_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'SECTOR' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SECTOR',
    'width' => '10%',
    'default' => true,
  ),
  'START_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'CONTRACT_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CONTRACT_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'END_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'ACHIEVED' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_ACHIEVED',
    'width' => '10%',
  ),
  'SUBSECTOR' => 
  array (
    'type' => 'dynamicenum',
    'studio' => 'visible',
    'label' => 'LBL_SUBSECTOR',
    'width' => '10%',
    'default' => false,
  ),
  'SCHEDULE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'default' => false,
  ),
  'WORKDAY_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_WORKDAY_TYPE',
    'width' => '10%',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
