REPLACE INTO stic_validation_actions (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, last_execution, `function`, report_always, priority) VALUES
('a8d6cdff-ff13-4a2d-b5af-dba7ed47f29c', 'Compromisos de pagament - Revisió de la data de caducitat de les targetes', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, 'a8d6cdff-ff13-4a2d-b5af-dba7ed47f29c', 0, 65);

REPLACE INTO stic_validation_actions_schedulers_c (id, date_modified, deleted, stic_validation_actions_schedulersstic_validation_actions_ida, stic_validation_actions_schedulersschedulers_idb) VALUES
('a58bba60-c60f-11ee-98c0-0242ac140002', NOW(), 0, 'a8d6cdff-ff13-4a2d-b5af-dba7ed47f29c', 'a9bebf7f-8896-46dd-8d06-77e2b5256c83');
