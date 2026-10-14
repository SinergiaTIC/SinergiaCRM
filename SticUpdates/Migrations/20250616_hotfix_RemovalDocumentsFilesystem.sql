-- Aumentar la longitud del campo 'module' a 100 caracteres para no cortar el nombre del "módulo"
ALTER TABLE `cron_remove_documents` MODIFY `module` VARCHAR(100) NULL;