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
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_HISTORY_TITLE' => '',
    'LBL_ID' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_ASSIGNED_USER_ID' => '',
    'LBL_ASSIGNED_USER_NAME' => '',
    'LBL_MODIFIED_USER_ID' => '',
    'LBL_CREATED_BY' => '',
    'LBL_NAME' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DELETED' => '',
    'LBL_DATE' => '',
    'LBL_DATE_START' => '',
    'LBL_DATE_END' => '',
    'LBL_PRIORITY' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_TOTAL_ESTIMATED_EFFORT' => '',
    'LBL_LIST_TOTAL_ACTUAL_EFFORT' => '',
    'LBL_LIST_END_DATE' => '',
    'LBL_PROJECT_SUBPANEL_TITLE' => '',
    'LBL_PROJECT_TASK_SUBPANEL_TITLE' => '',
    'LBL_OPPORTUNITY_SUBPANEL_TITLE' => '',
    'LBL_PROJECT_PREDECESSOR_NONE' => '',
    'LBL_ALL_PROJECTS' => '',
    'LBL_ALL_USERS' => '',
    'LBL_ALL_CONTACTS' => '',

    // quick create label
    'LBL_NEW_FORM_TITLE' => '',
    'LNK_NEW_PROJECT' => '',
    'LNK_PROJECT_LIST' => '',
    'LNK_NEW_PROJECT_TASK' => '',
    'LNK_PROJECT_TASK_LIST' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => '',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => '',
    'LBL_CASES_SUBPANEL_TITLE' => '',
    'LBL_BUGS_SUBPANEL_TITLE' => '',
    'LBL_TASK_ID' => '',
    'LBL_TASK_NAME' => '',
    'LBL_DURATION' => '',
    'LBL_ACTUAL_DURATION' => '',
    'LBL_START' => '',
    'LBL_FINISH' => '',
    'LBL_PREDECESSORS' => '',
    'LBL_PERCENT_COMPLETE' => '',
    'LBL_MORE' => '',
    'LBL_OPPORTUNITIES' => '',
    'LBL_NEXT_WEEK' => '',
    'LBL_PROJECT_INFORMATION' => '',
    'LBL_EDITLAYOUT' => '' /*for 508 compliance fix*/,
    'LBL_PROJECT_TASKS_SUBPANEL_TITLE' => '',
    'LBL_VIEW_GANTT_TITLE' => '',
    'LBL_VIEW_GANTT_DURATION' => '',
    'LBL_TASK_TITLE' => '',
    'LBL_DURATION_TITLE' => '',
    'LBL_LAG' => '',
    'LBL_DAYS' => '',
    'LBL_HOURS' => '',
    'LBL_MONTHS' => '',
    'LBL_SUBTASK' => '',
    'LBL_MILESTONE_FLAG' => '',
    'LBL_ADD_NEW_TASK' => '',
    'LBL_DELETE_TASK' => '',
    'LBL_EDIT_TASK_PROPERTIES' => '',
    'LBL_PARENT_TASK_ID' => '',
    'LBL_RESOURCE_CHART' => '',
    'LBL_RELATIONSHIP_TYPE' => '',
    'LBL_ASSIGNED_TO' => '',
    'LBL_AM_PROJECTTEMPLATES_PROJECT_1_FROM_AM_PROJECTTEMPLATES_TITLE' => '',
    'LBL_STATUS' => '',
    'LBL_LIST_ASSIGNED_USER_ID' => '',
    'LBL_TOOLTIP_PROJECT_NAME' => '',
    'LBL_TOOLTIP_TASK_NAME' => '',
    'LBL_TOOLTIP_TITLE' => '',
    'LBL_TOOLTIP_TASK_DURATION' => '',
    'LBL_RESOURCE_TYPE_TITLE_USER' => '',
    'LBL_RESOURCE_TYPE_TITLE_CONTACT' => '',
    'LBL_RESOURCE_CHART_PREVIOUS_MONTH' => '',
    'LBL_RESOURCE_CHART_NEXT_MONTH' => '',
    'LBL_RESOURCE_CHART_WEEK' => '',
    'LBL_RESOURCE_CHART_DAY' => '',
    'LBL_RESOURCE_CHART_WARNING' => '',
    'LBL_PROJECT_DELETE_MSG' => '',
    'LBL_LIST_MY_PROJECT' => '',
    'LBL_LIST_ASSIGNED_USER' => '',
    'LBL_UNASSIGNED' => '',
    'LBL_PROJECT_USERS_1_FROM_USERS_TITLE' => '',

    'LBL_EMAIL' => '',
    'LBL_PHONE' => '',
    'LBL_ADD_BUTTON' => '',
    'LBL_ADD_INVITEE' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_LAST_NAME' => '',
    'LBL_SEARCH_BUTTON' => '',
    'LBL_EMPTY_SEARCH_RESULT' => '',
    'LBL_CREATE_INVITEE' => '',
    'LBL_CREATE_CONTACT' => '',
    'LBL_CREATE_AND_ADD' => '',
    'LBL_CANCEL_CREATE_INVITEE' => '',
    'LBL_NO_ACCESS' => '',
    'LBL_SCHEDULING_FORM_TITLE' => '',
    'LBL_REMOVE' => '',
    'LBL_VIEW_DETAIL' => '',
    'LBL_OVERRIDE_BUSINESS_HOURS' => '',

    'LBL_IMPORT_PROJECTS' => '',

    'LBL_PROJECTS_SEARCH' => '',
    'LBL_USERS_SEARCH' => '',
    'LBL_CONTACTS_SEARCH' => '',
    'LBL_RESOURCE_CHART_SEARCH_BUTTON' => '',

    'LBL_CHART_TYPE' => '',
    'LBL_CHART_WEEKLY' => '',
    'LBL_CHART_MONTHLY' => '',
    'LBL_CHART_QUARTERLY' => '',

    'LBL_RESOURCE_CHART_MONTH' => '',
    'LBL_RESOURCE_CHART_QUARTER' => '',

    'LBL_PROJECT_CONTACTS_1_FROM_CONTACTS_TITLE' => '',
    'LBL_AM_PROJECTTEMPLATES_PROJECT_1_FROM_PROJECT_TITLE' => '',
    'LBL_AOS_QUOTES_PROJECT' => '',

    'LBL_ASCENDING' => '',
    'LBL_DESCENDING' => '',
);
