<?php
// created: 2021-01-11 13:02:29
$searchFields['stic_Incorpora_Locations'] = array (
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
  'range_state_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_state_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_state_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_municipality_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_municipality_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_municipality_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_town_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_town_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_town_code' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'favorites_only' => array(
    'query_type' => 'format',
    'operator' => 'subquery',
    'checked_only' => true,
    'subquery' => 'SELECT favorites.parent_id FROM favorites
                      WHERE favorites.deleted = 0
                          and favorites.parent_type = \'stic_Incorpora_Locations\'
                          and favorites.assigned_user_id = \'{1}\'',
    'db_field' => array(
        0 => 'id',
    ),
  ),
);