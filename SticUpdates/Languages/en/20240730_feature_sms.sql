REPLACE INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`) VALUES
('652c33e1-522e-4f06-8a66-755d05a318e6', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_ACTIVE', '0', 'Indicates whether sending SMS messages through Seven is enabled (0 = No, 1 = Yes).'),
('d9f0118c-b752-444d-9057-456b18f9f360', NOW(), NOW(), '1', '1', 0, '1', 'SEVEN', 'SEVEN_API_KEY', '', 'Key to connect with to Seven, obtained from its control panel.'),
('c8d720bd-2c7b-47a6-98ce-9664529559ca', NOW(), NOW(), '1', '1', 0, '1', 'MESSAGES', 'MESSAGES_SENDER', '', 'Sender name that will be shown on sent messages.'),
('64b1554a-cf95-423b-ae78-7f75951f3976', NOW(), NOW(), '1', '1', 0, '1', 'MESSAGES', 'MESSAGES_LIMIT', '100', 'Maximum number of messages allowed in a bulk send.');
