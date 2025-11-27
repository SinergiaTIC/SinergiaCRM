REPLACE INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`) 
VALUES 
('f47ac10b-58cc-4372-a567-0e02b2c3d479', NOW(), NOW(), '1', '1', 0, '1', 'GENERAL', 'GENERAL_CERTIFICATE_PASSWORD', '', 'Contraseña del certificado digital.'),
('f47ac10b-58cc-4372-a567-0e02b2c3d480', NOW(), NOW(), '1', '1', 0, '1', 'VERIFACTU', 'VERIFACTU_TEST', 1, 'Indica el modo de trabajo (0 = Real, 1 = Test).');
