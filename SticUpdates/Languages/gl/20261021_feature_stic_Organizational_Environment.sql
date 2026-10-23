REPLACE INTO stic_validation_actions (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, last_execution, `function`, report_always, priority) VALUES
('9f139c90-e5f7-4f69-8b05-c634d81bb609', 'Entorno Organizacional - Cálculo de rexistro activo/inactivo', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '9f139c90-e5f7-4f69-8b05-c634d81bb609', 0, 30);

REPLACE INTO stic_validation_actions_schedulers_c (id, date_modified, deleted, stic_validation_actions_schedulersstic_validation_actions_ida, stic_validation_actions_schedulersschedulers_idb) VALUES
('7ac00558-d797-420d-b1dc-bac838f1174f', NOW(), 0, '9f139c90-e5f7-4f69-8b05-c634d81bb609', 'b05bde8a-1309-4789-993b-bf85be389f07');