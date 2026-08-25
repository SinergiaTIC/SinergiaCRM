-- Portal Authentication — Email Templates & Language Defaults (English)
-- Updated: 2026-06-10 — Added password-set link to invitation templates

-- Default portal title (language-dependent string)
INSERT IGNORE INTO config (category, name, value) VALUES ('portal', 'PORTAL_TITLE', 'SinergiaCRM Portal');

-- Email Templates
DELETE FROM email_templates WHERE id='befd67fa-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='befe7460-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='beff7975-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='bf00573f-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='bf0102a9-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='bf01ff0f-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='bf02dda3-6017-11f1-8186-2299bea897ad';
DELETE FROM email_templates WHERE id='bf03d86f-6017-11f1-8186-2299bea897ad';

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('befd67fa-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Credentials (Contacts)','Sent when a CRM user sends a portal invitation to a Contact','Your portal access credentials','Hello {$contact_first_name},\n\nYour portal account is ready.\n\nAccess: {$portal_address}\nUsername: {$contact_stic_portal_username_c}\n\nClick here to set your password: {$portal_reset_link}\n\nThis link expires in 24 hours.','<p>Hello {$contact_first_name},</p><p>Your portal account is ready.</p><p>Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$contact_stic_portal_username_c}</p><p>Click here to set your password: <a href=\"{$portal_reset_link}\">Set Password</a></p><p>This link expires in 24 hours.</p>',0,'1',0,'email'),
('befe7460-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Credentials (Accounts)','Sent when a CRM user sends a portal invitation to an Account','Your portal access credentials','Hello,\n\nYour organization portal account is ready.\n\nAccess: {$portal_address}\nUsername: {$account_stic_portal_username_c}\n\nClick here to set your password: {$portal_reset_link}\n\nThis link expires in 24 hours.','<p>Hello,</p><p>Your organization portal account is ready.</p><p>Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$account_stic_portal_username_c}</p><p>Click here to set your password: <a href=\"{$portal_reset_link}\">Set Password</a></p><p>This link expires in 24 hours.</p>',0,'1',0,'email'),
('beff7975-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Password Reset','Sent when a user requests a password reset','Password Reset Request','Hello,\n\nA password reset was requested. Click to reset (valid 1 hour): {$portal_reset_link}\n\nIgnore if not requested.','<p>Hello,</p><p>A password reset was requested. Click to reset (valid 1 hour): <a href=\"{$portal_reset_link}\">Reset Password</a></p><p>Ignore if not requested.</p>',0,'1',0,'email'),
('bf00573f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Magic Link','Sent when a user requests a magic link login','Your Login Link','Hello,\n\nClick to log in: {$portal_magic_link}\n\nIgnore if not requested.','<p>Hello,</p><p>Click to log in: <a href=\"{$portal_magic_link}\">Login to Portal</a></p><p>Ignore if not requested.</p>',0,'1',0,'email'),
('bf0102a9-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Password Changed','Sent when a portal user changes their password','{$portal_title} - Your password was changed','Your portal password was changed at {$notification_time} from IP {$notification_ip}.\n\nContact support if not you.','<p>Your portal password was changed at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>',0,'1',0,'email'),
('bf01ff0f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - New Login','Sent when a new login is detected','{$portal_title} - New login detected','New login at {$notification_time}.\n\nIP: {$notification_ip}\nBrowser: {$notification_ua}\n\nContact support if not you.','<p>New login at {$notification_time}.</p><p>IP: {$notification_ip}</p><p>Browser: {$notification_ua}</p><p>Contact support if not you.</p>',0,'1',0,'email'),
('bf02dda3-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Account Locked','Sent when portal account is locked','{$portal_title} - Account locked','Account locked due to failed attempts at {$notification_time} from IP {$notification_ip}.\n\nWill unlock automatically or contact support.','<p>Account locked due to failed attempts at {$notification_time} from IP {$notification_ip}.</p><p>Will unlock automatically or contact support.</p>',0,'1',0,'email'),
('bf03d86f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Reset Requested','Sent when password reset is requested','{$portal_title} - Password reset requested','Reset requested at {$notification_time} from IP {$notification_ip}.\n\nContact support if not you.','<p>Reset requested at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>',0,'1',0,'email');

INSERT IGNORE INTO config (category, name, value) VALUES
('portal','PORTAL_TMPL_CRED_CONTACTS','befd67fa-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_CRED_ACCOUNTS','befe7460-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_RESET','beff7975-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_MAGIC','bf00573f-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_PWCHG','bf0102a9-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_LOGIN','bf01ff0f-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_LOCK','bf02dda3-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_RESET','bf03d86f-6017-11f1-8186-2299bea897ad');
INSERT IGNORE INTO config (category, name, value) VALUES ('portal', 'PORTAL_INVITATION_LIMIT', '100');
