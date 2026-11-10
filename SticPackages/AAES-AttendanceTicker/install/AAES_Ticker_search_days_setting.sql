-- STIC-Custom AAES - 20261109 - Optional setting for the Attendance Ticker search window (IMPL-AAES-001)
-- Creates the AAES_TICKER_SEARCH_DAYS record in stic_settings. OPTIONAL: the ticker
-- works without it (default window = 30 days, capped at 365). Run this manually
-- (phpMyAdmin / mysql CLI) after installing the package if the entity wants a
-- custom window (60, 90... days) for the past-sessions searcher.
-- END STIC-Custom AAES

INSERT INTO `stic_settings`
    (`id`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `deleted`, `assigned_user_id`, `type`, `name`, `value`, `description`)
VALUES
    (UUID(), NOW(), NOW(), '1', '1', 0, '1', 'GENERAL', 'AAES_TICKER_SEARCH_DAYS', '30',
     'Pasador de asistencias (AAES): ventana en días del buscador de sesiones pasadas. 30 por defecto; valores válidos 1-365. Editable sin cambios de código.');
