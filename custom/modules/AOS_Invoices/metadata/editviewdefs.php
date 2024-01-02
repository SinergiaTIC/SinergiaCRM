<?php
$module_name = 'AOS_Invoices';
$_object_name = 'aos_invoices';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'SAVE',
                    1 => 'CANCEL',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_INVOICE_TO' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_LINE_ITEMS' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'LBL_DEFAULT_PANEL' => array(
                0 => array(
                    0 => array(
                        'name' => 'name',
                        'displayParams' => array(
                            'required' => true,
                        ),
                        'label' => 'LBL_NAME',
                    ),
                    1 => array(
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO_NAME',
                    ),
                ),
                1 => array(
                    0 => '',
                    1 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'quote_number',
                        'label' => 'LBL_QUOTE_NUMBER',
                    ),
                    1 => array(
                        'name' => 'quote_date',
                        'label' => 'LBL_QUOTE_DATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'invoice_date',
                        'label' => 'LBL_INVOICE_DATE',
                    ),
                    1 => array(
                        'name' => 'due_date',
                        'label' => 'LBL_DUE_DATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
            'LBL_INVOICE_TO' => array(
                0 => array(
                    0 => array(
                        'name' => 'billing_account',
                        'label' => 'LBL_BILLING_ACCOUNT',
                        'displayParams' => array(
                            'key' => array(
                                0 => 'billing',
                                1 => 'shipping',
                            ),
                            'copy' => array(
                                0 => 'billing',
                                1 => 'shipping',
                            ),
                            'billingKey' => 'billing',
                            'shippingKey' => 'shipping',
                        ),
                    ),
                    1 => array(
                        'name' => 'billing_contact',
                        'label' => 'LBL_BILLING_CONTACT',
                        'displayParams' => array(
                            'initial_filter' => '&account_name="+this.form.{$fields.billing_account.name}.value+"',
                        ),
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'billing_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => array(
                            'key' => 'billing',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                        'label' => 'LBL_BILLING_ADDRESS_STREET',
                    ),
                    1 => array(
                        'name' => 'shipping_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => array(
                            'key' => 'shipping',
                            'copy' => 'billing',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                        'label' => 'LBL_SHIPPING_ADDRESS_STREET',
                    ),
                ),
            ),
            'lbl_line_items' => array(
                0 => array(
                    0 => array(
                        'name' => 'currency_id',
                        'studio' => 'visible',
                        'label' => 'LBL_CURRENCY',
                    ),
                    1 => '',
                ),
                1 => array(
                    0 => array(
                        'name' => 'line_items',
                        'label' => 'LBL_LINE_ITEMS',
                    ),
                ),
                2 => array(
                    0 => '',
                ),
                3 => array(
                    0 => array(
                        'name' => 'total_amt',
                        'label' => 'LBL_TOTAL_AMT',
                    ),
                    1 => '',
                ),
                4 => array(
                    0 => array(
                        'name' => 'discount_amount',
                        'label' => 'LBL_DISCOUNT_AMOUNT',
                    ),
                    1 => '',
                ),
                5 => array(
                    0 => array(
                        'name' => 'subtotal_amount',
                        'label' => 'LBL_SUBTOTAL_AMOUNT',
                    ),
                    1 => '',
                ),
                6 => array(
                    0 => array(
                        'name' => 'shipping_amount',
                        'label' => 'LBL_SHIPPING_AMOUNT',
                        'displayParams' => array(
                            'field' => array(
                                'onblur' => 'calculateTotal(\'lineItems\');',
                            ),
                        ),
                    ),
                    1 => '',
                ),
                7 => array(
                    0 => array(
                        'name' => 'shipping_tax_amt',
                        'label' => 'LBL_SHIPPING_TAX_AMT',
                    ),
                    1 => '',
                ),
                8 => array(
                    0 => array(
                        'name' => 'tax_amount',
                        'label' => 'LBL_TAX_AMOUNT',
                    ),
                    1 => '',
                ),
                9 => array(
                    0 => array(
                        'name' => 'total_amount',
                        'label' => 'LBL_GRAND_TOTAL',
                    ),
                    1 => '',
                ),
            ),
        ),
    ),
);
