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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings ['LBL_SENDMESSAGE'] = 'Send message';

// STIC-Custom 20260927 AAM - Portal Actions workflow action labels
$mod_strings['LBL_PORTALACTIONS'] = 'Portal Actions';
$mod_strings['LBL_PORTAL_ACTIONS_TYPE'] = 'Action';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP'] = 'Target App for Redirect';
$mod_strings['LBL_PORTAL_ACTIONS_GENERIC_APP'] = 'Generic (no specific app)';
$mod_strings['LBL_PORTAL_ACTIONS_INVITATION'] = 'Send Invitation Email';
$mod_strings['LBL_PORTAL_ACTIONS_RESET'] = 'Send Password Reset';
$mod_strings['LBL_PORTAL_ACTIONS_MODULES_HELP'] = 'This action only applies to workflows on the Contacts and Accounts modules.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT'] = 'Default (from Portal Configuration)';
// STIC-Custom 20261102 AAM - Portal Actions workflow action inline help
$mod_strings['LBL_PORTAL_ACTIONS_TYPE_HELP'] = '<b>Send Invitation Email</b> — creates the portal user (if needed), enables portal access and sends the credentials with a first-time password setup link (valid 24 hours).<br><b>Send Password Reset</b> — sends a one-hour link to set a new password for an existing portal user. In both cases, if the record has no portal username, it is filled from the source configured in Portal Configuration.';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP_HELP'] = 'Portal app the invitation points to. It is recorded in the audit log, fills the $portal_app_name template variable and is used as the redirect after setting the password. "Generic (no specific app)" sends no app reference.';
$mod_strings['LBL_PORTAL_ACTIONS_INDIVIDUAL_HELP'] = 'Checked: one email per record (each recipient gets a separate message). Unchecked: a single email with all the recipient addresses in the "To" field.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_HELP'] = 'Defaults to the template selected in Portal Configuration for the current action type; pick another one to override it for this workflow. Variables available in the template:<br><br>• Any field of the record, e.g. $contact_first_name, $account_name<br>• <b>$portal_app_name</b> — target app name (falls back to the portal title)<br>• <b>$portal_address</b> — portal/app URL<br>• <b>$portal_reset_link</b> — password setup link (invitation, 24 h) or reset link (password reset, 1 h)<br>• <b>$portal_title</b> — portal title';

