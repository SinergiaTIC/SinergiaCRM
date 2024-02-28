REPLACE INTO `stic_settings` (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`) VALUES
('2013e269-06b7-42ef-8a6d-44ff2341c8ec', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_CURRENCY', '978', 'Código de moneda para la pasarela de pago (978 = Euro).'),
('496bf293-1552-453a-96fb-1dde7d78ea63', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_MERCHANT_NAME', NULL, 'El nombre de tu organización, que se mostrará en la pasarela de pago.'),
('bb3a7ca4-8668-4e7d-a6f3-c3bfbe7247d8', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_MERCHANT_CODE', NULL, 'Código numérico único proporcionado por la pasarela de pago.'),
('d72a1bc8-c20b-49b6-a970-0ff37178b9d5', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_TEST', '1', 'Indica el modo de funcionamiento (0 = Real, 1 = Prueba).'),
('3cbabeae-f992-4958-955b-1a0cd4300dc8', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_TERMINAL', '1', 'Número de terminal proporcionado por la pasarela de pago. Usualmente el número 1, pero podría ser 2, 3, etc., dependiendo de si tu organización tiene uno o más terminales POS.'),
('7f72883c-18ba-46c9-b4fc-511b17f6a5ad', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_PASSWORD_TEST', NULL, 'Contraseña para el modo de prueba, proporcionada por la pasarela de pago.'),
('7521efd8-671c-4296-9b6f-3bc236fc1fe0', NOW(), NOW(), '1', '1', 0, '1', 'TPVCECA', 'TPVCECA_PASSWORD', NULL, 'Contraseña para el modo real, proporcionada por la pasarela de pago.');
