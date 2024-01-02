<?php
// created: 2021-12-10 11:48:36
$searchFields['stic_Payments'] = array(
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
    'range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_rejection_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_rejection_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_rejection_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_payment_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_payment_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_payment_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'favorites_only' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'checked_only' => true,
        'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'stic_Payments\'
			                        and favorites.assigned_user_id = \'{1}\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
);
