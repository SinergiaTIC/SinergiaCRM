<?php
// created: 2020-06-12 17:22:51
$searchFields['Accounts'] = array(
    'name' => array(
        'query_type' => 'default',
    ),
    'address_street' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_street',
            1 => 'shipping_address_street',
        ),
    ),
    'address_city' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_city',
            1 => 'shipping_address_city',
        ),
        'vname' => 'LBL_CITY',
    ),
    'address_state' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_state',
            1 => 'shipping_address_state',
        ),
        'vname' => 'LBL_STATE',
    ),
    'address_postalcode' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_postalcode',
            1 => 'shipping_address_postalcode',
        ),
        'vname' => 'LBL_POSTAL_CODE',
    ),
    'address_country' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_country',
            1 => 'shipping_address_country',
        ),
        'vname' => 'LBL_COUNTRY',
    ),
    'rating' => array(
        'query_type' => 'default',
    ),
    'phone' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'phone_office',
        ),
        'vname' => 'LBL_ANY_PHONE',
    ),
    'email' => array(
        'query_type' => 'default',
        'operator' => 'subquery',
        'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
        'db_field' => array(
            0 => 'id',
        ),
        'vname' => 'LBL_ANY_EMAIL',
    ),
    'website' => array(
        'query_type' => 'default',
    ),
    'employees' => array(
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
    'range_stic_total_annual_donations_c' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_stic_total_annual_donations_c' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_stic_total_annual_donations_c' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'favorites_only' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'checked_only' => true,
        'subquery' => 'SELECT favorites.parent_id FROM favorites
                      WHERE favorites.deleted = 0
                          and favorites.parent_type = \'Accounts\'
                          and favorites.assigned_user_id = \'{1}\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
);
