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
    'LBL_BLANK' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_LIST_CLOSE' => '',
    'LBL_LIST_SUBJECT' => '',
    'LBL_LIST_CONTACT' => '',
    'LBL_LIST_RELATED_TO' => '',
    'LBL_LIST_RELATED_TO_ID' => '',
    'LBL_LIST_DATE' => '',
    'LBL_LIST_DIRECTION' => '',
    'LBL_SUBJECT' => '',
    'LBL_REMINDER' => '',
    'LBL_CONTACT_NAME' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_STATUS' => '',
    'LBL_DIRECTION' => '',
    'LBL_DATE' => '',
    'LBL_DURATION' => '',
    'LBL_DURATION_HOURS' => '',
    'LBL_DURATION_MINUTES' => '',
    'LBL_HOURS_MINUTES' => '',
    'LBL_DATE_TIME' => '',
    'LBL_TIME' => '',
    'LBL_HOURS_ABBREV' => '',
    'LBL_MINSS_ABBREV' => '',
    'LNK_NEW_CALL' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_CALL_LIST' => '',
    'LNK_IMPORT_CALLS' => '',
    'ERR_DELETE_RECORD' => '',
    'LBL_INVITEE' => '',
    'LBL_RELATED_TO' => '',
    'LNK_NEW_APPOINTMENT' => '',
    'LBL_SCHEDULING_FORM_TITLE' => '',
    'LBL_ADD_INVITEE' => '',
    'LBL_NAME' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_LAST_NAME' => '',
    'LBL_EMAIL' => '',
    'LBL_PHONE' => '',
    'LBL_REMINDER_POPUP' => '',
    'LBL_REMINDER_EMAIL_ALL_INVITEES' => '',
    'LBL_EMAIL_REMINDER' => '',
    'LBL_EMAIL_REMINDER_TIME' => '',
    'LBL_SEND_BUTTON_TITLE' => '',
    'LBL_SEND_BUTTON_LABEL' => '',
    'LBL_DATE_END' => '',
    'LBL_REMINDER_TIME' => '',
    'LBL_EMAIL_REMINDER_SENT' => '',
    'LBL_SEARCH_BUTTON' => '',
    'LBL_ADD_BUTTON' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LNK_SELECT_ACCOUNT' => '',
    'LNK_NEW_ACCOUNT' => '',
    'LNK_NEW_OPPORTUNITY' => '',
    'LBL_LEADS_SUBPANEL_TITLE' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_USERS_SUBPANEL_TITLE' => '',
    'LBL_OUTLOOK_ID' => '',
    'LBL_MEMBER_OF' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_LIST_ASSIGNED_TO_NAME' => '',
    'LBL_LIST_MY_CALLS' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_ASSIGNED_TO_ID' => '',
    'NOTICE_DURATION_TIME' => '',
    'LBL_CALL_INFORMATION' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_REMOVE' => '',
    'LBL_ACCEPT_STATUS' => '',
    'LBL_ACCEPT_LINK' => '',

    // create invitee functionality
    'LBL_CREATE_INVITEE' => '',
    'LBL_CREATE_CONTACT' => '',
    'LBL_CREATE_LEAD' => '',
    'LBL_CREATE_AND_ADD' => '',
    'LBL_CANCEL_CREATE_INVITEE' => '',
    'LBL_EMPTY_SEARCH_RESULT' => '',
    'LBL_NO_ACCESS' => '',

    'LBL_REPEAT_TYPE' => '',
    'LBL_REPEAT_INTERVAL' => '',
    'LBL_REPEAT_DOW' => '',
    'LBL_REPEAT_UNTIL' => '',
    'LBL_REPEAT_COUNT' => '',
    'LBL_REPEAT_PARENT_ID' => '',
    'LBL_RECURRING_SOURCE' => '',

    'LBL_SYNCED_RECURRING_MSG' => '',

    // for reminders
    'LBL_REMINDERS' => '',
    'LBL_REMINDERS_ACTIONS' => '',
    'LBL_REMINDERS_POPUP' => '',
    'LBL_REMINDERS_EMAIL' => '',
    'LBL_REMINDERS_WHEN' => '',
    'LBL_REMINDERS_REMOVE_REMINDER' => '',
    'LBL_REMINDERS_ADD_ALL_INVITEES' => '',
    'LBL_REMINDERS_ADD_REMINDER' => '',

    'LBL_RESCHEDULE' => '',
    'LBL_RESCHEDULE_COUNT' => '',
    'LBL_RESCHEDULE_DATE' => '',
    'LBL_RESCHEDULE_REASON' => '',
    'LBL_RESCHEDULE_ERROR1' => '',
    'LBL_RESCHEDULE_ERROR2' => '',
    'LBL_RESCHEDULE_PANEL' => '',
    'LBL_RESCHEDULE_HISTORY' => '',
    'LBL_CANCEL' => '',
    'LBL_SAVE' => '',

    'LBL_CALLS_RESCHEDULE' => '',
    'LBL_LIST_STATUS'=>'',
    'LBL_LIST_DATE_MODIFIED'=>'',
    'LBL_LIST_DUE_DATE'=>'',
);