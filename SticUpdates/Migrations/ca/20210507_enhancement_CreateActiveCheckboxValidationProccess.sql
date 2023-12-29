-- Insert new validation action
INSERT INTO stic_validation_actions (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, last_execution, `function`, report_always, priority) VALUES
('0b5b5d41-ae84-11eb-9b56-0242ac180004', 'Compromisos de pagament - Càlcul de registre actiu/inactiu', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '0b5b5d41-ae84-11eb-9b56-0242ac180004', 0, 30),
('8cd4b3ba-b273-11eb-b5ab-0242ac1e0002', 'Relacions amb Persones - Càlcul de registre actiu/inactiu', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '8cd4b3ba-b273-11eb-b5ab-0242ac1e0002', 0, 30),
('23d660da-b276-11eb-b5ab-0242ac1e0002', 'Relacions amb Organitzacions - Càlcul de registre actiu/inactiu', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '23d660da-b276-11eb-b5ab-0242ac1e0002', 0, 30);


-- Update scheduler names
UPDATE schedulers SET name = 'SinergiaCRM - Validació i actualització diària de dades' WHERE id= 'b05bde8a-1309-4789-993b-bf85be389f07';
