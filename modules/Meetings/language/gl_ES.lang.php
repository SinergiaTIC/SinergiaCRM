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
    'LBL_ACCEPT_THIS' => '',
    'LBL_ADD_BUTTON' => '',
    'LBL_ADD_INVITEE' => '',
    'LBL_CONTACT_NAME' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_CREATED_BY' => '',
    'LBL_DATE_END' => '',
    'LBL_DATE_TIME' => '',
    'LBL_DATE' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DIRECTION' => '',
    'LBL_DURATION_HOURS' => '',
    'LBL_DURATION_MINUTES' => '',
    'LBL_DURATION' => '',
    'LBL_EMAIL' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_HOURS_ABBREV' => '',
    'LBL_HOURS_MINS' => '',
    'LBL_INVITEE' => '',
    'LBL_LAST_NAME' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_LIST_ASSIGNED_TO_NAME' => '',
    'LBL_LIST_CLOSE' => '',
    'LBL_LIST_CONTACT' => '',
    'LBL_LIST_DATE_MODIFIED' => '',
    'LBL_LIST_DATE' => '',
    'LBL_LIST_DIRECTION' => '',
    'LBL_LIST_DUE_DATE' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_LIST_MY_MEETINGS' => '',
    'LBL_LIST_RELATED_TO' => '',
    'LBL_LIST_STATUS' => '',
    'LBL_LIST_SUBJECT' => '',
    'LBL_LEADS_SUBPANEL_TITLE' => '',
    'LBL_LOCATION' => '',
    'LBL_MINSS_ABBREV' => '',
    'LBL_MODIFIED_BY' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NAME' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_OUTLOOK_ID' => '',
    'LBL_SEQUENCE' => '',
    'LBL_PHONE' => '',
    'LBL_REMINDER_TIME' => '',
    'LBL_EMAIL_REMINDER_SENT' => '',
    'LBL_REMINDER' => '',
    'LBL_REMINDER_POPUP' => '',
    'LBL_REMINDER_EMAIL_ALL_INVITEES' => '',
    'LBL_EMAIL_REMINDER' => '',
    'LBL_EMAIL_REMINDER_TIME' => '',
    'LBL_REMOVE' => '',
    'LBL_SCHEDULING_FORM_TITLE' => '',
    'LBL_SEARCH_BUTTON' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_SEND_BUTTON_LABEL' => '',
    'LBL_SEND_BUTTON_TITLE' => '',
    'LBL_STATUS' => '',
    'LBL_TYPE' => '',
    'LBL_PASSWORD' => '',
    'LBL_URL' => '',
    'LBL_HOST_URL' => '',
    'LBL_DISPLAYED_URL' => '',
    'LBL_CREATOR' => '',
    'LBL_EXTERNALID' => '',
    'LBL_SUBJECT' => '',
    'LBL_TIME' => '',
    'LBL_USERS_SUBPANEL_TITLE' => '',
    'LBL_PARENT_TYPE' => '',
    'LBL_PARENT_ID' => '',
    'LNK_MEETING_LIST' => '',
    'LNK_NEW_APPOINTMENT' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_IMPORT_MEETINGS' => '',

    'LBL_CREATED_USER' => '',
    'LBL_MODIFIED_USER' => '',
    'NOTICE_DURATION_TIME' => '',
    'LBL_MEETING_INFORMATION' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_LIST_JOIN_MEETING' => '',
    'LBL_ACCEPT_STATUS' => '',
    'LBL_ACCEPT_LINK' => '',
    // You are not invited to the meeting messages
    'LBL_EXTNOT_MAIN' => '',
    'LBL_EXTNOT_RECORD_LINK' => '',

    //cannot start messages
    'LBL_EXTNOSTART_MAIN' => '',

    // create invitee functionallity
    'LBL_CREATE_INVITEE' => '',
    'LBL_CREATE_CONTACT' => '',  // Create invitee functionallity
    'LBL_CREATE_LEAD' => '', // Create invitee functionallity
    'LBL_CREATE_AND_ADD' => '', // Create invitee functionallity
    'LBL_CANCEL_CREATE_INVITEE' => '',
    'LBL_EMPTY_SEARCH_RESULT' => '',
    'LBL_NO_ACCESS' => '',  // Create invitee functionallity

    'LBL_REPEAT_TYPE' => '',
    'LBL_REPEAT_INTERVAL' => '',
    'LBL_REPEAT_DOW' => '',
    'LBL_REPEAT_UNTIL' => '',
    'LBL_REPEAT_COUNT' => '',
    'LBL_REPEAT_PARENT_ID' => '',
    'LBL_RECURRING_SOURCE' => '',

    'LBL_SYNCED_RECURRING_MSG' => '',
    'LBL_RELATED_TO' => '',

    // for reminders
    'LBL_REMINDERS' => '',
    'LBL_REMINDERS_ACTIONS' => '',
    'LBL_REMINDERS_POPUP' => '',
    'LBL_REMINDERS_EMAIL' => '',
    'LBL_REMINDERS_WHEN' => '',
    'LBL_REMINDERS_REMOVE_REMINDER' => '',
    'LBL_REMINDERS_ADD_ALL_INVITEES' => '',
    'LBL_REMINDERS_ADD_REMINDER' => '',

    // for google sync
    'LBL_GSYNC_ID' => '',
    'LBL_GSYNC_LASTSYNC' => '',
);
