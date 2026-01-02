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


    'LBL_RE' => '',

    'ERR_BAD_LOGIN_PASSWORD' => '',
    'ERR_INI_ZLIB' => '',
    'ERR_NO_IMAP' => '',
    'ERR_NO_OPTS_SAVED' => '',
    'ERR_TEST_MAILBOX' => '',
    'ERR_INVALID_PORT' => '',

    'LBL_ASSIGN_TO_USER' => '',
    'LBL_AUTOREPLY' => '',
    'LBL_AUTOREPLY_HELP' => '',
    'LBL_BASIC' => '',
    'LBL_CASE_MACRO' => '',
    'LBL_CASE_MACRO_DESC' => '',
    'LBL_CASE_MACRO_DESC2' => '',
    'LBL_CLOSE_POPUP' => '',
    'LBL_CREATE_TEMPLATE' => '',
    'LBL_DELETE_SEEN' => '',
    'LBL_EDIT_TEMPLATE' => '',
    'LBL_EMAIL_OPTIONS' => '',
    'LBL_EMAIL_BOUNCE_OPTIONS' => '',
    'LBL_FILTER_DOMAIN_DESC' => '',
    'LBL_ASSIGN_TO_GROUP_FOLDER_DESC' => '',
    'LBL_FILTER_DOMAIN' => '',
    'LBL_FIND_SSL_WARN' => '',
    'LBL_FROM_ADDR' => '',
    'LBL_FROM_ADDR_DESC' => '',
    'LBL_FROM_NAME' => '',
    'LBL_GROUP_QUEUE' => '',
    'LBL_HOME' => '',
    'LBL_LIST_MAILBOX_TYPE' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_GLOBAL_PERSONAL' => '',
    'LBL_LIST_SERVER_URL' => '',
    'LBL_SERVER_ADDRESS' => '',
    'LBL_LIST_STATUS' => '',
    'LBL_LOGIN' => '',
    'LBL_MAILBOX_DEFAULT' => '',
    'LBL_USERNAME' => '',
    'LBL_MAILBOX_SSL' => '',
    'LBL_MAILBOX_TYPE' => '',
    'LBL_DISTRIBUTION_METHOD' => '',
    'LBL_CREATE_CASE_REPLY_TEMPLATE' => '',
    'LBL_CREATE_CASE_REPLY_TEMPLATE_HELP' => '',
    'LBL_MAILBOX' => '',
    'LBL_TRASH_FOLDER' => '',
    'LBL_SENT_FOLDER' => '',
    'LBL_SELECT' => '',
    'LBL_MARK_READ_NO' => '',
    'LBL_MARK_READ_YES' => '',
    'LBL_MARK_READ' => '',
    'LBL_MAX_AUTO_REPLIES' => '',
    'LBL_MAX_AUTO_REPLIES_DESC' => '',
    'LBL_PERSONAL_MODULE_NAME' => '',
    'LBL_CREATE_CASE' => '',
    'LBL_CREATE_CASE_HELP' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_BOUNCE_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NAME' => '',
    'LBL_NONE' => '',
    'LBL_ONLY_SINCE_NO' => '',
    'LBL_ONLY_SINCE_YES' => '',
    'LBL_PASSWORD' => '',
    'LBL_EMAIL_PASSWORD' => '',
    'LBL_POP3_SUCCESS' => '',
    'LBL_POPUP_TITLE' => '',
    'LBL_SELECT_SUBSCRIBED_FOLDERS' => '',
    'LBL_SELECT_TRASH_FOLDERS' => '',
    'LBL_SELECT_SENT_FOLDERS' => '',
    'LBL_DELETED_FOLDERS_LIST' => '',
    'LBL_PORT' => '',
    'LBL_REPLY_TO_NAME' => '',
    'LBL_REPLY_TO_ADDR' => '',
    'LBL_SAME_AS_ABOVE' => '',
    'LBL_SERVER_OPTIONS' => '',
    'LBL_SERVER_TYPE' => '',
    'LBL_SERVER_PORT' => '',
    'LBL_SERVER_URL' => '',
    'LBL_SSL_DESC' => '',
    'LBL_ASSIGN_TO_TEAM_DESC' => '',
    'LBL_SSL' => '',
    'LBL_STATUS' => '',
    'LBL_EMAIL_BODY_FILTERING' => '',
    'LBL_SYSTEM_DEFAULT' => '',
    'LBL_TEST_BUTTON_TITLE' => '',
    'LBL_TEST_SETTINGS' => '',
    'LBL_TEST_CONNECTION_SETTINGS' => '',
    'LBL_TEST_SUCCESSFUL' => '',
    'LBL_TEST_WAIT_MESSAGE' => '',
    'LBL_WARN_IMAP_TITLE' => '',
    'LBL_WARN_IMAP' => '',
    'LBL_WARN_NO_IMAP' => '',

    'LNK_LIST_CREATE_NEW_PERSONAL' => '',
    'LNK_LIST_CREATE_NEW_GROUP' => '',
    'LNK_LIST_CREATE_NEW_CASES_TYPE' => '',
    'LNK_LIST_CREATE_NEW_BOUNCE' => '',
    'LNK_LIST_MAILBOXES' => '',
    'LNK_LIST_OUTBOUND_EMAILS' => '',    
    'LNK_LIST_SCHEDULER' => '',
    'LNK_SEED_QUEUES' => '',
    'LBL_GROUPFOLDER_ID' => '',

    'LBL_ALLOW_OUTBOUND_GROUP_USAGE' => '',
    'LBL_ALLOW_OUTBOUND_GROUP_USAGE_DESC' => '',
    'LBL_STATUS_ACTIVE' => '',
    'LBL_STATUS_INACTIVE' => '',
    'LBL_IS_PERSONAL' => '',
    'LBL_IS_GROUP' => '',
    'LBL_ENABLE_AUTO_IMPORT' => '',
    'LBL_WARNING_CHANGING_AUTO_IMPORT' => '',
    'LBL_WARNING_CHANGING_AUTO_IMPORT_WITH_CREATE_CASE' => '',
    'LBL_LIST_TITLE_MY_DRAFTS' => '',
    'LBL_LIST_TITLE_MY_INBOX' => '',
    'LBL_LIST_TITLE_MY_SENT' => '',
    'LBL_LIST_TITLE_MY_ARCHIVES' => '',
    'LNK_MY_DRAFTS' => '',
    'LNK_MY_INBOX' => '',
    'LNK_VIEW_MY_INBOX' => '',
    'LNK_QUICK_REPLY' => '',
    'LNK_SENT_EMAIL_LIST' => '',
    'LBL_EDIT_LAYOUT' => '' /*for 508 compliance fix*/,
    'LBL_TYPE_DIFFERENT' => '',

    'LBL_MODIFIED_BY' => '',
    'LBL_SERVICE' => '',
    'LBL_STORED_OPTIONS' => '',
    'LBL_GROUP_ID' => '',

    'LBL_OUTBOUND_CONFIGURATION' => '',
    'LBL_CONNECTION_CONFIGURATION' => '',
    'LBL_AUTO_REPLY_CONFIGURATION' => '',
    'LBL_CASE_CONFIGURATION' => '',
    'LBL_GROUP_CONFIGURATION' => '',

    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => '',

    'LBL_OUTBOUND_EMAIL_ACCOUNT' => '',
    'LBL_OUTBOUND_EMAIL_ACCOUNT_ID' => '',
    'LBL_OUTBOUND_EMAIL_ACCOUNT_NAME' => '',

    'LBL_AUTOREPLY_EMAIL_TEMPLATE' => '',
    'LBL_AUTOREPLY_EMAIL_TEMPLATE_ID' => '',
    'LBL_AUTOREPLY_EMAIL_TEMPLATE_NAME' => '',

    'LBL_CASE_EMAIL_TEMPLATE' => '',
    'LBL_CASE_EMAIL_TEMPLATE_ID' => '',
    'LBL_CASE_EMAIL_TEMPLATE_NAME' => '',

    'LBL_PROTOCOL' => '',
    'LBL_CONNECTION_STRING' => '',
    'LBL_DISTRIB_METHOD' => '',
    'LBL_DISTRIB_OPTIONS' => '',

    'LBL_DISTRIBUTION_USER' => '',
    'LBL_DISTRIBUTION_USER_ID' => '',
    'LBL_DISTRIBUTION_USER_NAME' => '',

    'LBL_EXTERNAL_OAUTH_CONNECTION' => '',
    'LBL_EXTERNAL_OAUTH_CONNECTION_ID' => '',
    'LBL_EXTERNAL_OAUTH_CONNECTION_NAME' => '',
    'LNK_EXTERNAL_OAUTH_CONNECTIONS' => '',

    'LBL_TYPE' => '',
    'LBL_AUTH_TYPE' => '',
    'LBL_IS_DEFAULT' => '',
    'LBL_SIGNATURE' => '',

    'LBL_OWNER_NAME' => '',

    'LBL_SET_AS_DEFAULT_BUTTON' => '',

    'LBL_MOVE_MESSAGES_TO_TRASH_AFTER_IMPORT' => '',
);