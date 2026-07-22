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
SELECT UUID(), 'TTS_DEFAULT_LANGUAGE', 'es', 'TTS', 'Default TTS language', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEFAULT_LANGUAGE' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_AVAILABLE_LANGUAGES', 'es,en,ca', 'TTS', 'Available languages separated by comma', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_AVAILABLE_LANGUAGES' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEFAULT_VOICE', 'aura-2-alvaro-es', 'TTS', 'Default TTS voice', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEFAULT_VOICE' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_TEXTAREAS', '', 'TTS', 'Modules and fields for textarea buttons. Format: Module:field1,field2 | Module2:field1. Use ALL to enable on every plain textarea of a module (e.g. Contacts:ALL).', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_TEXTAREAS' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_HIGHLIGHT_FIELDS', '', 'TTS', 'Modules and fields for detail view highlight. Format: Module:field1,field2 | Module2:field1.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_HIGHLIGHT_FIELDS' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DAILY_CHAR_LIMIT', '50000', 'TTS', 'Daily character limit per user (-1 = unlimited)', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DAILY_CHAR_LIMIT' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_MAX_RECORDS_LIST', '50', 'TTS', 'Maximum records for list view mass action', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_MAX_RECORDS_LIST' AND type = 'TTS' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_LIST_SEPARATOR', 'Registro siguiente.', 'TTS', 'Audible separator between list records', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_LIST_SEPARATOR' AND type = 'TTS' AND deleted = 0);

-- TTS Deepgram credentials and config (type: TTS_DEEPGRAM)
INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEEPGRAM_API_KEY', '', 'TTS_DEEPGRAM', 'Deepgram API Key for Text-to-Speech. Leave empty to disable TTS.', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEEPGRAM_API_KEY' AND type = 'TTS_DEEPGRAM' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEEPGRAM_MODEL', 'aura-2', 'TTS_DEEPGRAM', 'Deepgram TTS model (aura-2)', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEEPGRAM_MODEL' AND type = 'TTS_DEEPGRAM' AND deleted = 0);

INSERT INTO stic_settings (id, name, value, type, description, date_entered, date_modified, deleted)
SELECT UUID(), 'TTS_DEEPGRAM_VOICE', 'aura-2-alvaro-es', 'TTS_DEEPGRAM', 'Default Deepgram TTS voice', NOW(), NOW(), 0
WHERE NOT EXISTS (SELECT 1 FROM stic_settings WHERE name = 'TTS_DEEPGRAM_VOICE' AND type = 'TTS_DEEPGRAM' AND deleted = 0);
