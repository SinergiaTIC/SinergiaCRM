<?php
// created: 2023-04-03 18:05:58
$searchFields['AOS_Invoices'] = array(
    'name' => array(
        'query_type' => 'default',
    ),
    'account_type' => array(
        'query_type' => 'default',
        'options' => 'account_type_dom',
        'template_var' => 'ACCOUNT_TYPE_OPTIONS',
    ),
    'industry' => array(
        'query_type' => 'default',
        'options' => 'industry_dom',
        'template_var' => 'INDUSTRY_OPTIONS',
    ),
    'annual_revenue' => array(
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
    ),
    'address_state' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_state',
            1 => 'shipping_address_state',
        ),
    ),
    'address_postalcode' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_postalcode',
            1 => 'shipping_address_postalcode',
        ),
    ),
    'address_country' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'billing_address_country',
            1 => 'shipping_address_country',
        ),
    ),
    'rating' => array(
        'query_type' => 'default',
    ),
    'phone' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'phone_office',
        ),
    ),
    'email' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'email1',
            1 => 'email2',
        ),
    ),
    'website' => array(
        'query_type' => 'default',
    ),
    'ownership' => array(
        'query_type' => 'default',
    ),
    'employees' => array(
        'query_type' => 'default',
    ),
    'ticker_symbol' => array(
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
    'favorites_only' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'checked_only' => true,
        'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'AOS_Invoices\'
			                        and favorites.assigned_user_id = \'{1}\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
    'range_total_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_total_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_total_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_due_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_due_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_due_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
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
    'range_quote_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_quote_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_quote_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_invoice_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_invoice_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_invoice_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
);
