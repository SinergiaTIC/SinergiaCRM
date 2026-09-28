-- =====================================================================
-- Portal Authentication System - Database Migration
--
-- Portal authentication metadata.
-- Adds the custom fields on Contacts/Accounts. The portal infrastructure
-- tables (stic_portal_login_audit, stic_portal_login_attempts,
-- stic_portal_password_history, stic_portal_magic_rate_limit) are NOT created
-- here: they are hidden Basic modules (modules/stic_Portal_*) whose schema is
-- built and kept in sync by the standard vardefs + quick-repair pipeline.
-- The contacts_cstm shrink runs from this release's post_install.txt, after
-- SticUpdate's vardef/quick-repair pass.
-- =====================================================================

-- fields_meta_data entries (required for SuiteCRM to surface the portal fields)
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

