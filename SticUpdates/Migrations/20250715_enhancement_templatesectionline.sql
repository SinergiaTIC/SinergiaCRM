-- Create new fields in Template Section Line in field_meta_data table
INSERT INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('TemplateSectionLinehtmlcode_c', 'TemplateSectionLine', 'htmlcode_c'),
('TemplateSectionLinethumbnail_image_c', 'TemplateSectionLine', 'thumbnail_image_c'),
('TemplateSectionLinethumbnail_name_c', 'TemplateSectionLine', 'thumbnail_name_c'),

-- Update the order of existing thumbnails so they appear after SinergiaTIC's
UPDATE `templatesectionline` SET `ord` = '31' WHERE name = 'Headline';
UPDATE `templatesectionline` SET `ord` = '32' WHERE name = 'Content';
UPDATE `templatesectionline` SET `ord` = '33' WHERE name = 'Content with two columns';
UPDATE `templatesectionline` SET `ord` = '34' WHERE name = 'Content with three columns';
UPDATE `templatesectionline` SET `ord` = '35' WHERE name = 'Content with left image';
UPDATE `templatesectionline` SET `ord` = '36' WHERE name = 'Content with right image';
UPDATE `templatesectionline` SET `ord` = '37' WHERE name = 'Content with two image';
UPDATE `templatesectionline` SET `ord` = '38' WHERE name = 'Content with three image';
UPDATE `templatesectionline` SET `ord` = '39' WHERE name = 'Footer';