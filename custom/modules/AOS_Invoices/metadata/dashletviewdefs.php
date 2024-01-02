<?php
$dashletData['AOS_InvoicesDashlet']['searchFields'] = array(
    'date_entered' => array(
        'default' => '',
    ),
    'billing_account' => array(
        'default' => '',
    ),
    'assigned_user_id' => array(
        'default' => '',
    ),
    'name' => array(
        'default' => '',
    ),
    'number' => array(
        'default' => '',
    ),
    'invoice_date' => array(
        'default' => '',
    ),
);
$dashletData['AOS_InvoicesDashlet']['columns'] = array(
    'number' => array(
        'width' => '5%',
        'label' => 'LBL_LIST_NUM',
        'default' => true,
        'name' => 'number',
    ),
    'name' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'status' => array(
        'width' => '15%',
        'label' => 'LBL_STATUS',
        'default' => true,
        'name' => 'status',
    ),
    'total_amount' => array(
        'width' => '15%',
        'label' => 'LBL_GRAND_TOTAL',
        'currency_format' => true,
        'default' => true,
        'name' => 'total_amount',
    ),
    'due_date' => array(
        'width' => '15%',
        'label' => 'LBL_DUE_DATE',
        'default' => true,
        'name' => 'due_date',
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => true,
    ),
    'billing_account' => array(
        'width' => '20%',
        'label' => 'LBL_BILLING_ACCOUNT',
        'name' => 'billing_account',
        'default' => false,
    ),
    'billing_contact' => array(
        'width' => '15%',
        'label' => 'LBL_BILLING_CONTACT',
        'name' => 'billing_contact',
        'default' => false,
    ),
    'invoice_date' => array(
        'width' => '15%',
        'label' => 'LBL_INVOICE_DATE',
        'name' => 'invoice_date',
        'default' => false,
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'name' => 'date_entered',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'LBL_CREATED',
        'name' => 'created_by',
        'default' => false,
    ),
    'quote_number' => array(
        'type' => 'int',
        'label' => 'LBL_QUOTE_NUMBER',
        'width' => '10%',
        'default' => false,
        'name' => 'quote_number',
    ),
    'quote_date' => array(
        'type' => 'date',
        'label' => 'LBL_QUOTE_DATE',
        'width' => '10%',
        'default' => false,
        'name' => 'quote_date',
    ),
);
