-- SinergiaCRM TTS (Text-to-Speech) - Installation SQL
-- Creates usage tracking table and inserts default stic_settings
-- Run automatically by post_install.php

CREATE TABLE IF NOT EXISTS tts_usage (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    created_at DATETIME NOT NULL,
    char_count INT NOT NULL DEFAULT 0,
    language VARCHAR(10),
    module VARCHAR(100),
    provider VARCHAR(50),
    scenario VARCHAR(1),
    record_id VARCHAR(36),
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TTS business settings (type: TTS)
INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_ENABLED', '1', 'TTS', 'Master switch: 1=enabled, 0=disabled (buttons will not appear)', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_ENABLED' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEFAULT_LANGUAGE', 'es', 'TTS', 'Default TTS language (e.g. es, en, ca)', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEFAULT_LANGUAGE' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEFAULT_VOICE', 'aura-2-alvaro-es', 'TTS', 'Default TTS voice', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEFAULT_VOICE' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_TEXTAREAS', 'Ver descripcion', 'TTS', 'Formato: Modulo:campo1,campo2|OtroModulo:campo1. Ej: Accounts:description|Notes:description. Usar ALL para todos los textarea. Ver README.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_TEXTAREAS' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_HIGHLIGHT_FIELDS', 'Ver descripcion', 'TTS', 'Formato: Modulo:campo1,campo2|OtroModulo:campo1. Ej: Accounts:name,phone_office,billing_address_city. Ver README.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_HIGHLIGHT_FIELDS' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DAILY_CHAR_LIMIT', '50000', 'TTS', 'Daily character limit per user (-1 = unlimited)', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DAILY_CHAR_LIMIT' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DAILY_TIME_LIMIT', '-1', 'TTS', 'Daily time limit in minutes per user (-1 = unlimited). Estimate: chars/10/60.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DAILY_TIME_LIMIT' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_MAX_RECORDS_LIST', '50', 'TTS', 'Maximum records for list view mass action', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_MAX_RECORDS_LIST' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_LIST_SEPARATOR', 'Registro siguiente.', 'TTS', 'Audible separator between list records', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_LIST_SEPARATOR' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_BAR_COLOR', '#181818', 'TTS', 'Background color of the TTS player bar. Use hex format (e.g. #181818).', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_BAR_COLOR' AND type = 'TTS' AND deleted = 0);

-- TTS Deepgram credentials (type: TTS_DEEPGRAM)
INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEEPGRAM_API_KEY', '', 'TTS_DEEPGRAM', 'Deepgram API Key for Text-to-Speech. Leave empty to disable TTS.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEEPGRAM_API_KEY' AND type = 'TTS_DEEPGRAM' AND deleted = 0);
