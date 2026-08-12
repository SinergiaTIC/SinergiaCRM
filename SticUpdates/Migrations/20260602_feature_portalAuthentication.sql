-- =====================================================================
-- Portal Authentication System — Database Migration
-- Creates custom fields on Contacts/Accounts, new tables for password
-- history, login audit, and IP lockout, and extends oauth2tokens.
-- =====================================================================

-- ---------------------------------------------------------------------
-- 1. Custom fields on contacts_cstm and accounts_cstm
-- ---------------------------------------------------------------------
ALTER TABLE `contacts_cstm`
    ADD COLUMN IF NOT EXISTS `stic_portal_hashed_c`              VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_remember_token_c`      VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_locked_until_c`        DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_failed_attempts_c`     INT(11) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_last_login_c`          DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_password_changed_c`    DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_password_expires_c`    DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_reset_token_c`         VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_reset_expires_c`       DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_session_id_c`          VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_magic_token_c`         VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_magic_expires_c`       DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_enabled_c`             TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_force_pw_change_c`     TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_username_c`            VARCHAR(255) NULL;

ALTER TABLE `accounts_cstm`
    ADD COLUMN IF NOT EXISTS `stic_portal_hashed_c`              VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_remember_token_c`      VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_locked_until_c`        DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_failed_attempts_c`     INT(11) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_last_login_c`          DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_password_changed_c`    DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_password_expires_c`    DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_reset_token_c`         VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_reset_expires_c`       DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_session_id_c`          VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_magic_token_c`         VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_magic_expires_c`       DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `stic_portal_enabled_c`             TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_force_pw_change_c`     TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `stic_portal_username_c`            VARCHAR(255) NULL;

-- Useful indices for the most-queried columns
ALTER TABLE `contacts_cstm`
    ADD INDEX IF NOT EXISTS `idx_contacts_portal_username` (`stic_portal_username_c`);

ALTER TABLE `accounts_cstm`
    ADD INDEX IF NOT EXISTS `idx_accounts_portal_username` (`stic_portal_username_c`);

-- ---------------------------------------------------------------------
-- 2. fields_meta_data entries (required for SuiteCRM to surface them)
-- ---------------------------------------------------------------------
INSERT IGNORE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
('Contactsstic_portal_hashed_c',           'Contacts', 'stic_portal_hashed_c'),
('Contactsstic_portal_remember_token_c',   'Contacts', 'stic_portal_remember_token_c'),
('Contactsstic_portal_locked_until_c',     'Contacts', 'stic_portal_locked_until_c'),
('Contactsstic_portal_failed_attempts_c',  'Contacts', 'stic_portal_failed_attempts_c'),
('Contactsstic_portal_last_login_c',       'Contacts', 'stic_portal_last_login_c'),
('Contactsstic_portal_password_changed_c', 'Contacts', 'stic_portal_password_changed_c'),
('Contactsstic_portal_password_expires_c', 'Contacts', 'stic_portal_password_expires_c'),
('Contactsstic_portal_reset_token_c',      'Contacts', 'stic_portal_reset_token_c'),
('Contactsstic_portal_reset_expires_c',    'Contacts', 'stic_portal_reset_expires_c'),
('Contactsstic_portal_session_id_c',       'Contacts', 'stic_portal_session_id_c'),
('Contactsstic_portal_magic_token_c',      'Contacts', 'stic_portal_magic_token_c'),
('Contactsstic_portal_magic_expires_c',    'Contacts', 'stic_portal_magic_expires_c'),
('Contactsstic_portal_enabled_c',          'Contacts', 'stic_portal_enabled_c'),
('Contactsstic_portal_force_pw_change_c',  'Contacts', 'stic_portal_force_pw_change_c'),
('Contactsstic_portal_username_c',         'Contacts', 'stic_portal_username_c'),
('Accountsstic_portal_hashed_c',           'Accounts', 'stic_portal_hashed_c'),
('Accountsstic_portal_remember_token_c',   'Accounts', 'stic_portal_remember_token_c'),
('Accountsstic_portal_locked_until_c',     'Accounts', 'stic_portal_locked_until_c'),
('Accountsstic_portal_failed_attempts_c',  'Accounts', 'stic_portal_failed_attempts_c'),
('Accountsstic_portal_last_login_c',       'Accounts', 'stic_portal_last_login_c'),
('Accountsstic_portal_password_changed_c', 'Accounts', 'stic_portal_password_changed_c'),
('Accountsstic_portal_password_expires_c', 'Accounts', 'stic_portal_password_expires_c'),
('Accountsstic_portal_reset_token_c',      'Accounts', 'stic_portal_reset_token_c'),
('Accountsstic_portal_reset_expires_c',    'Accounts', 'stic_portal_reset_expires_c'),
('Accountsstic_portal_session_id_c',       'Accounts', 'stic_portal_session_id_c'),
('Accountsstic_portal_magic_token_c',      'Accounts', 'stic_portal_magic_token_c'),
('Accountsstic_portal_magic_expires_c',    'Accounts', 'stic_portal_magic_expires_c'),
('Accountsstic_portal_enabled_c',          'Accounts', 'stic_portal_enabled_c'),
('Accountsstic_portal_force_pw_change_c',  'Accounts', 'stic_portal_force_pw_change_c'),
('Accountsstic_portal_username_c',         'Accounts', 'stic_portal_username_c');

-- ---------------------------------------------------------------------
-- 3. Password history (reuse-prevention)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stic_portal_password_history` (
    `id` CHAR(36) NOT NULL,
    `parent_id` CHAR(36) NOT NULL,
    `parent_type` VARCHAR(20) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `date_entered` DATETIME NOT NULL,
    `date_modified` DATETIME NOT NULL,
    `deleted` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `idx_pwdhist_parent` (`parent_id`, `parent_type`, `date_entered`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 4. Login audit trail
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stic_portal_login_audit` (
    `id` CHAR(36) NOT NULL,
    `parent_id` CHAR(36) NULL,
    `parent_type` VARCHAR(20) NULL,
    `username` VARCHAR(255) NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(500) NULL,
    `success` TINYINT(1) NOT NULL DEFAULT 0,
    `failure_reason` VARCHAR(100) NULL,
    `auth_method` VARCHAR(20) NULL,
    `date_entered` DATETIME NOT NULL,
    `date_modified` DATETIME NOT NULL,
    `deleted` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `idx_audit_parent_date` (`parent_id`, `date_entered`),
    INDEX `idx_audit_username_date` (`username`, `date_entered`),
    INDEX `idx_audit_ip_date` (`ip_address`, `date_entered`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 5. IP-based lockout tracking
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stic_portal_login_attempts` (
    `id` CHAR(36) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `failed_attempts` INT(11) DEFAULT 1,
    `locked_until` DATETIME NULL,
    `last_attempt` DATETIME NOT NULL,
    `date_entered` DATETIME NOT NULL,
    `date_modified` DATETIME NOT NULL,
    `deleted` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `idx_attempts_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 6. Magic link rate limit (per IP, per username)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stic_portal_magic_rate_limit` (
    `id` CHAR(36) NOT NULL,
    `identifier` VARCHAR(255) NOT NULL,  -- username or IP
    `identifier_type` VARCHAR(20) NOT NULL,
    `window_start` DATETIME NOT NULL,
    `count` INT(11) DEFAULT 1,
    PRIMARY KEY (`id`),
    INDEX `idx_magic_id` (`identifier`, `identifier_type`, `window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- 7. Default settings in config table (only if not already set)
-- ---------------------------------------------------------------------
INSERT IGNORE INTO `config` (`category`, `name`, `value`) VALUES
('portal', 'PORTAL_HOME_URL',                        ''),
('portal', 'PORTAL_LOGO',                            ''),
('portal', 'PORTAL_LOGO_WIDTH',                      '212'),
('portal', 'PORTAL_PASSWORD_MIN_LENGTH',             '8'),
('portal', 'PORTAL_PASSWORD_REQUIRE_UPPER',          '0'),
('portal', 'PORTAL_PASSWORD_REQUIRE_LOWER',          '0'),
('portal', 'PORTAL_PASSWORD_REQUIRE_NUMBER',         '0'),
('portal', 'PORTAL_PASSWORD_REQUIRE_SPECIAL',        '0'),
('portal', 'PORTAL_PASSWORD_EXPIRATION_DAYS',        '0'),
('portal', 'PORTAL_PASSWORD_HISTORY_COUNT',          '0'),
('portal', 'PORTAL_MAX_FAILED_ATTEMPTS',             '5'),
('portal', 'PORTAL_LOCKOUT_DURATION_MINUTES',        '30'),
('portal', 'PORTAL_REMEMBER_ME_DAYS',                '30'),
('portal', 'PORTAL_SESSION_TIMEOUT_MINUTES',         '60'),
('portal', 'PORTAL_ALLOW_CONCURRENT_SESSIONS',       '0'),
('portal', 'PORTAL_MAGIC_LINK_ENABLED',              '0'),
('portal', 'PORTAL_MAGIC_LINK_EXPIRATION_MINUTES',   '15'),
('portal', 'PORTAL_MAGIC_LINK_TEMPLATE',             ''),
('portal', 'PORTAL_RESET_TEMPLATE',                  ''),
('portal', 'PORTAL_CREDENTIALS_TEMPLATE_CONTACTS',   ''),
('portal', 'PORTAL_CREDENTIALS_TEMPLATE_ACCOUNTS',   ''),
('portal', 'PORTAL_NOTIFY_PASSWORD_CHANGED',         '1'),
('portal', 'PORTAL_NOTIFY_NEW_LOGIN',                '1'),
('portal', 'PORTAL_NOTIFY_ACCOUNT_LOCKED',           '1'),
('portal', 'PORTAL_NOTIFY_RESET_REQUESTED',          '1'),
('portal', 'PORTAL_AUDIT_RETENTION_DAYS',            '365'),
('portal', 'PORTAL_CAPTCHA_AFTER_FAILURES',          '3'),
('portal', 'PORTAL_CAPTCHA_SITE_KEY',                ''),
('portal', 'PORTAL_CAPTCHA_SECRET_KEY',              '');
('portal', 'PORTAL_INVITATION_LIMIT',               '100');
