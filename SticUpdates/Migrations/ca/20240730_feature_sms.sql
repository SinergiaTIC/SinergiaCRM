REPLACE INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`) VALUES
('652c33e1-522e-4f06-8a66-755d05a318e6', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_ACTIVE', '0', "Indica si està habilitat l'enviament de missatges SMS a través de Seven (0 = No, 1 = Sí)."),
('d9f0118c-b752-444d-9057-456b18f9f360', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_API_KEY', '', "Clau per connectar amb Seven. S'obté al seu panell de control."),
('c8d720bd-2c7b-47a6-98ce-9664529559ca', NOW(), NOW(), '1', '1', 0, '1', 'MESSAGES', 'MESSAGES_SENDER', '', 'Nom que apareixerà com a remitent en els missatges enviats.'),
('64b1554a-cf95-423b-ae78-7f75951f3976', NOW(), NOW(), '1', '1', 0, '1', 'MESSAGES', 'MESSAGES_LIMIT', '100', 'Nombre màxim de missatges permesos en un enviament massiu.');
