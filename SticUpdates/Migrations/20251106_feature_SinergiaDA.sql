-- Active: 1632214630318@@localhost@2002@sinergiacrm
-- Add fields_meta_data fields for SDA Users

REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Userssda_readonly_c', 'Users', 'sda_readonly_c');


ALTER TABLE users_cstm add COLUMN `sda_readonly_c` bool  DEFAULT '0' NULL;

