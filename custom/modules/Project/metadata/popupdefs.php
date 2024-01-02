<?php
$popupMeta = array (
    'moduleMain' => 'Project',
    'varName' => 'PROJECT',
    'orderBy' => 'name',
    'whereClauses' => array (
  'name' => 'project.name',
  'status' => 'project.status',
  'estimated_start_date' => 'project.estimated_start_date',
  'estimated_end_date' => 'project.estimated_end_date',
  'assigned_user_name' => 'project.assigned_user_name',
  'total_estimated_effort' => 'project.total_estimated_effort',
  'created_by' => 'project.created_by',
  'current_user_only' => 'project.current_user_only',
  'favorites_only' => 'project.favorites_only',
  'priority' => 'project.priority',
),
    'searchInputs' => array (
  0 => 'name',
  1 => 'status',
  2 => 'estimated_start_date',
  3 => 'estimated_end_date',
  5 => 'assigned_user_name',
  7 => 'total_estimated_effort',
  13 => 'created_by',
  16 => 'current_user_only',
  17 => 'favorites_only',
  18 => 'priority',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
  ),
  'estimated_start_date' => 
  array (
    'name' => 'estimated_start_date',
    'width' => '10%',
  ),
  'estimated_end_date' => 
  array (
    'name' => 'estimated_end_date',
    'width' => '10%',
  ),
  'priority' => 
  array (
    'name' => 'priority',
    'width' => '10%',
  ),
  'assigned_user_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_ASSIGNED_USER_NAME',
    'width' => '10%',
    'id' => 'ASSIGNED_USER_ID',
    'name' => 'assigned_user_name',
  ),
  'total_estimated_effort' => 
  array (
    'type' => 'int',
    'label' => 'LBL_LIST_TOTAL_ESTIMATED_EFFORT',
    'width' => '10%',
    'name' => 'total_estimated_effort',
  ),
  'created_by' => 
  array (
    'type' => 'assigned_user_name',
    'label' => 'LBL_CREATED_BY',
    'width' => '10%',
    'name' => 'created_by',
  ),
  'current_user_only' => 
  array (
    'label' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
    'width' => '10%',
    'name' => 'current_user_only',
  ),
  'favorites_only' => 
  array (
    'label' => 'LBL_FAVORITES_FILTER',
    'type' => 'bool',
    'width' => '10%',
    'name' => 'favorites_only',
  ),
),
);
