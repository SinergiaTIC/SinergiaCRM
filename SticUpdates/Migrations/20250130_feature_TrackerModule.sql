-- Actualiza los registros existentes que no tienen el assigned_user_id o es nulo, añadiéndole el user_id

UPDATE `tracker` SET `assigned_user_id` = `user_id` WHERE `assigned_user_id` IS NULL OR `assigned_user_id` = '';

-- Actualiza los registros existentes que el action es editview cambiandolo por update

UPDATE `tracker` SET `action` = 'update' WHERE `action` = 'editview';