<?php
// created: 2020-07-04 10:28:55
$listViewDefs['FP_Event_Locations'] = array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CAPACITY' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_CAPACITY',
    'width' => '10%',
    'align' => 'right',
  ),
  'ADDRESS' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_ADDRESS',
    'width' => '10%',
  ),
  'ADDRESS_POSTALCODE' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_ADDRESS_POSTALCODE',
    'width' => '10%',
  ),
  'ADDRESS_CITY' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_ADDRESS_CITY',
    'width' => '10%',
  ),
  'ADDRESS_STATE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_ADDRESS_STATE',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'STIC_ADDRESS_COUNTY_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_STIC_ADDRESS_COUNTY',
    'width' => '10%',
  ),
  'STIC_ADDRESS_REGION_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_STIC_ADDRESS_REGION',
    'width' => '10%',
  ),
  'ADDRESS_COUNTRY' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_ADDRESS_COUNTRY',
    'width' => '10%',
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
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
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
);