-- =============================================================================
-- Verifactu (AEAT) Integration — Full Migration
-- Date: 2026-05-18
-- Description: All Verifactu SQL migrations unified into a single file.
--              Includes fields_meta_data entries and stic_settings configuration.
-- =============================================================================

-- =============================================================================
-- AOS_Invoices — Fields added to fields_meta_data (18 entries)
-- verifactu_hash_c                : Hash del registro de factura (huella AEAT, SHA-256)
-- verifactu_previous_hash_c       : Hash de la factura anterior (encadenamiento de cadena)
-- verifactu_check_url_c           : URL QR de verificación devuelta por AEAT
-- verifactu_aeat_status_c         : Estado AEAT: pending / emitted / accepted / rejected / cancelled / error
-- verifactu_aeat_response_c       : Respuesta literal de AEAT (max 255 chars)
-- verifactu_cancel_id_c           : ID de la factura que se cancela (para anulaciones y rectificativas)
-- verifactu_cancel_name_c         : Relate — nombre de la factura que se cancela/rectifica (non-db, joins via cancel_id_c)
-- verifactu_csv_c                 : Código Seguro de Verificación devuelto por AEAT
-- verifactu_submitted_at_c        : Fecha y hora del último envío a AEAT
-- stic_invoice_type_c             : Tipo de serie de factura (dropdown configurado en config_override.php)
-- verifactu_is_rectified_c        : Flag booleano — indica si la factura es rectificativa
-- verifactu_rectified_type_c      : Tipo de rectificación: S (sustitución) / I (diferencias)
-- verifactu_rectified_base_c      : Base de rectificación: R1 / R2 / R3 / R4 / R5
-- verifactu_rectified_date_c      : Fecha de la factura original que se rectifica
-- verifactu_cancel_hash_c         : Hash del CancellationRecord (separado del hash de factura original)
-- verifactu_audit_log_c           : Log técnico de auditoría de todas las operaciones Verifactu
-- verifactu_previous_status_c     : Estado de pago previo al envío AEAT (preserva estado 'paid')
-- =============================================================================

-- =============================================================================
-- AOS_Products_Quotes — Fields added to fields_meta_data (1 field)
-- verifactu_aeat_operation_type_c : Tipo de operación AEAT por línea: S (sujeto) / E (exento) / N (no sujeto) / NL (no sujeto por localizacion)
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
-- stic_settings — Verifactu configuration (3 entries)
-- VERIFACTU_TEST       : Modo de trabajo (0 = Real, 1 = Test)
-- VERIFACTU_TAX_TYPE   : Tipo de impuesto por defecto (01=IVA, 02=IPSI, 03=IGIC)
-- VERIFACTU_ACTIVATED  : Activa o desactiva la integración (0 = modo legacy, 1 = Verifactu activo)
-- =============================================================================

REPLACE INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`)
VALUES
('f47ac10b-58cc-4372-a567-0e02b2c3d480', NOW(), NOW(), '1', '1', 0, '1', 'VERIFACTU', 'VERIFACTU_TEST', 1, 'Indica el modo de trabajo (0 = Real, 1 = Test).'),
('f47ac10b-58cc-4372-a567-0e02b2c3d482', NOW(), NOW(), '1', '1', 0, '1', 'VERIFACTU', 'VERIFACTU_TAX_TYPE', '01', 'Tipo de impuesto por defecto (01=IVA, 02=IPSI, 03=IGIC).'),
('f47ac10b-58cc-4372-a567-0e02b2c3d481', NOW(), NOW(), '1', '1', 0, '1', 'VERIFACTU', 'VERIFACTU_ACTIVATED', 0, 'Activa o desactiva la integración con Verifactu AEAT (0 = modo legacy sin envío, 1 = Verifactu activo con envío automático a AEAT).');