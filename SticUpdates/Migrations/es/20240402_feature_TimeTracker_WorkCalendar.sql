-- Insertamos el scheduler
INSERT INTO schedulers (id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, date_time_end, job_interval, time_from, time_to, last_run, status, catch_up) VALUES
('56dca334-679c-266d-fd9a-660bcd6ed93e', 0, NOW(), NOW(), '1', '1', 'SinergiaCRM - Validación y actualización semanal de datos', 'function::validationActions', NOW(), NULL, '*::6::*::*::0', NULL, NULL, NULL, 'Inactive', 0);

-- Insertamos las acciones de validación
REPLACE INTO stic_validation_actions (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, last_execution, `function`, report_always, priority) VALUES
('3b9f3cc9-3a16-8d5f-3822-660bc51215e0', 'Registro horario - Revisión de los registros del día anterior', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '3b9f3cc9-3a16-8d5f-3822-660bc51215e0', 0, 90),
('6eac6d58-ae3b-df60-261b-660e85c32b9a', 'Calendario laboral - Revisión de los registros del día anterior', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '6eac6d58-ae3b-df60-261b-660e85c32b9a', 0, 95),
('7acc83f4-f72e-10d5-969c-660bcb36cb56', 'Registro horario - Revisión del total de horas trabajadas por empleado durante la semana anterior', NOW(), NOW(), '1', '1', NULL, 0, '1', NULL, '7acc83f4-f72e-10d5-969c-660bcb36cb56', 0, 95);
