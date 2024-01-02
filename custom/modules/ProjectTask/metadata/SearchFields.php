<?php
// created: 2020-07-02 17:25:25
$searchFields['ProjectTask'] = array (
  'name' => 
  array (
    'query_type' => 'default',
  ),
  'current_user_only' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'assigned_user_id',
    ),
    'my_items' => true,
    'vname' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
  ),
  'project_name' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'project.name',
    ),
  ),
  'assigned_user_id' => 
  array (
    'query_type' => 'default',
  ),
  'range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_finish' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_finish' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_finish' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'favorites_only' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'checked_only' => true,
    'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'ProjectTask\'
			                        and favorites.assigned_user_id = \'{1}\'',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'range_percent_complete' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_percent_complete' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_percent_complete' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_actual_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_actual_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_actual_duration' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_order_number' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_order_number' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_order_number' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_actual_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_actual_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_actual_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_estimated_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_estimated_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_estimated_effort' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_utilization' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_utilization' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_utilization' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
);