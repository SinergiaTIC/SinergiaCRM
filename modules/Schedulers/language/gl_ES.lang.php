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
// OOTB Scheduler Job Names:
    'LBL_OOTB_WORKFLOW' => '',
    'LBL_OOTB_REPORTS' => '',
    'LBL_OOTB_IE' => '',
    'LBL_OOTB_BOUNCE' => '',
    'LBL_OOTB_CAMPAIGN' => '',
    'LBL_OOTB_PRUNE' => '',
    'LBL_OOTB_TRACKER' => '',
    'LBL_OOTB_SUITEFEEDS' => '',
    'LBL_OOTB_LUCENE_INDEX' => '',
    'LBL_OOTB_OPTIMISE_INDEX' => '',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => '',
    'LBL_OOTB_CLEANUP_QUEUE' => '',
    'LBL_OOTB_REMOVE_DOCUMENTS_FROM_FS' => '',
    'LBL_OOTB_GOOGLE_CAL_SYNC' => '',
    'LBL_OOTB_ELASTIC_INDEX' => '',

// List Labels
    'LBL_LIST_JOB_INTERVAL' => '',
    'LBL_LIST_LIST_ORDER' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_RANGE' => '',
    'LBL_LIST_STATUS' => '',
    'LBL_LIST_TITLE' => '',
// human readable:
    'LBL_SUN' => '',
    'LBL_MON' => '',
    'LBL_TUE' => '',
    'LBL_WED' => '',
    'LBL_THU' => '',
    'LBL_FRI' => '',
    'LBL_SAT' => '',
    'LBL_ALL' => '',
    'LBL_EVERY' => '',
    'LBL_FROM' => '',
    'LBL_ON_THE' => '',
    'LBL_RANGE' => '',
    'LBL_AND' => '',
    'LBL_MINUTES' => '',
    'LBL_HOUR' => '',
    'LBL_HOUR_SING' => '',
    'LBL_OFTEN' => '',
    'LBL_MIN_MARK' => '',


// crontabs
    'LBL_MINS' => '',
    'LBL_HOURS' => '',
    'LBL_DAY_OF_MONTH' => '',
    'LBL_MONTHS' => '',
    'LBL_DAY_OF_WEEK' => '',
    'LBL_CRONTAB_EXAMPLES' => '',
// Labels
    'LBL_ALWAYS' => '',
    'LBL_CATCH_UP' => '',
    'LBL_CATCH_UP_WARNING' => '',
    'LBL_DATE_TIME_END' => '',
    'LBL_DATE_TIME_START' => '',
    'LBL_INTERVAL' => '',
    'LBL_JOB' => '',
    'LBL_JOB_URL' => '',
    'LBL_LAST_RUN' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NAME' => '',
    'LBL_NEVER' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_PERENNIAL' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_SCHEDULER' => '',
    'LBL_STATUS' => '',
    'LBL_TIME_FROM' => '',
    'LBL_TIME_TO' => '',
    'LBL_WARN_CURL_TITLE' => '',
    'LBL_WARN_CURL' => '',
    'LBL_WARN_NO_CURL' => '',
    'LBL_BASIC_OPTIONS' => '',
    'LBL_ADV_OPTIONS' => '',
    'LBL_TOGGLE_ADV' => '',
    'LBL_TOGGLE_BASIC' => '',
// Links
    'LNK_LIST_SCHEDULER' => '',
    'LNK_NEW_SCHEDULER' => '',
// Messages
    'ERR_CRON_SYNTAX' => '',
    'NTC_LIST_ORDER' => '',
    'LBL_CRON_INSTRUCTIONS_WINDOWS' => '',
    'LBL_CRON_INSTRUCTIONS_LINUX' => '',
    'LBL_CRON_LINUX_DESC1' => '',
    'LBL_CRON_LINUX_DESC2' => '',
    'LBL_CRON_LINUX_DESC3' => '',
    'LBL_CRON_WINDOWS_DESC' => '',
// Subpanels
    'LBL_JOBS_SUBPANEL_TITLE' => '',
    'LBL_EXECUTE_TIME' => '',

//jobstrings
    'LBL_REFRESHJOBS' => '',
    'LBL_POLLMONITOREDINBOXES' => '',
    'LBL_PERFORMFULLFTSINDEX' => '',

    'LBL_RUNMASSEMAILCAMPAIGN' => '',
    'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '',
    'LBL_PRUNEDATABASE' => '',
    'LBL_TRIMTRACKER' => '',
    'LBL_TRIMSUGARFEEDS' => '',
    'LBL_SENDEMAILREMINDERS' => '',
    'LBL_CLEANJOBQUEUE' => '',
    'LBL_REMOVEDOCUMENTSFROMFS' => '',

    'LBL_AODOPTIMISEINDEX' => '',
    'LBL_AODINDEXUNINDEXED' => '',
    'LBL_POLLMONITOREDINBOXESAOP' => '',
    'LBL_AORRUNSCHEDULEDREPORTS' => '',
    'LBL_PROCESSAOW_WORKFLOW' => '',

    'LBL_RUNELASTICSEARCHINDEXERSCHEDULER' => '',

    'LBL_SCHEDULER_TIMES' => '',
    'LBL_SYNCGOOGLECALENDAR' => '',
);

global $sugar_config;
