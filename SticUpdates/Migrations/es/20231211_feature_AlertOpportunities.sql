REPLACE INTO schedulers
(id, deleted, date_entered, date_modified, created_by, modified_user_id, name, job, date_time_start, date_time_end, job_interval, time_from, time_to, last_run, status, catch_up)
VALUES 
('4d3b2bdc-16cf-4304-97c6-962c5e872bb1', 0, NOW(), NOW(), '1', '1', 'SinergiaCRM - Envío de alertas de subvenciones', 'function::sendOpportunityAlerts', NOW(), NULL, '0::2-6::*::*::*', NULL, NULL, NULL, 'Active', 0);