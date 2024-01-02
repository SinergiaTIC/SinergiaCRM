<?php
// created: 2021-09-16 09:42:35
$searchFields['stic_Resources'] = array(
    'name' => array(
        'query_type' => 'default',
    ),
    'current_user_only' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'assigned_user_id',
        ),
        'my_items' => true,
        'vname' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
    ),
    'assigned_user_id' => array(
        'query_type' => 'default',
    ),
    'range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_hourly_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_hourly_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_hourly_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_daily_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_daily_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_daily_rate' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'favorites_only' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'checked_only' => true,
        'subquery' => 'SELECT favorites.parent_id FROM favorites
                      WHERE favorites.deleted = 0
                          and favorites.parent_type = \'stic_Resources\'
                          and favorites.assigned_user_id = \'{1}\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
);
