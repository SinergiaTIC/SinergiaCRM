-- Add fields_meta_data fields for Time tracker and Work calendar

REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Usersstic_work_calendar_c', 'Users', 'stic_work_calendar_c');
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Usersstic_clock_c', 'Users', 'stic_clock_c');

ALTER TABLE users_cstm add COLUMN `stic_work_calendar_c` bool  DEFAULT '0' NULL;
ALTER TABLE users_cstm add COLUMN `stic_clock_c` bool  DEFAULT '0' NULL;
