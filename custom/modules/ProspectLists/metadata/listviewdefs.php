<?php
// created: 2020-07-04 10:28:55
$listViewDefs['ProspectLists'] = array (
  'NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_LIST_PROSPECT_LIST_NAME',
    'link' => true,
    'default' => true,
  ),
  'LIST_TYPE' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_TYPE_LIST_NAME',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_DESCRIPTION',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'MARKETING_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MARKETING_ID',
    'width' => '10%',
    'default' => false,
  ),
  'MARKETING_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MARKETING_NAME',
    'width' => '10%',
    'default' => false,
  ),
  'ENTRY_COUNT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_LIST_ENTRIES',
    'width' => '10%',
    'default' => false,
  ),
  'DOMAIN_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DOMAIN_NAME',
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
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'label' => 'LBL_MODIFIED',
    'id' => 'MODIFIED_USER_ID',
    'link' => true,
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
);