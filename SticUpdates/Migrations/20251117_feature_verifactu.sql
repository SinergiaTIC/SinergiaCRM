-- Add fields_meta_data entries for Verifactu fields
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('AOS_Invoicesverifactu_hash_c', 'AOS_Invoices', 'verifactu_hash_c'),
('AOS_Invoicesverifactu_previous_hash_c', 'AOS_Invoices', 'verifactu_previous_hash_c'),
('AOS_Invoicesverifactu_check_url_c', 'AOS_Invoices', 'verifactu_check_url_c'),
('AOS_Invoicesverifactu_aeat_status_c', 'AOS_Invoices', 'verifactu_aeat_status_c'),
('AOS_Invoicesverifactu_aeat_response_c', 'AOS_Invoices', 'verifactu_aeat_response_c'),
('AOS_Invoicesverifactu_cancel_id_c', 'AOS_Invoices', 'verifactu_cancel_id_c'),
('AOS_Invoicesverifactu_csv_c', 'AOS_Invoices', 'verifactu_csv_c'),
('AOS_Invoicesverifactu_submitted_at_c', 'AOS_Invoices', 'verifactu_submitted_at_c'),
('AOS_Invoicesstic_invoice_type_c', 'AOS_Invoices', 'stic_invoice_type_c'),
('AOS_Products_Quotesverifactu_aeat_operation_type_c', 'AOS_Products_Quotes', 'verifactu_aeat_operation_type_c'),
('AOS_Invoicesverifactu_is_rectified_c', 'AOS_Invoices', 'verifactu_is_rectified_c'),
('AOS_Invoicesverifactu_rectified_type_c', 'AOS_Invoices', 'verifactu_rectified_type_c'),
('AOS_Invoicesverifactu_rectified_base_c', 'AOS_Invoices', 'verifactu_rectified_base_c'),
('AOS_Invoicesverifactu_rectified_date_c', 'AOS_Invoices', 'verifactu_rectified_date_c');
