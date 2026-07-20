-- =============================================================================
-- Verifactu (AEAT) Integration — Language-specific content
-- Date: 2026-05-18
-- Database-level migration (fields_meta_data + stic_settings):
-- SticUpdates/Migrations/20260518_feature_verifactu.sql
-- =============================================================================
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
