-- Portal Authentication — Email Templates & Language Defaults (English)

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
('befd67fa-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Credentials (Contacts)','Sent when portal access is enabled for a Contact','Your portal access credentials','<p>Hello {$contact_first_name},</p><p>Your portal account has been created. Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$contact_stic_portal_username_c}</p>','<p>Hello {$contact_first_name},</p><p>Your portal account has been created. Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$contact_stic_portal_username_c}</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('befe7460-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Credentials (Accounts)','Sent when portal access is enabled for an Account','Your portal access credentials','<p>Hello,</p><p>Your organization portal account has been created. Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$account_stic_portal_username_c}</p>','<p>Hello,</p><p>Your organization portal account has been created. Access: <a href=\"{$portal_address}\">{$portal_address}</a></p><p>Username: {$account_stic_portal_username_c}</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('beff7975-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Password Reset','Sent when a user requests a password reset','Password Reset Request','<p>Hello,</p><p>A password reset was requested. Click to reset (valid 1 hour): <a href=\"{$portal_reset_link}\">Reset Password</a></p><p>Ignore if not requested.</p>','<p>Hello,</p><p>A password reset was requested. Click to reset (valid 1 hour): <a href=\"{$portal_reset_link}\">Reset Password</a></p><p>Ignore if not requested.</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('bf00573f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Magic Link','Sent when a user requests a magic link login','Your Login Link','<p>Hello,</p><p>Click to log in: <a href=\"{$portal_magic_link}\">Login to Portal</a></p><p>Ignore if not requested.</p>','<p>Hello,</p><p>Click to log in: <a href=\"{$portal_magic_link}\">Login to Portal</a></p><p>Ignore if not requested.</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('bf0102a9-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Password Changed','Sent when a portal user changes their password','{$portal_title} - Your password was changed','<p>Your portal password was changed at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>','<p>Your portal password was changed at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('bf01ff0f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - New Login','Sent when a new login is detected','{$portal_title} - New login detected','<p>New login at {$notification_time}.</p><p>IP: {$notification_ip}</p><p>Browser: {$notification_ua}</p><p>Contact support if not you.</p>','<p>New login at {$notification_time}.</p><p>IP: {$notification_ip}</p><p>Browser: {$notification_ua}</p><p>Contact support if not you.</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('bf02dda3-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Account Locked','Sent when portal account is locked','{$portal_title} - Account locked','<p>Account locked due to failed attempts at {$notification_time} from IP {$notification_ip}.</p><p>Will unlock automatically or contact support.</p>','<p>Account locked due to failed attempts at {$notification_time} from IP {$notification_ip}.</p><p>Will unlock automatically or contact support.</p>',0,'1',0,'email');

INSERT INTO email_templates (id, date_entered, date_modified, modified_user_id, created_by, published, name, description, subject, body, body_html, deleted, assigned_user_id, text_only, type) VALUES
('bf03d86f-6017-11f1-8186-2299bea897ad',NOW(),NOW(),'1','1','yes','Portal Notification - Reset Requested','Sent when password reset is requested','{$portal_title} - Password reset requested','<p>Reset requested at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>','<p>Reset requested at {$notification_time} from IP {$notification_ip}.</p><p>Contact support if not you.</p>',0,'1',0,'email');

INSERT IGNORE INTO config (category, name, value) VALUES
('portal','PORTAL_TMPL_CRED_CONTACTS','befd67fa-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_CRED_ACCOUNTS','befe7460-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_RESET','beff7975-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_MAGIC','bf00573f-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_PWCHG','bf0102a9-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_LOGIN','bf01ff0f-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_LOCK','bf02dda3-6017-11f1-8186-2299bea897ad'),
('portal','PORTAL_TMPL_NOTIFY_RESET','bf03d86f-6017-11f1-8186-2299bea897ad');
