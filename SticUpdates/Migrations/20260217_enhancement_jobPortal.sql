-- Create new fields in Accounts and Contacts in field_meta_data table
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Accountsstic_pa_password_c', 'Accounts', 'stic_pa_password_c'),
('Accountsstic_pa_username_c', 'Accounts', 'stic_pa_username_c'),
('Accountsstic_pa_enable_c', 'Accounts', 'stic_pa_enable_c'),
('Contactsstic_pa_password_c', 'Contacts', 'stic_pa_password_c'),
('Contactsstic_pa_username_c', 'Contacts', 'stic_pa_username_c'),
('Contactsstic_pa_enable_c', 'Contacts', 'stic_pa_enable_c');