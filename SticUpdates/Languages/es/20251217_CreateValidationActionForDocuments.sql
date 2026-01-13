-- Insertamos la acción de validación
REPLACE INTO stic_validation_actions (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, last_execution, `function`, report_always, priority) VALUES
('02546e06-37d2-ffad-868b-685bc852ebdb', 'Documentos - Actualizar el estado de documentos relacionados con el voluntariado', NOW(), NOW(), '1', '1', NULL, 0, '1', NOW(), '02546e06-37d2-ffad-868b-685bc852ebdb', 0, 100);

-- Insertamos la relación entre la tarea y la acción de validación
REPLACE INTO stic_validation_actions_schedulers_c (id, date_modified, deleted, stic_validation_actions_schedulersstic_validation_actions_ida, stic_validation_actions_schedulersschedulers_idb) VALUES
('84520e53-57fe-dedb-99f2-691d9f02a984', NOW(), 0, '02546e06-37d2-ffad-868b-685bc852ebdb', 'b05bde8a-1309-4789-993b-bf85be389f07');
