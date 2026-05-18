-- =============================================================================
-- Verifactu (AEAT) Integration — fields_meta_data migration
-- Date: 2026-05-18
-- stic_settings kept in Languages/es/20260518_feature_verifactu.sql
-- =============================================================================

-- =============================================================================
-- AOS_Invoices — Fields added to fields_meta_data (18 entries)
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
