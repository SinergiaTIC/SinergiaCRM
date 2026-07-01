-- =============================================================================
-- Verifactu (AEAT) Integration — fields_meta_data migration
-- Date: 2026-05-18
-- stic_settings kept in Languages/es/20260518_feature_verifactu.sql
-- =============================================================================

-- =============================================================================
-- AOS_Invoices — Fields added to fields_meta_data (18 entries, see also 20260701_feature_verifactu_valid_invoice.sql for verifactu_valid_invoice_c)
-- =============================================================================

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
('AOS_Invoicesverifactu_is_rectified_c', 'AOS_Invoices', 'verifactu_is_rectified_c'),
('AOS_Invoicesverifactu_rectified_type_c', 'AOS_Invoices', 'verifactu_rectified_type_c'),
('AOS_Invoicesverifactu_rectified_base_c', 'AOS_Invoices', 'verifactu_rectified_base_c'),
('AOS_Invoicesverifactu_rectified_date_c', 'AOS_Invoices', 'verifactu_rectified_date_c'),
('AOS_Invoicesverifactu_cancel_hash_c', 'AOS_Invoices', 'verifactu_cancel_hash_c'),
('AOS_Invoicesverifactu_audit_log_c', 'AOS_Invoices', 'verifactu_audit_log_c'),
('AOS_Invoicesverifactu_previous_status_c', 'AOS_Invoices', 'verifactu_previous_status_c'),
('AOS_Invoicesverifactu_cancel_name_c', 'AOS_Invoices', 'verifactu_cancel_name_c'),
('AOS_Products_Quotesverifactu_aeat_operation_type_c', 'AOS_Products_Quotes', 'verifactu_aeat_operation_type_c');

-- =============================================================================
-- Rename duplicate invoice numbers before creating unique index via SticRefresh
-- (index definition in custom/Extension/modules/AOS_Invoices/Ext/Vardefs/SticVardefs.php)
-- =============================================================================

UPDATE aos_invoices t
    INNER JOIN (
        SELECT id,
               IF(@prev = number, @rn := @rn + 1, @rn := 1) AS rn,
               @prev := number AS number
        FROM aos_invoices
        CROSS JOIN (SELECT @rn := 0, @prev := '') vars
        WHERE COALESCE(number, '') != ''
        ORDER BY number, date_entered
    ) ranked ON t.id = ranked.id
SET t.number = CONCAT(t.number, '-DUP-', ranked.rn - 1)
WHERE ranked.rn > 1;

-- =============================================================================
-- AOS_PDF_Templates — Verifactu invoice template
-- =============================================================================

REPLACE INTO `aos_pdf_templates` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `active`, `type`, `pdfheader`, `pdffooter`, `margin_left`, `margin_right`, `margin_top`, `margin_bottom`, `margin_header`, `margin_footer`, `page_size`, `orientation`) VALUES
('c2e189c0-e637-4e6c-b810-ba6b91b346a3', 'Modelo factura verifactu (es)', NOW(), NOW(), '1', '1', '<table style="width:700px;" border="0" cellpadding="2"><tbody><tr><td width="200"><img src="custom/themes/default/images/company_logo.png" alt="" width="200" /></td><td align="right" width="500"><strong>FECHA:</strong> $aos_invoices_invoice_date<br /><strong>Nº FACTURA:</strong> $aos_invoices_number</td></tr></tbody></table><table style="width:700px;" border="0" cellpadding="2"><tbody><tr><td bgcolor="#f5f5f5" width="230"><strong>$aos_invoices_billing_account</strong><br />NIF: $billing_account_stic_identification_number_c<br />$billing_account_billing_address_street<br />$billing_account_billing_address_postalcode $billing_account_billing_address_city</td><td width="470"> </td></tr></tbody></table><hr /><p> </p><table style="width:700px;border-spacing:1px;" border="0" cellpadding="1"><tbody><tr><td style="border:0.5px solid #cccccc;" width="60"><span style="font-size:small;"><strong>Cant.</strong></span></td><td style="border:0.5px solid #cccccc;" width="340"><span style="font-size:small;"><strong>Producto</strong></span></td><td style="border:0.5px solid #cccccc;" align="right" width="150"><span style="font-size:small;"><strong>Precio</strong></span></td><td style="border:0.5px solid #cccccc;" align="right" width="150"><span style="font-size:small;"><strong>Total</strong></span></td></tr></tbody></table><table style="width:700px;border-spacing:1px;" border="0" cellpadding="1"><tbody><tr><td style="border:0.5px solid #cccccc;" align="center" width="60"><span style="font-size:x-small;">$aos_products_quotes_product_qty</span></td><td style="border:0.5px solid #cccccc;" width="340"><span style="font-size:x-small;">$aos_products_quotes_name</span></td><td style="border:0.5px solid #cccccc;" align="right" width="150"><span style="font-size:x-small;">$aos_products_quotes_product_unit_price €</span></td><td style="border:0.5px solid #cccccc;" align="right" width="150"><span style="font-size:x-small;">$aos_products_quotes_product_total_price €</span></td></tr></tbody></table><table style="width:700px;" border="0" cellpadding="2"><tbody><tr><td style="text-align:right;"><strong>Subtotal:</strong></td><td style="border:0.5px solid #000000;text-align:right;" align="right" width="175">$aos_invoices_subtotal_amount €</td></tr><tr><td style="text-align:right;"><strong>Descuento:</strong></td><td style="border:0.5px solid #000000;text-align:right;" align="right" width="175">$aos_invoices_discount_amount €</td></tr><tr><td style="text-align:right;"><strong>Impuestos:</strong></td><td style="border:0.5px solid #000000;text-align:right;" align="right" width="175">$aos_invoices_tax_amount €</td></tr><tr><td style="text-align:right;"><strong>TOTAL:</strong></td><td style="border:0.5px solid #000000;text-align:right;" align="right" width="175"><strong>$aos_invoices_total_amount €</strong></td></tr></tbody></table><p> </p><hr /><p> </p><p> </p><table style="width:700px;" border="0" cellpadding="2"><tbody><tr><td width="280"><strong>QR VERIFACTU</strong><br />$aos_invoices_verifactu_check_url_c</td><td width="420"><strong>DATOS VERIFACTU</strong><br />Estado: $aos_invoices_verifactu_aeat_status_c<br />CSV: $aos_invoices_verifactu_csv_c<br />Hash: $aos_invoices_verifactu_hash_c<br />Enviado: $aos_invoices_verifactu_submitted_at_c</td></tr></tbody></table>', 0, '1', 1, 'AOS_Invoices', NULL, '<p style="text-align:right;font-size:8px;color:#999;">Página {PAGENO} de {nb}</p>', 15, 15, 20, 20, 10, 10, 'A4', 'Portrait');
