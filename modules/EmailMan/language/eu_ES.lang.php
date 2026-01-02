<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings = array(
    'LBL_SEND_DATE_TIME' => '',
    'LBL_IN_QUEUE' => '',
    'LBL_IN_QUEUE_DATE' => '',

    'ERR_INT_ONLY_EMAIL_PER_RUN' => '',

    'LBL_ATTACHMENT_AUDIT' => '',
    'LBL_CONFIGURE_SETTINGS' => '',
    'LBL_CUSTOM_LOCATION' => '',
    'LBL_DEFAULT_LOCATION' => '',

    'LBL_EMAIL_DEFAULT_DELETE_ATTACHMENTS' => '',
    'LBL_EMAIL_WARNING_NOTIFICATIONS' => '', // PR 7610
    'LBL_EMAIL_ENABLE_CONFIRM_OPT_IN' => '',
    'LBL_EMAIL_ENABLE_SEND_OPT_IN' => '',
    'LBL_EMAIL_CONFIRM_OPT_IN_TEMPLATE_ID' => '',
    'LBL_LEGACY_EMAIL_COMPOSE_BEHAVIOR' => '',
    'LBL_EMAIL_OUTBOUND_CONFIGURATION' => '',
    'LBL_EMAILS_PER_RUN' => '',
    'LBL_ID' => '',
    'LBL_LIST_CAMPAIGN' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_LIST_FROM_NAME' => '',
    'LBL_LIST_IN_QUEUE' => '',
    'LBL_LIST_MESSAGE_NAME' => '',
    'LBL_LIST_RECIPIENT_EMAIL' => '',
    'LBL_LIST_RECIPIENT_NAME' => '',
    'LBL_LIST_SEND_ATTEMPTS' => '',
    'LBL_LIST_SEND_DATE_TIME' => '',
    'LBL_LIST_USER_NAME' => '',
    'LBL_LOCATION_ONLY' => '',
    'LBL_LOCATION_TRACK' => '',
    'LBL_CAMP_MESSAGE_COPY' => '',
    'LBL_CAMP_MESSAGE_COPY_DESC' => '',
    'LBL_MAIL_SENDTYPE' => '',
    'LBL_MAIL_SMTPAUTH_REQ' => '',
    'LBL_MAIL_SMTPPASS' => '',
    'LBL_MAIL_SMTPPORT' => '',
    'LBL_MAIL_SMTPSERVER' => '',
    'LBL_MAIL_SMTPUSER' => '',
    'LBL_CHOOSE_EMAIL_PROVIDER' => '',
    'LBL_YAHOOMAIL_SMTPPASS' => '',
    'LBL_YAHOOMAIL_SMTPUSER' => '',
    'LBL_GMAIL_SMTPPASS' => '',
    'LBL_GMAIL_SMTPUSER' => '',
    'LBL_EXCHANGE_SMTPPASS' => '',
    'LBL_EXCHANGE_SMTPUSER' => '',
    'LBL_EXCHANGE_SMTPPORT' => '',
    'LBL_EXCHANGE_SMTPSERVER' => '',
    'LBL_EMAIL_LINK_TYPE' => '',
    'LBL_MARKETING_ID' => '',
    'LBL_MODULE_ID' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NOTIFICATION_ON_DESC' => '',
    'LBL_NOTIFY_FROMADDRESS' => '',
    'LBL_NOTIFY_FROMNAME' => '',
    'LBL_NOTIFY_ON' => '',
    'LBL_NOTIFY_TITLE' => '',
    'LBL_OUTBOUND_EMAIL_TITLE' => '',
    'LBL_RELATED_ID' => '',
    'LBL_RELATED_TYPE' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'TRACKING_ENTRIES_LOCATION_DEFAULT_VALUE' => '',
    'TXT_REMOVE_ME_ALT' => '',
    'TXT_REMOVE_ME_CLICK' => '',
    'TXT_REMOVE_ME' => '',
    'LBL_NOTIFY_SEND_FROM_ASSIGNING_USER' => '',

    'LBL_SECURITY_TITLE' => '',
    'LBL_SECURITY_DESC' => '',
    'LBL_SECURITY_APPLET' => '',
    'LBL_SECURITY_BASE' => '',
    'LBL_SECURITY_EMBED' => '',
    'LBL_SECURITY_FORM' => '',
    'LBL_SECURITY_FRAME' => '',
    'LBL_SECURITY_FRAMESET' => '',
    'LBL_SECURITY_IFRAME' => '',
    'LBL_SECURITY_IMPORT' => '',
    'LBL_SECURITY_LAYER' => '',
    'LBL_SECURITY_LINK' => '',
    'LBL_SECURITY_OBJECT' => '',
    'LBL_SECURITY_OUTLOOK_DEFAULTS' => '',
    'LBL_SECURITY_STYLE' => '',
    'LBL_SECURITY_TOGGLE_ALL' => '',
    'LBL_SECURITY_XMP' => '',
    'LBL_YES' => '',
    'LBL_NO' => '',
    'LBL_PREPEND_TEST' => '',
    'LBL_SEND_ATTEMPTS' => '',
    'LBL_OUTGOING_SECTION_HELP' => '',
    'LBL_ALLOW_DEFAULT_SELECTION' => "",
    'LBL_ALLOW_DEFAULT_SELECTION_HELP' => '',
    'LBL_FROM_ADDRESS_HELP' => '',
    'LBL_HELP' => '' /*for 508 compliance fix*/,
    'LBL_OUTBOUND_EMAIL_ACCOUNT_VIEW' => '',
    'LBL_ALLOW_SEND_AS_USER' => '',
    'LBL_ALLOW_SEND_AS_USER_DESC' => '',
);
