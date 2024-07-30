INSERT INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`) VALUES
('652c33e1-522e-4f06-8a66-755d05a318e6', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_ACTIVE', '0', "0 indica que no se enviarán mensajes. 1 indica que s´q se enviarán mensajes."),
('d9f0118c-b752-444d-9057-456b18f9f360', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_API_KEY', 'XXXXX', "Clave para conectar con Seven. Se obtiene en el panel de control de Seven."),
('c8d720bd-2c7b-47a6-98ce-9664529559ca', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_SENDER', '<ORGANITZACIÓ>', "Nombre que aparecerá como remitente en los mensajes enviados");
