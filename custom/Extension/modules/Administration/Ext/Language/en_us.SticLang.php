<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

// SinergiaCRM admin section
$mod_strings['LBL_SINERGIACRM_TAB_TITLE'] = 'SinergiaCRM';
$mod_strings['LBL_SINERGIACRM_TAB_DESCRIPTION'] = 'Admin settings for SinergiaCRM';

// SinergiaCRM admin section items (titles and short descriptions)
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_LINK_TITLE'] = 'Validation actions';
$mod_strings['LBL_STIC_VALIDATION_ACTIONS_DESCRIPTION'] = 'Manage validation actions and link them to scheduled jobs.';

$mod_strings['LBL_STIC_VALIDATION_RESULTS_LINK_TITLE'] = 'Validation results';
$mod_strings['LBL_STIC_VALIDATION_RESULTS_DESCRIPTION'] = 'Manage and review the validation actions results.';

$mod_strings['LBL_STIC_CUSTOM_VIEWS_LINK_TITLE'] = 'Custom views';
$mod_strings['LBL_STIC_CUSTOM_VIEWS_DESCRIPTION'] = 'Module views conditional customization.';

$mod_strings['LBL_STIC_MESSAGES_QUEUE_LINK_TITLE'] = 'Phone messages queue';
$mod_strings['LBL_STIC_MESSAGES_QUEUE_DESCRIPTION'] = 'Manage the phone messages queue (SMS).';

$mod_strings['LBL_STIC_SETTINGS_LINK_TITLE'] = 'Settings';
$mod_strings['LBL_STIC_SETTINGS_DESCRIPTION'] = 'Settings management for SinergiaCRM.';

$mod_strings['LBL_STIC_TEST_DATA_LINK_TITLE'] = 'Test data';
$mod_strings['LBL_STIC_TEST_DATA_DESCRIPTION'] = 'Load or delete test data.';

$mod_strings['LBL_STIC_SINERGIADA_LINK_TITLE'] = 'Sinergia Data Analytics';
$mod_strings['LBL_STIC_SINERGIADA_DESCRIPTION'] = 'Rebuild the integration with Sinergia Data Analytics.';
$mod_strings['LBL_STIC_SINERGIADA_MAX_USERS_ERROR'] = 'Non-admin users limit in SinergiaDA exceeded. Maximum allowed: <b>__max_users__</b>. Current value: <b>__enabled_users__</b>. Deactivate the appropiate number of users and try it again.';

$mod_strings['LBL_STIC_MAIN_MENU_LINK_TITLE'] = 'Main menu';
$mod_strings['LBL_STIC_MAIN_MENU_DESCRIPTION'] = 'Set main menu structure and content';

// Test data
$mod_strings['LBL_STIC_TEST_DATA_NOTICE'] = "<strong>Important:</strong> Loaded sample records should not be used to store real data, since they can be deleted in the future.";
$mod_strings['LBL_STIC_TEST_DATA_INSERT_LINK_TITLE'] = 'Load test dataset';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_DESCRIPTION'] = 'Load a sample dataset in order to help in SinergiaCRM learning process. This data might be freely deleted at any time.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_SUCCESS'] = 'Test dataset succesfully loaded.';
$mod_strings['LBL_STIC_TEST_DATA_INSERT_ERROR'] = 'Errors have occurred while loading the test dataset. Please review the <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_insertSticData">log</a>.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_LINK_TITLE'] = 'Delete test dataset';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_DESCRIPTION'] = 'Delete the sample dataset previously loaded.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_SUCCESS'] = 'Test dataset succesfully deleted.';
$mod_strings['LBL_STIC_TEST_DATA_REMOVE_ERROR'] = 'Errors have occurred while deleting the test dataset. Please review the <a target="_blank" href="index.php?action=LogView&module=Configurator&doaction=all&filter=action_insertSticData">log</a>.';

// SinergiaDA
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_LINK_TITLE'] = 'Rebuild now';
$mod_strings['LBL_STIC_RUN_SDA_ACTIONS_DESCRIPTION'] = 'Rebuild and repair the views and other necessary elements for integration with Sinergia Data Analytics. Add new fields if needed.';
$mod_strings['LBL_STIC_GO_TO_SDA_LINK_TITLE'] = 'Go to Sinergia Data Analytics';
$mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG'] = 'Rebuild of Sinergia Data Analytics has been successfully completed.';
$mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG'] = 'The following errors have been found in the rebuild of Sinergia Data Analytics. Please contact SinergiaTIC technical support if needed.';

// Advanced main menu
$mod_strings['LBL_STIC_MENU_CONFIGURE_TITLE'] = 'Main menu settings';
$mod_strings['LBL_STIC_MENU_ENABLED_NOT_INCLUDED'] = 'Enabled modules not included in the menu';
$mod_strings['LBL_STIC_MENU_ENABLED_INCLUDED'] = 'Menu configuration';
$mod_strings['LBL_STIC_MENU_SAVE'] = 'Save and apply';
$mod_strings['LBL_STIC_MENU_RESTORE'] = 'Restore';
$mod_strings['LBL_STIC_MENU_RESTORE_CONFIRM'] = 'Restore the default SinergiaCRM menu?';
$mod_strings['LBL_STIC_MENU_INFO'] = 'The main menu contains two types of elements: on the one hand, shortcuts to the different SinergiaCRM modules and, on the other, support nodes that can be used to group modules, link to other websites, etc. The latter are identified by a coloured mark in the lower right corner. To include a module in the main menu it must be <a href="index.php?module=Administration&action=ConfigureTabs" target="_blank">enabled</a>. If it already is, you can drag it from the non-included modules area (right) to the menu node where you want it to appear (left). To reorganise the menu, drag any element to the desired position. Using the right mouse button, you can display the context menu associated with each node, which will allow you to create new nodes (which in the case of support nodes can point to any URL), duplicate them, rename them (only in the case of support nodes) or delete them. Module renaming must be done in <a href="index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs">Rename Modules</a>.';
$mod_strings['LBL_STIC_MENU_ICONS'] = 'Show module icons';
$mod_strings['LBL_STIC_MENU_ALL'] = 'Show ALL option';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE'] = 'Create';
$mod_strings['LBL_STIC_MENU_COMMAND_CREATE_DEFAULT'] = 'New node';
$mod_strings['LBL_STIC_MENU_COMMAND_RENAME'] = 'Rename';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL'] = 'Edit URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT'] = 'Enter the URL';
$mod_strings['LBL_STIC_MENU_COMMAND_EDITURL_PROMPT_VALIDATE'] = 'Please enter a valid URL';
$mod_strings['LBL_STIC_MENU_COMMAND_REMOVE'] = 'Remove';
$mod_strings['LBL_STIC_MENU_COMMAND_DUPLICATE'] = 'Duplicate';
$mod_strings['LBL_STIC_MENU_COMMAND_NEW_MAIN_NODE'] = 'New main node';
$mod_strings['LBL_STIC_MENU_COMMAND_EXPAND'] = 'Expand tree';
$mod_strings['LBL_STIC_MENU_COMMAND_COLLAPSE'] = 'Collapse tree';

// SuiteCRM modified strings
$mod_strings['LBL_CONFIGURE_GROUP_TABS'] = 'Subpanel grouping';
$mod_strings['LBL_CONFIGURE_GROUP_TABS_DESC'] = 'Configure how subpanels are grouped in the detail views';

// Other strings
$mod_strings['LBL_TRACKERS_TITLE'] = 'Tracker';
$mod_strings['LBL_TRACKERS_DESCRIPTION'] = 'Logging of user sessions and record actions.';
$mod_strings['LBL_ADMIN_ACTIONS'] = 'Admin actions';
$mod_strings['ERR_SYS_GEN_PWD_TPL_NOT_SELECTED'] = 'Set the email template that will be sent when the system generates the password of a new user.';

// OAuth authentication
$mod_strings['LBL_OAUTH_AUTHENTICATION_TITLE'] = 'OAuth authentication';
$mod_strings['LBL_OAUTH_AUTH_ENABLE'] = 'Enable OAuth authentication';
$mod_strings['LBL_OAUTH_AUTH_ENABLE_HELP'] = 'When enabling this option users will be able to authenticate using OAuth 2.0, in addition to using a username and password. After enabling it at least one of the external providers must be configured. For more information, see the <a href="https://wiki.sinergiatic.org/index.php?title=Usuarios,_Roles,_Grupos_de_seguridad_y_Registro_de_cambios#Autenticaci%C3%B3n_OAuth" target="_blank">documentation</a>.';

// Portal Authentication admin labels
$mod_strings['LBL_STIC_PORTAL_CONFIG_LINK_TITLE'] = 'Portal Configuration';
$mod_strings['LBL_STIC_PORTAL_CONFIG_DESCRIPTION'] = 'Configure portal authentication, security, branding, and email templates';
$mod_strings['LBL_STIC_PORTAL_GENERAL'] = 'General Settings';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_POLICIES'] = 'Password Policies';
$mod_strings['LBL_STIC_PORTAL_SECURITY'] = 'Security Settings';
$mod_strings['LBL_STIC_PORTAL_MAGIC_LINK'] = 'Magic Link Settings';
$mod_strings['LBL_STIC_PORTAL_EMAIL_TEMPLATES'] = 'Email Templates';
$mod_strings['LBL_STIC_PORTAL_NOTIFICATIONS'] = 'Security Notifications';
$mod_strings['LBL_STIC_PORTAL_BULK_ACTIONS'] = 'Bulk Actions';
$mod_strings['LBL_STIC_PORTAL_LOGIN_AUDIT'] = 'Login Audit Log';
$mod_strings['LBL_STIC_PORTAL_TITLE'] = 'Portal Title';
$mod_strings['LBL_STIC_PORTAL_HOME_URL'] = 'Home URL';
$mod_strings['LBL_STIC_PORTAL_LOGO'] = 'Portal Logo';
$mod_strings['LBL_STIC_PORTAL_LOGO_WIDTH'] = 'Logo Max Width';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_MIN_LENGTH'] = 'Password minimum length';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_HISTORY'] = 'Password history count';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_UPPER'] = 'Password should contain uppercase characters';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_LOWER'] = 'Password should contain lowercase characters';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_NUMBER'] = 'Password should contain numbers';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_SPECIAL'] = 'Password should contain special characters';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_EXPIRATION'] = 'Password expiration';
$mod_strings['LBL_STIC_PORTAL_MAX_ATTEMPTS'] = 'Max Failed Attempts';
$mod_strings['LBL_STIC_PORTAL_LOCKOUT_DURATION'] = 'Lockout Duration';
$mod_strings['LBL_STIC_PORTAL_REMEMBER_ME'] = 'Remember Me Days';
$mod_strings['LBL_STIC_PORTAL_SESSION_TIMEOUT'] = 'Session Timeout';
$mod_strings['LBL_STIC_PORTAL_AUDIT_RETENTION'] = 'Audit Retention';
$mod_strings['LBL_STIC_PORTAL_CONCURRENT_SESSIONS'] = 'Concurrent Sessions';
$mod_strings['LBL_STIC_PORTAL_INVITATION_LIMIT'] = 'Invitation Batch Limit';
$mod_strings['LBL_STIC_PORTAL_RECORDS'] = 'Records';
$mod_strings['LBL_STIC_PORTAL_MAGIC_ENABLED'] = 'Enable Magic Link';
$mod_strings['LBL_STIC_PORTAL_MAGIC_EXPIRATION'] = 'Magic Link Expiration';
$mod_strings['LBL_STIC_PORTAL_CHARACTERS'] = 'Characters';
$mod_strings['LBL_STIC_PORTAL_ATTEMPTS'] = 'Attempts';
$mod_strings['LBL_STIC_PORTAL_MINUTES'] = 'Minutes';
$mod_strings['LBL_STIC_PORTAL_DAYS'] = 'Days';
$mod_strings['LBL_STIC_PORTAL_DISABLED'] = '(0 = disabled)';
$mod_strings['LBL_STIC_PORTAL_NEVER'] = '(0 = never)';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_CONTACTS'] = 'Portal credentials (Contacts):';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_ACCOUNTS'] = 'Portal credentials (Accounts):';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_RESET'] = 'Password reset:';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_MAGIC'] = 'Magic link:';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_PASSWORD_CHANGED'] = 'Enable password changed notification:';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_NEW_LOGIN'] = 'Enable new login notification:';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_ACCOUNT_LOCKED'] = 'Enable account locked notification:';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_RESET_REQUESTED'] = 'Enable reset requested notification:';
$mod_strings['LBL_STIC_PORTAL_CREATE'] = 'Create';
$mod_strings['LBL_STIC_PORTAL_EDIT'] = 'Edit';
$mod_strings['LBL_STIC_PORTAL_VIEW_LOG'] = 'View Log';
$mod_strings['LBL_STIC_PORTAL_CLEAR_LOCKOUTS'] = 'Clear All Lockouts';
$mod_strings['LBL_STIC_PORTAL_CLEAR_LOCKOUTS_HELP'] = 'Remove all portal account lockouts at once. Locked accounts are automatically unlocked after the configured lockout duration. Use this to manually unlock all accounts immediately.';
$mod_strings['LBL_STIC_PORTAL_CLEAR_SESSIONS'] = 'Clear All Sessions';
$mod_strings['LBL_STIC_PORTAL_CLEAR_SESSIONS_HELP'] = 'Force logout of all active portal sessions. This invalidates all current portal login sessions. Users will need to log in again.';
$mod_strings['LBL_STIC_PORTAL_CONFIRM_CLEAR_LOCKOUTS'] = 'Are you sure you want to clear all portal lockouts?';
$mod_strings['LBL_STIC_PORTAL_CONFIRM_CLEAR_SESSIONS'] = 'Are you sure you want to clear all portal sessions?';
$mod_strings['LBL_STIC_PORTAL_TITLE_HELP'] = 'Portal name shown on login page and emails.';
$mod_strings['LBL_STIC_PORTAL_HOME_URL_HELP'] = 'Redirect users here after login. Leave empty to show welcome page.';
$mod_strings['LBL_STIC_PORTAL_LOGO_WIDTH_HELP'] = 'Maximum width in pixels for the logo on the portal login page.';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_MIN_LENGTH_HELP'] = 'Minimum number of characters required for portal passwords.';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_HISTORY_HELP'] = 'Prevent reuse of the last N passwords. Set to 0 to disable.';
$mod_strings['LBL_STIC_PORTAL_PASSWORD_EXPIRATION_HELP'] = 'Number of days before a password expires and must be changed. Set to 0 to disable.';
$mod_strings['LBL_STIC_PORTAL_MAX_ATTEMPTS_HELP'] = 'Number of consecutive failed login attempts before account is locked.';
$mod_strings['LBL_STIC_PORTAL_LOCKOUT_DURATION_HELP'] = 'Number of minutes an account remains locked after exceeding max failed attempts.';
$mod_strings['LBL_STIC_PORTAL_REMEMBER_ME_HELP'] = 'Number of days the Remember Me cookie remains valid.';
$mod_strings['LBL_STIC_PORTAL_SESSION_TIMEOUT_HELP'] = 'Number of minutes of inactivity before the portal session expires.';
$mod_strings['LBL_STIC_PORTAL_AUDIT_RETENTION_HELP'] = 'Number of days to keep login audit records before purging.';
$mod_strings['LBL_STIC_PORTAL_INVITATION_LIMIT_HELP'] = 'Maximum number of records that can be invited at once from the list view mass action. Prevents timeouts when sending large batches of invitation emails synchronously.';
$mod_strings['LBL_STIC_PORTAL_MAGIC_ENABLED_HELP'] = 'Allow users to log in via a one-time link sent to their email, without entering a password.';
$mod_strings['LBL_STIC_PORTAL_MAGIC_EXPIRATION_HELP'] = 'Number of minutes the magic link remains valid after being sent.';
$mod_strings['LBL_STIC_PORTAL_LOGIN_AUDIT_HELP'] = 'View login attempts (success and failure) with IP, user agent, and timestamp.';
$mod_strings["LBL_STIC_PORTAL_ACTIONS"] = "Portal Actions";
$mod_strings["LBL_STIC_PORTAL_APPLY_TAB"] = "Apply Portal Tab to Contacts";
$mod_strings["LBL_STIC_PORTAL_APPLY_TAB_HELP"] = "Adds the Portal Authentication tab to all Contacts' DetailView and EditView. Use this when Contacts were created before the portal feature was installed and are missing the portal fields tab.";
$mod_strings["LBL_STIC_PORTAL_APPLY_TAB_BUTTON"] = "Add Portal Tab to All Contacts";
$mod_strings["LBL_STIC_PORTAL_APPLY_TAB_CONFIRM"] = "This will rebuild the Contacts view metadata. Continue?";
$mod_strings["LBL_STIC_PORTAL_REMOVE_TAB_BUTTON"] = "Remove Portal Tab";
$mod_strings["LBL_STIC_PORTAL_REMOVE_TAB_CONFIRM"] = "This will remove the Portal Authentication tab from all Contacts views. Continue?";
$mod_strings['LBL_STIC_PORTAL_APPLY_TAB_CONTACTS'] = 'Contacts';
$mod_strings['LBL_STIC_PORTAL_APPLY_TAB_ACCOUNTS'] = 'Accounts';

// Email template variable help
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_CONTACTS_HELP'] = 'Available variables:<br><b>&#123;$contact_first_name&#125;</b> — Contact first name<br><b>&#123;$contact_last_name&#125;</b> — Contact last name<br><b>&#123;$contact_name&#125;</b> — Contact full name<br><b>&#123;$contact_stic_portal_username_c&#125;</b> — Portal username<br><b>&#123;$portal_address&#125;</b> — Portal/app URL<br><b>&#123;$portal_reset_link&#125;</b> — Password setup link<br><b>&#123;$portal_title&#125;</b> — Portal title';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_ACCOUNTS_HELP'] = 'Available variables:<br><b>&#123;$account_stic_portal_username_c&#125;</b> — Portal username<br><b>&#123;$portal_address&#125;</b> — Portal/app URL<br><b>&#123;$portal_reset_link&#125;</b> — Password setup link<br><b>&#123;$portal_title&#125;</b> — Portal title';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_RESET_HELP'] = 'Available variables:<br><b>&#123;$portal_reset_link&#125;</b> — Password reset link (valid 1 hour)<br><b>&#123;$portal_title&#125;</b> — Portal title';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_MAGIC_HELP'] = 'Available variables:<br><b>&#123;$portal_magic_link&#125;</b> — One-click login link<br><b>&#123;$portal_title&#125;</b> — Portal title';
$mod_strings['LBL_STIC_PORTAL_TEMPLATE_NOTIFY_HELP'] = 'Each security notification can be enabled or disabled using the checkbox next to its label. When disabled, no email will be sent for that event.<br><br>Select an email template to customize the message content. If no template is selected, a default message will be used.<br><br>Available variables in notification templates:<br><b>&#123;$notification_time&#125;</b> — Event date/time<br><b>&#123;$notification_ip&#125;</b> — IP address of the user<br><b>&#123;$notification_ua&#125;</b> — Browser / user-agent<br><b>&#123;$notification_event&#125;</b> — Event type (e.g. new_login, password_changed)<br><b>&#123;$portal_title&#125;</b> — Portal title from General Settings<br><b>&#123;$contact_name&#125;</b> — Contact or account name';

$mod_strings['LBL_STIC_PORTAL_NOTIFICATIONS_HELP'] = 'Security notifications inform portal users about important account events via email. Tick a checkbox to enable a notification, then optionally choose an email template to customise the message. If no template is selected a built-in default is used. Notifications that are not ticked will never be sent.';

$mod_strings['LBL_STIC_PORTAL_NOTIFY_PASSWORD_CHANGED_HELP'] = 'Sent when the user successfully changes their portal password. Tick the checkbox to enable this notification, then optionally choose a custom email template. Available variables: <b>&#123;$notification_time&#125;</b>, <b>&#123;$notification_ip&#125;</b>, <b>&#123;$notification_ua&#125;</b>, <b>&#123;$notification_event&#125;</b>, <b>&#123;$portal_title&#125;</b>, <b>&#123;$contact_name&#125;</b>.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_NEW_LOGIN_HELP'] = 'Sent on every successful portal login so the user can monitor account access. Tick the checkbox to enable, then optionally choose a template. Available variables: <b>&#123;$notification_time&#125;</b>, <b>&#123;$notification_ip&#125;</b>, <b>&#123;$notification_ua&#125;</b>, <b>&#123;$notification_event&#125;</b>, <b>&#123;$portal_title&#125;</b>, <b>&#123;$contact_name&#125;</b>.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_ACCOUNT_LOCKED_HELP'] = 'Sent when the account is locked after too many failed login attempts. The user cannot log in until the lockout expires or an admin clears it. Tick the checkbox to enable, then optionally choose a template. Available variables: <b>&#123;$notification_time&#125;</b>, <b>&#123;$notification_ip&#125;</b>, <b>&#123;$notification_ua&#125;</b>, <b>&#123;$notification_event&#125;</b>, <b>&#123;$portal_title&#125;</b>, <b>&#123;$contact_name&#125;</b>.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_RESET_REQUESTED_HELP'] = 'Sent when a password reset is requested via the Forgot Password link. Alerts the user if someone else tries to reset their password. Tick the checkbox to enable, then optionally choose a template. Available variables: <b>&#123;$notification_time&#125;</b>, <b>&#123;$notification_ip&#125;</b>, <b>&#123;$notification_ua&#125;</b>, <b>&#123;$notification_event&#125;</b>, <b>&#123;$portal_title&#125;</b>, <b>&#123;$contact_name&#125;</b>.';
// Portal email default subjects & bodies (used when no template is configured)
$mod_strings['LBL_STIC_PORTAL_INVITATION_SUBJECT'] = 'SinergiaCRM Portal - Access your portal';
$mod_strings['LBL_STIC_PORTAL_INVITATION_BODY'] = 'Hello,<br><br>Your portal account is ready.<br><br>Access: <a href=\"{$portal_address}\">{$portal_address}</a><br>Username: {$contact_stic_portal_username_c}<br><br>Click here to set your password: <a href=\"{$portal_reset_link}\">Set Password</a><br><br>This link expires in 24 hours.';
$mod_strings['LBL_STIC_PORTAL_RESET_SUBJECT'] = 'Password Reset Request';
$mod_strings['LBL_STIC_PORTAL_RESET_BODY'] = 'A password reset was requested. Click to reset (valid 1 hour): <a href=\"{$portal_reset_link}\">Reset Password</a><br><br>Ignore if not requested.';
$mod_strings['LBL_STIC_PORTAL_MAGIC_SUBJECT'] = 'Your Login Link';
$mod_strings['LBL_STIC_PORTAL_MAGIC_BODY'] = 'Click to log in: <a href=\"{$portal_magic_link}\">Login to Portal</a><br><br>Ignore if not requested.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_PWCHG_SUBJECT'] = '{$portal_title} - Your password was changed';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_PWCHG_BODY'] = 'Your portal password was changed at {$notification_time} from IP {$notification_ip}. Contact support if not you.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_LOGIN_SUBJECT'] = '{$portal_title} - New login detected';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_LOGIN_BODY'] = 'New login at {$notification_time}. IP: {$notification_ip}. Browser: {$notification_ua}. Contact support if not you.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_LOCK_SUBJECT'] = '{$portal_title} - Account locked';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_LOCK_BODY'] = 'Account locked due to failed attempts at {$notification_time} from IP {$notification_ip}. Will unlock automatically or contact support.';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_RESET_SUBJECT'] = '{$portal_title} - Password reset requested';
$mod_strings['LBL_STIC_PORTAL_NOTIFY_RESET_BODY'] = 'Reset requested at {$notification_time} from IP {$notification_ip}. Contact support if not you.';

// Portal Login Audit Log display labels
$mod_strings['LBL_STIC_PORTAL_BACK_CONFIG'] = 'Back to Portal Configuration';
$mod_strings['LBL_STIC_PORTAL_AUDIT_DATE'] = 'Date';
$mod_strings['LBL_STIC_PORTAL_AUDIT_USERNAME'] = 'Username';
$mod_strings['LBL_STIC_PORTAL_AUDIT_TYPE'] = 'Type';
$mod_strings['LBL_STIC_PORTAL_AUDIT_IP'] = 'IP Address';
$mod_strings['LBL_STIC_PORTAL_AUDIT_RESULT'] = 'Result';
$mod_strings['LBL_STIC_PORTAL_AUDIT_REASON_METHOD'] = 'Reason / Method';
$mod_strings['LBL_STIC_PORTAL_AUDIT_USER_AGENT'] = 'User Agent';
$mod_strings['LBL_STIC_PORTAL_AUDIT_NO_RECORDS'] = 'No records found';
$mod_strings['LBL_STIC_PORTAL_AUDIT_RESULT_SUCCESS'] = 'Success';
$mod_strings['LBL_STIC_PORTAL_AUDIT_RESULT_FAILURE'] = 'Failure';
$mod_strings['LBL_STIC_PORTAL_AUDIT_TYPE_CONTACT'] = 'Contact';
$mod_strings['LBL_STIC_PORTAL_AUDIT_TYPE_ACCOUNT'] = 'Account';
$mod_strings['LBL_STIC_PORTAL_AUDIT_TYPE_RESET_SENT'] = 'Password reset';
$mod_strings['LBL_STIC_PORTAL_AUDIT_REASON_INVALID_CREDENTIALS'] = 'Invalid credentials';
$mod_strings['LBL_STIC_PORTAL_AUDIT_REASON_NOT_FOUND'] = 'User not found';
$mod_strings['LBL_STIC_PORTAL_AUDIT_REASON_LOCKED_OUT'] = 'Account locked out';
$mod_strings['LBL_STIC_PORTAL_AUDIT_REASON_IP_LOCKED'] = 'IP locked out';
$mod_strings['LBL_STIC_PORTAL_AUDIT_METHOD_PASSWORD'] = 'Password';
$mod_strings['LBL_STIC_PORTAL_AUDIT_METHOD_INVITATION'] = 'Invitation';
$mod_strings['LBL_STIC_PORTAL_AUDIT_METHOD_MAGIC_LINK'] = 'Magic link';
$mod_strings['LBL_STIC_PORTAL_AUDIT_METHOD_ADMIN_RESET'] = 'Admin reset';
