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

    'LBL_SHAREDWEEK' => '',
    'LBL_SHAREDMONTH' => '',

    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LNK_NEW_CALL' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_NEW_TASK' => '',
    'LNK_CALL_LIST' => '',
    'LNK_MEETING_LIST' => '',
    'LNK_TASK_LIST' => '',
    'LNK_TASK' => '',
    'LNK_TASK_VIEW' => '',
    'LNK_EVENT' => '',
    'LNK_EVENT_VIEW' => '',
    'LNK_VIEW_CALENDAR' => '',
    'LNK_IMPORT_CALLS' => '',
    'LNK_IMPORT_MEETINGS' => '',
    'LNK_IMPORT_TASKS' => '',
    'LBL_MONTH' => '',
    'LBL_AGENDADAY' => '',
    'LBL_YEAR' => '',

    'LBL_AGENDAWEEK' => '',
    'LBL_PREVIOUS_MONTH' => '',
    'LBL_PREVIOUS_DAY' => '',
    'LBL_PREVIOUS_YEAR' => '',
    'LBL_PREVIOUS_WEEK' => '',
    'LBL_NEXT_MONTH' => '',
    'LBL_NEXT_DAY' => '',
    'LBL_NEXT_YEAR' => '',
    'LBL_NEXT_WEEK' => '',
    'LBL_AM' => '',
    'LBL_PM' => '',
    'LBL_SCHEDULED' => '',
    'LBL_BUSY' => '',
    'LBL_CONFLICT' => '',
    'LBL_USER_CALENDARS' => '',
    'LBL_SHARED' => '',
    'LBL_PREVIOUS_SHARED' => '',
    'LBL_NEXT_SHARED' => '',
    'LBL_SHARED_CAL_TITLE' => '',
    'LBL_USERS' => '',
    'LBL_REFRESH' => '',
    'LBL_EDIT_USERLIST' => '',
    'LBL_SELECT_USERS' => '',
    'LBL_FILTER_BY_TEAM' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_DATE' => '',
    'LBL_CREATE_MEETING' => '',
    'LBL_CREATE_CALL' => '',
    'LBL_HOURS_ABBREV' => '',
    'LBL_MINS_ABBREV' => '',


    'LBL_YES' => '',
    'LBL_NO' => '',
    'LBL_SETTINGS' => '',
    'LBL_CREATE_NEW_RECORD' => '',
    'LBL_LOADING' => '',
    'LBL_SAVING' => '',
    'LBL_SENDING_INVITES' => '',
    'LBL_CONFIRM_REMOVE' => '',
    'LBL_CONFIRM_REMOVE_ALL_RECURRING' => '',
    'LBL_EDIT_RECORD' => '',
    'LBL_ERROR_SAVING' => '',
    'LBL_ERROR_LOADING' => '',
    'LBL_GOTO_DATE' => '',
    'NOTICE_DURATION_TIME' => '',
    'LBL_STYLE_BASIC' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_STYLE_ADVANCED' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions

    'LBL_NO_USER' => '',
    'LBL_SUBJECT' => '',
    'LBL_DURATION' => '',
    'LBL_STATUS' => '',
    'LBL_PRIORITY' => '',

    'LBL_SETTINGS_TITLE' => '',
    'LBL_SETTINGS_DISPLAY_TIMESLOTS' => '',
    'LBL_SETTINGS_TIME_STARTS' => '',
    'LBL_SETTINGS_TIME_ENDS' => '',
    'LBL_SETTINGS_CALLS_SHOW' => '',
    'LBL_SETTINGS_TASKS_SHOW' => '',
    'LBL_SETTINGS_COMPLETED_SHOW' => '',
    'LBL_SETTINGS_DISPLAY_SHARED_CALENDAR_SEPARATE' => '',

    'LBL_SAVE_BUTTON' => '',
    'LBL_DELETE_BUTTON' => '',
    'LBL_APPLY_BUTTON' => '',
    'LBL_SEND_INVITES' => '',
    'LBL_CANCEL_BUTTON' => '',
    'LBL_CLOSE_BUTTON' => '',

    'LBL_GENERAL_TAB' => '',
    'LBL_PARTICIPANTS_TAB' => '',
    'LBL_REPEAT_TAB' => '',

    'LBL_REPEAT_TYPE' => '',
    'LBL_REPEAT_INTERVAL' => '',
    'LBL_REPEAT_END' => '',
    'LBL_REPEAT_END_AFTER' => '',
    'LBL_REPEAT_OCCURRENCES' => '',
    'LBL_REPEAT_END_BY' => '',
    'LBL_REPEAT_DOW' => '',
    'LBL_REPEAT_UNTIL' => '',
    'LBL_REPEAT_COUNT' => '',
    'LBL_REPEAT_LIMIT_ERROR' => '',

    'LBL_EDIT_ALL_RECURRENCES' => '',
    'LBL_REMOVE_ALL_RECURRENCES' => '',

    'LBL_DATE_END_ERROR' => '',
    'ERR_YEAR_BETWEEN' => '',
    'ERR_NEIGHBOR_DATE' => '',
    'LBL_NO_ITEMS_MOBILE' => '',
    'LBL_GENERAL_SETTINGS' => '',
    'LBL_COLOR_SETTINGS' => '',
    'LBL_MODULE' => '',
    'LBL_BODY' => '',
    'LBL_BORDER' => '',
    'LBL_TEXT' => '',
);


$mod_list_strings = array(
    'dom_cal_weekdays' =>
        array(
            '0' => "",
            '1' => "",
            '2' => "",
            '3' => "",
            '4' => "",
            '5' => "",
            '6' => "",
        ),
    'dom_cal_weekdays_long' =>
        array(
            '0' => "",
            '1' => "",
            '2' => "",
            '3' => "",
            '4' => "",
            '5' => "",
            '6' => "",
        ),
    'dom_cal_month' =>
        array(
            '0' => "",
            '1' => "",
            '2' => "",
            '3' => "",
            '4' => "",
            '5' => "",
            '6' => "",
            '7' => "",
            '8' => "",
            '9' => "",
            '10' => "",
            '11' => "",
            '12' => "",
        ),
    'dom_cal_month_long' =>
        array(
            '0' => "",
            '1' => "",
            '2' => "",
            '3' => "",
            '4' => "",
            '5' => "",
            '6' => "",
            '7' => "",
            '8' => "",
            '9' => "",
            '10' => "",
            '11' => "",
            '12' => "",
        ),
);