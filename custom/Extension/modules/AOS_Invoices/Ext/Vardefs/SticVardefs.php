<?php
$dictionary['AOS_Invoices']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

// Mass update fields definition:
$dictionary['AOS_Invoices']['fields']['billing_account']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_contact']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_street']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_city']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_state']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_postalcode']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['billing_address_country']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_street']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_city']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_state']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_postalcode']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['shipping_address_country']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['quote_number']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['quote_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['invoice_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['due_date']['massupdate'] = 1;
$dictionary['AOS_Invoices']['fields']['status']['massupdate'] = 1;

// Inline edition definition:
$dictionary['AOS_Invoices']['fields']['number']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['billing_address_street']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_address_street']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['currency_id']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['line_items']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['total_amt']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['discount_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['subtotal_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['shipping_tax_amt']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['tax_amount']['inline_edit'] = 0;
$dictionary['AOS_Invoices']['fields']['total_amount']['inline_edit'] = 0;
