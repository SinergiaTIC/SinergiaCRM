-- Create new fields in Template Section Line in field_meta_data table
INSERT INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('TemplateSectionLinehtmlcode_c', 'TemplateSectionLine', 'htmlcode_c'),
('TemplateSectionLinethumbnail_image_c', 'TemplateSectionLine', 'thumbnail_image_c'),
('TemplateSectionLinethumbnail_name_c', 'TemplateSectionLine', 'thumbnail_name_c'),

-- Update the order of existing thumbnails so they appear after SinergiaTIC's
UPDATE `templatesectionline` SET `ord` = '31' WHERE `templatesectionline`.`id` = '00000a79-d560-11ea-201c-6874d0898df6';
UPDATE `templatesectionline` SET `ord` = '32' WHERE `templatesectionline`.`id` = '00000da3-ace4-5b2d-16a3-6874d0ed297a';
UPDATE `templatesectionline` SET `ord` = '33' WHERE `templatesectionline`.`id` = '0000019b-32f0-8f71-6eea-6874d057dfa0';
UPDATE `templatesectionline` SET `ord` = '34' WHERE `templatesectionline`.`id` = '000000f4-7058-0b7e-8308-6874d094f513';
UPDATE `templatesectionline` SET `ord` = '35' WHERE `templatesectionline`.`id` = '0000092e-b42a-0a5c-428e-6874d057558e';
UPDATE `templatesectionline` SET `ord` = '36' WHERE `templatesectionline`.`id` = '00000e7a-8b33-9d23-4378-6874d0e10154';
UPDATE `templatesectionline` SET `ord` = '37' WHERE `templatesectionline`.`id` = '00000292-10f4-e889-9a88-6874d01ad6b0';
UPDATE `templatesectionline` SET `ord` = '38' WHERE `templatesectionline`.`id` = '0000026b-8378-fa6f-2224-6874d0bdf273';
UPDATE `templatesectionline` SET `ord` = '39' WHERE `templatesectionline`.`id` = '0000081b-64d2-edca-23ed-67daefbb8c1c';