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
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_LAST_NAME' => '',
    'LBL_LIST_LAST_NAME' => '',
    'LBL_PHONE' => '',
    'LBL_EMAIL_ADDRESS' => '',
    'LBL_MY_PIPELINE_FORM_TITLE' => '',
    'LBL_PIPELINE_FORM_TITLE' => '',
    'LBL_RGraph_PIPELINE_FORM_TITLE' => '',
    'LNK_NEW_CONTACT' => '',
    'LNK_NEW_ACCOUNT' => '',
    'LNK_NEW_OPPORTUNITY' => '',
    'LNK_NEW_LEAD' => '',
    'LNK_NEW_CASE' => '',
    'LNK_NEW_NOTE' => '',
    'LNK_NEW_CALL' => '',
    'LNK_NEW_EMAIL' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_NEW_TASK' => '',
    'LNK_NEW_BUG' => '',
    'LNK_NEW_SEND_EMAIL' => '',
    'LBL_NO_ACCESS' => '',
    'LBL_NO_RESULTS_IN_MODULE' => '',
    'LBL_NO_RESULTS' => '',
    'LBL_NO_RESULTS_TIPS' => '',

    'LBL_ADD_DASHLETS' => '',
    'LBL_WEBSITE_TITLE' => '',
    'LBL_RSS_TITLE' => '',
    'LBL_CLOSE_DASHLETS' => '',
    'LBL_OPTIONS' => '',
    // dashlet search fields
    'LBL_TODAY' => '',
    'LBL_YESTERDAY' => '',
    'LBL_TOMORROW' => '',
    'LBL_NEXT_WEEK' => '',
    'LBL_LAST_7_DAYS' => '',
    'LBL_NEXT_7_DAYS' => '',
    'LBL_LAST_MONTH' => '',
    'LBL_NEXT_MONTH' => '',
    'LBL_LAST_YEAR' => '',
    'LBL_NEXT_YEAR' => '',
    'LBL_LAST_30_DAYS' => '',
    'LBL_NEXT_30_DAYS' => '',
    'LBL_THIS_MONTH' => '',
    'LBL_THIS_YEAR' => '',

    'LBL_MODULES' => '',
    'LBL_CHARTS' => '',
    'LBL_TOOLS' => '',
    'LBL_WEB' => '',
    'LBL_SEARCH_RESULTS' => '',

    // Dashlet Categories
    'dashlet_categories_dom' => array(
        'Module Views' => '',
        'Portal' => '',
        'Charts' => '',
        'Tools' => '',
        'Miscellaneous' => ''
    ),
    'LBL_ADDING_DASHLET' => '',
    'LBL_ADDED_DASHLET' => '',
    'LBL_REMOVE_DASHLET_CONFIRM' => '',
    'LBL_REMOVING_DASHLET' => '',
    'LBL_REMOVED_DASHLET' => '',
    'LBL_DASHLET_CONFIGURE_GENERAL' => '',
    'LBL_DASHLET_CONFIGURE_FILTERS' => '',
    'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY' => '',
    'LBL_DASHLET_CONFIGURE_TITLE' => '',
    'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS' => '',

    'LBL_DASHLET_DELETE' => '',
    'LBL_DASHLET_REFRESH' => '',
    'LBL_DASHLET_EDIT' => '',

    // Default out-of-box names for tabs
    'LBL_HOME_PAGE_1_NAME' => '',
    'LBL_CLOSE_SITEMAP' => '',

    'LBL_SEARCH' => '',
    'LBL_CLEAR' => '',

    'LBL_BASIC_CHARTS' => '',

    'LBL_DASHLET_SEARCH' => '',

//ABOUT page
    'LBL_VERSION' => '',
    'LBL_BUILD' => '',

    'LBL_SOURCE_SUGAR' => '',

    'LBL_DASHLET_TITLE' => '',
    'LBL_DASHLET_OPT_TITLE' => '',
    'LBL_DASHLET_INCORRECT_URL' => '',
    'LBL_DASHLET_OPT_URL' => '',
    'LBL_DASHLET_OPT_HEIGHT' => '',
    'LBL_DASHLET_SUITE_NEWS' => '',
    'LBL_DASHLET_DISCOVER_SUITE' => '',
    'LBL_BASIC_SEARCH' => '' /*for 508 compliance fix*/,
    'LBL_ADVANCED_SEARCH' => '' /*for 508 compliance fix*/,
    'LBL_TOUR_HOME' => '',
    'LBL_TOUR_HOME_DESCRIPTION' => '',
    'LBL_TOUR_MODULES' => '',
    'LBL_TOUR_MODULES_DESCRIPTION' => '',
    'LBL_TOUR_MORE' => '',
    'LBL_TOUR_MORE_DESCRIPTION' => '',
    'LBL_TOUR_SEARCH' => '',
    'LBL_TOUR_SEARCH_DESCRIPTION' => '',
    'LBL_TOUR_NOTIFICATIONS' => '',
    'LBL_TOUR_NOTIFICATIONS_DESCRIPTION' => '',
    'LBL_TOUR_PROFILE' => '',
    'LBL_TOUR_PROFILE_DESCRIPTION' => '',
    'LBL_TOUR_QUICKCREATE' => '',
    'LBL_TOUR_QUICKCREATE_DESCRIPTION' => '',
    'LBL_TOUR_FOOTER' => '',
    'LBL_TOUR_FOOTER_DESCRIPTION' => '',
    'LBL_TOUR_CUSTOM' => '',
    'LBL_TOUR_CUSTOM_DESCRIPTION' => '',
    'LBL_TOUR_BRAND' => '',
    'LBL_TOUR_BRAND_DESCRIPTION' => '',
    'LBL_TOUR_WELCOME' => '',
    'LBL_TOUR_WATCH' => '',
    'LBL_TOUR_FEATURES' => '',
    'LBL_TOUR_VISIT' => '',
    'LBL_TOUR_DONE' => '',
    'LBL_TOUR_REFERENCE_1' => '',
    'LBL_TOUR_REFERENCE_2' => '',
    'LNK_TOUR_DOCUMENTATION' => '',
    'LBL_TOUR_CALENDAR_URL_1' => '',
    'LBL_TOUR_CALENDAR_URL_2' => '',
    'LBL_CONTRIBUTORS' => '',
    'LBL_ABOUT_SUITE' => '',
    'LBL_PARTNERS' => '',
    'LBL_FEATURING' => '',

    'LBL_CONTRIBUTOR_SUITECRM' => '',
    'LBL_CONTRIBUTOR_SECURITY_SUITE' => '',
    'LBL_CONTRIBUTOR_JJW_GMAPS' => '',
    'LBL_CONTRIBUTOR_CONSCIOUS' => '',
    'LBL_CONTRIBUTOR_RESPONSETAP' => '',
    'LBL_CONTRIBUTOR_GMBH' => '',

    'LBL_LANGUAGE_ABOUT' => '',
    'LBL_LANGUAGE_COMMUNITY_ABOUT' => '',
    'LBL_LANGUAGE_COMMUNITY_PACKS' => '',

    'LBL_ABOUT_SUITE_2' => '',
    'LBL_ABOUT_SUITE_4' => '',
    'LBL_ABOUT_SUITE_5' => '',

    'LBL_SUITE_PARTNERS' => '',

    'LBL_SAVE_BUTTON' => '',
    'LBL_DELETE_BUTTON' => '',
    'LBL_APPLY_BUTTON' => '',
    'LBL_SEND_INVITES' => '',
    'LBL_CANCEL_BUTTON' => '',
    'LBL_CLOSE_BUTTON' => '',

    'LBL_CREATE_NEW_RECORD' => '',
    'LBL_CREATE_CALL' => '',
    'LBL_CREATE_MEETING' => '',

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

    //Events
    'LNK_EVENT' => '',
    'LNK_EVENT_VIEW' => '',
    'LBL_DATE' => '',
    'LBL_DURATION' => '',
    'LBL_NAME' => '',
    'LBL_HOUR_ABBREV' => '',
    'LBL_HOURS_ABBREV' => '',
    'LBL_MINSS_ABBREV' => '',
    'LBL_LOCATION' => '',
    'LBL_STATUS' => '',
    'LBL_DESCRIPTION' => '',
    //End Events

    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH_INVALID_REQUEST' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH_ENGINE_NOT_FOUND' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_NO_NODES_AVAILABLE' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_SEARCH' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_DEFAULT' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_END_MESSAGE' => '',
    'LBL_ELASTIC_SEARCH_EXCEPTION_MISSING_INDEX' => '',

    'LBL_ELASTIC_SEARCH_DEFAULT' => '',

    'LNK_TASK_VIEW' => '',
);
