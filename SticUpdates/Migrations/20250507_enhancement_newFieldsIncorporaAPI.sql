INSERT INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Contactsinc_country_nationality_c', 'Contacts', 'inc_country_nationality_c');

update contacts_cstm set inc_nationality_c = '^011^' where inc_nationality_c = 'nacional';