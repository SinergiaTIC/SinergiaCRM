-- Create new fields in Template Section Line in field_meta_data table
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('TemplateSectionLinehtmlcode_c', 'TemplateSectionLine', 'htmlcode_c'),
('TemplateSectionLinethumbnail_image_c', 'TemplateSectionLine', 'thumbnail_image_c'),
('TemplateSectionLinethumbnail_name_c', 'TemplateSectionLine', 'thumbnail_name_c');

-- Update the order of existing thumbnails so they appear after SinergiaTIC's
UPDATE `templatesectionline` SET `ord` = '101' WHERE name = 'Headline';
UPDATE `templatesectionline` SET `ord` = '102' WHERE name = 'Content';
UPDATE `templatesectionline` SET `ord` = '103' WHERE name = 'Content with two columns';
UPDATE `templatesectionline` SET `ord` = '104' WHERE name = 'Content with three columns';
UPDATE `templatesectionline` SET `ord` = '105' WHERE name = 'Content with left image';
UPDATE `templatesectionline` SET `ord` = '106' WHERE name = 'Content with right image';
UPDATE `templatesectionline` SET `ord` = '107' WHERE name = 'Content with two image';
UPDATE `templatesectionline` SET `ord` = '108' WHERE name = 'Content with three image';
UPDATE `templatesectionline` SET `ord` = '109' WHERE name = 'Footer';