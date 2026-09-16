-- =====================================================================
-- Portal Authentication System - Database Migration
--
-- Section A: reduce the column length of 20 contacts_cstm
--            VARCHAR(255) fields. Purpose: these instances have reached
--            the InnoDB row size limit for `contacts_cstm` ("row size
--            too large" / BLOB conversion error) and the MySQL server
--            configuration is not modifiable; shrinking text-input
--            columns that never approach 255 reclaims row space without
--            data loss. Field sizes match the updated vardefs:
--              custom/Extension/modules/Contacts/Ext/Vardefs/SticVardefs.php
--            IMPORTANT: run the read-only data-length check per instance
--            BEFORE applying (SticUpdates/Checks/
--            20260915_checkContactsCstmDataLength.sql); anything over the
--            new size must be reviewed/truncated first or the ALTER
--            fails (strict mode) / silently truncates.
--            This shrink runs FIRST so the portal fields below are
--            created afterwards by the quick-repair pipeline at their
--            own (already reduced) sizes.
--
-- Section B: portal authentication metadata.
--            Adds the custom fields on Contacts/Accounts. The portal
--            infrastructure tables (stic_portal_login_audit,
--            stic_portal_login_attempts, stic_portal_password_history,
--            stic_portal_magic_rate_limit) are NOT created here: they
--            are hidden Basic modules (modules/stic_Portal_*) whose
--            schema is built and kept in sync by the standard vardefs +
--            quick-repair pipeline.
-- =====================================================================

-- 3 fields -> VARCHAR(50)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_identification_number_c`        VARCHAR(50) NULL DEFAULT NULL;

-- 1 field -> VARCHAR(150)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_tax_name_c`                     VARCHAR(150) NULL DEFAULT NULL;

-- 16 fields -> VARCHAR(100)
ALTER TABLE `contacts_cstm`
    MODIFY COLUMN `stic_professional_sector_other_c`    VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_block_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_district_c`              VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_door_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_floor_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_num_a_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_num_b_c`                 VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_postal_code_c`           VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_address_street_c`                VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_driving_licenses_c`              VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_employ_office_reg_time_c`        VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_max_commuting_time_c`            VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_state_c`                         VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_municipality_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `inc_town_c`                          VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_time_availability_c`            VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_pa_username_c`                  VARCHAR(100) NULL DEFAULT NULL,
    MODIFY COLUMN `stic_pa_password_c`                  VARCHAR(100) NULL DEFAULT NULL;

-- The new sizes keep matching the vardefs in
-- custom/Extension/modules/Contacts/Ext/Vardefs/SticVardefs.php, so the
-- quick-repair pipeline stays aligned afterwards.


-- =====================================================================
-- Portal Authentication System — Database Migration
-- Creates/updates custom fields on Contacts/Accounts. The portal
-- infrastructure tables (stic_portal_login_audit, stic_portal_login_attempts,
-- stic_portal_password_history, stic_portal_magic_rate_limit) are NOT created
-- here: they are Basic modules (modules/stic_Portal_*) whose schema is built
-- and kept in sync by the standard vardefs + quick-repair pipeline.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 2. fields_meta_data entries (required for SuiteCRM to surface them)
-- ---------------------------------------------------------------------
REPLACE INTO `fields_meta_data` (`id`, `custom_module`, `name`) VALUES
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
-- 3. Default settings in config table (REPLACE = seed or refresh to defaults)
-- ---------------------------------------------------------------------
REPLACE INTO `config` (`category`, `name`, `value`) VALUES
('portal', 'PORTAL_HOME_URL',                        ''),
('portal', 'PORTAL_LOGO',                            ''),
('portal', 'PORTAL_LOGO_WIDTH',                      '212'),
('portal', 'PORTAL_TITLE',                           'SinergiaCRM Portal'),
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
('portal', 'PORTAL_MAGIC_LINK_EXP_MIN',              '15'),
('portal', 'PORTAL_TMPL_MAGIC',                      ''),
('portal', 'PORTAL_TMPL_RESET',                      ''),
('portal', 'PORTAL_TMPL_CRED_CONTACTS',              ''),
('portal', 'PORTAL_TMPL_CRED_ACCOUNTS',              ''),
('portal', 'PORTAL_TMPL_NOTIFY_PWCHG',               ''),
('portal', 'PORTAL_TMPL_NOTIFY_LOGIN',               ''),
('portal', 'PORTAL_TMPL_NOTIFY_LOCK',                ''),
('portal', 'PORTAL_TMPL_NOTIFY_RESET',               ''),
('portal', 'PORTAL_NOTIFY_PASSWORD_CHANGED',         '1'),
('portal', 'PORTAL_NOTIFY_NEW_LOGIN',                '1'),
('portal', 'PORTAL_NOTIFY_ACCOUNT_LOCKED',           '1'),
('portal', 'PORTAL_NOTIFY_RESET_REQUESTED',          '1'),
('portal', 'PORTAL_AUDIT_RETENTION_DAYS',            '365'),
('portal', 'PORTAL_CAPTCHA_AFTER_FAILURES',          '3'),
('portal', 'PORTAL_CAPTCHA_SITE_KEY',                ''),
('portal', 'PORTAL_CAPTCHA_SECRET_KEY',              ''),
('portal', 'PORTAL_INVITATION_LIMIT',               '100');
