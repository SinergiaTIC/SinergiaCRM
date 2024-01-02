<?php
$dashletData['stic_Sepe_FilesDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'status' => 
  array (
    'default' => '',
  ),
  'reported_month' => 
  array (
    'default' => '',
  ),
  'type' => 
  array (
    'default' => '',
  ),
  'agreement' => 
  array (
    'default' => '',
  ),
  'assigned_user_name' => 
  array (
    'default' => '',
  ),
);
$dashletData['stic_Sepe_FilesDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
    'name' => 'status',
  ),
  'reported_month' => 
  array (
    'type' => 'date',
    'label' => 'LBL_REPORTED_MONTH',
    'width' => '10%',
    'default' => true,
    'name' => 'reported_month',
  ),
  'type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'type',
  ),
  'agreement' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_AGREEMENT',
    'width' => '10%',
    'default' => true,
    'name' => 'agreement',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
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
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'columns' => 
  array (
    'name' => 
    array (
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
    ),
    'status' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_STATUS',
      'width' => '10%',
      'default' => true,
      'name' => 'status',
    ),
    'reported_month' => 
    array (
      'type' => 'date',
      'label' => 'LBL_REPORTED_MONTH',
      'width' => '10%',
      'default' => true,
      'name' => 'reported_month',
    ),
    'type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
      'name' => 'type',
    ),
    'agreement' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_AGREEMENT',
      'width' => '10%',
      'default' => true,
      'name' => 'agreement',
    ),
    'assigned_user_name' => 
    array (
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
    ),
    'width' => '10%',
    'default' => false,
  ),
  'searchfields' => 
  array (
    'name' => 
    array (
      'default' => '',
    ),
    'status' => 
    array (
      'default' => '',
    ),
    'reported_month' => 
    array (
      'default' => '',
    ),
    'type' => 
    array (
      'default' => '',
    ),
    'agreement' => 
    array (
      'default' => '',
    ),
    'assigned_user_name' => 
    array (
      'default' => '',
    ),
    'width' => '10%',
    'default' => false,
  ),
);
