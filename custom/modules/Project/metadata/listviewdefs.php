<?php
// created: 2020-07-04 10:28:55
$listViewDefs['Project'] = array (
  'NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
  ),
  'STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'link' => false,
    'default' => true,
  ),
  'ESTIMATED_START_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_START',
    'link' => false,
    'default' => true,
  ),
  'ESTIMATED_END_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_END',
    'link' => false,
    'default' => true,
  ),
  'STIC_LOCATION_C' => 
  array (
    'type' => 'multienum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STIC_LOCATION',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER_ID',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'PRIORITY' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_PRIORITY',
    'width' => '10%',
    'default' => false,
  ),
  'OVERRIDE_BUSINESS_HOURS' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_OVERRIDE_BUSINESS_HOURS',
    'width' => '10%',
  ),
  'AM_PROJECTTEMPLATES_PROJECT_1_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_AM_PROJECTTEMPLATES_PROJECT_1_FROM_AM_PROJECTTEMPLATES_TITLE',
    'id' => 'AM_PROJECTTEMPLATES_PROJECT_1AM_PROJECTTEMPLATES_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'TOTAL_ACTUAL_EFFORT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_LIST_TOTAL_ACTUAL_EFFORT',
    'width' => '10%',
    'default' => false,
  ),
  'TOTAL_ESTIMATED_EFFORT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_LIST_TOTAL_ESTIMATED_EFFORT',
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