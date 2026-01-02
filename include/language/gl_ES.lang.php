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



$app_list_strings = array(
//e.g. auf Deutsch 'Contacts'=>'Contakten',
    'language_pack_name' => '',
    'moduleList' => array(
        'Home' => '',
        'ResourceCalendar' => '',
        'Contacts' => '',
        'Accounts' => '',
        'Alerts' => '',
        'Opportunities' => '',
        'Cases' => '',
        'Notes' => '',
        'Calls' => '',
        'TemplateSectionLine' => '',
        'Calls_Reschedule' => '',
        'Emails' => '',
        'EAPM' => '',
        'Meetings' => '',
        'Tasks' => '',
        'Calendar' => '',
        'Leads' => '',
        'Currencies' => '',
        'Activities' => '',
        'Bugs' => '',
        'Feeds' => '',
        'iFrames' => '',
        'TimePeriods' => '',
        'ContractTypes' => '',
        'Schedulers' => '',
        'Project' => '',
        'ProjectTask' => '',
        'Campaigns' => '',
        'CampaignLog' => '',
        'Documents' => '',
        'DocumentRevisions' => '',
        'Connectors' => '',
        'Roles' => '',
        'Notifications' => '',
        'Sync' => '',
        'Users' => '',
        'Employees' => '',
        'Administration' => '',
        'ACLRoles' => '',
        'InboundEmail' => '',
        'Releases' => '',
        'Prospects' => '',
        'Queues' => '',
        'EmailMarketing' => '',
        'EmailTemplates' => '',
        'ProspectLists' => '',
        'SavedSearch' => '',
        'UpgradeWizard' => '',
        'Trackers' => '',
        'TrackerSessions' => '',
        'TrackerQueries' => '',
        'FAQ' => '',
        'Newsletters' => '',
        'SugarFeed' => '',
        'SugarFavorites' => '',

        'OAuthKeys' => '',
        'OAuthTokens' => '',
        'OAuth2Clients' => '',
        'OAuth2Tokens' => '',
    ),

    'moduleListSingular' => array(
        'Home' => '',
        'Dashboard' => '',
        'Contacts' => '',
        'Accounts' => '',
        'Opportunities' => '',
        'Cases' => '',
        'Notes' => '',
        'Calls' => '',
        'Emails' => '',
        'EmailTemplates' => '',
        'Meetings' => '',
        'Tasks' => '',
        'Calendar' => '',
        'Leads' => '',
        'Activities' => '',
        'Bugs' => '',
        'KBDocuments' => '',
        'Feeds' => '',
        'iFrames' => '',
        'TimePeriods' => '',
        'Project' => '',
        'ProjectTask' => '',
        'Prospects' => '',
        'Campaigns' => '',
        'Documents' => '',
        'Sync' => '',
        'Users' => '',
        'SugarFavorites' => '',

    ),

    'checkbox_dom' => array(
        '' => '',
        '1' => '',
        '2' => '',
    ),

    //e.g. en francais 'Analyst'=>'Analyste',
    'account_type_dom' => array(
        '' => '',
        'Analyst' => '',
        'Competitor' => '',
        'Customer' => '',
        'Integrator' => '',
        'Investor' => '',
        'Partner' => '',
        'Press' => '',
        'Prospect' => '',
        'Reseller' => '',
        'Other' => '',
    ),
    //e.g. en espanol 'Apparel'=>'Ropa',
    'industry_dom' => array(
        '' => '',
        'Apparel' => '',
        'Banking' => '',
        'Biotechnology' => '',
        'Chemicals' => '',
        'Communications' => '',
        'Construction' => '',
        'Consulting' => '',
        'Education' => '',
        'Electronics' => '',
        'Energy' => '',
        'Engineering' => '',
        'Entertainment' => '',
        'Environmental' => '',
        'Finance' => '',
        'Government' => '',
        'Healthcare' => '',
        'Hospitality' => '',
        'Insurance' => '',
        'Machinery' => '',
        'Manufacturing' => '',
        'Media' => '',
        'Not For Profit' => '',
        'Recreation' => '',
        'Retail' => '',
        'Shipping' => '',
        'Technology' => '',
        'Telecommunications' => '',
        'Transportation' => '',
        'Utilities' => '',
        'Other' => '',
    ),
    'lead_source_default_key' => '',
    'lead_source_dom' => array(
        '' => '',
        'Cold Call' => '',
        'Existing Customer' => '',
        'Self Generated' => '',
        'Employee' => '',
        'Partner' => '',
        'Public Relations' => '',
        'Direct Mail' => '',
        'Conference' => '',
        'Trade Show' => '',
        'Web Site' => '',
        'Word of mouth' => '',
        'Email' => '',
        'Campaign' => '',
        'Other' => '',
    ),
    'language_dom' => array(
        'af' => '',
        'ar-EG' => '',
        'ar-SA' => '',
        'az' => '',
        'bg' => '',
        'bn' => '',
        'bs' => '',
        'ca' => '',
        'ceb' => '',
        'cs' => '',
        'da' => '',
        'de' => '',
        'de-CH' => '',
        'el' => '',
        'en-GB' => '',
        'en-US' => '',
        'es-ES' => '',
        'es-MX' => '',
        'es-PY' => '',
        'es-VE' => '',
        'et' => '',
        'eu' => '',
        'fa' => '',
        'fi' => '',
        'fil' => '',
        'fr' => '',
        'fr-CA' => '',
        'gu-IN' => '',
        'he' => '',
        'hi' => '',
        'hr' => '',
        'hu' => '',
        'hy-AM' => '',
        'id' => '',
        'it' => '',
        'ja' => '',
        'ka' => '',
        'ko' => '',
        'lt' => '',
        'lv' => '',
        'mk' => '',
        'nb' => '',
        'nl' => '',
        'pcm' => '',
        'pl' => '',
        'pt-BR' => '',
        'pt-PT' => '',
        'ro' => '',
        'ru' => '',
        'si-LK' => '',
        'sk' => '',
        'sl' => '',
        'sq' => '',
        'sr-CS' => '',
        'sv-SE' => '',
        'th' => '',
        'tl' => '',
        'tr' => '',
        'uk' => '',
        'ur-IN' => '',
        'ur-PK' => '',
        'vi' => '',
        'yo' => '',
        'zh-CN' => '',
        'zh-TW' => '',
        'other' => '',
    ),
    'opportunity_type_dom' => array(
        '' => '',
        'Existing Business' => '',
        'New Business' => '',
    ),
    'roi_type_dom' => array(
        'Revenue' => '',
        'Investment' => '',
        'Expected_Revenue' => '',
        'Budget' => '',

    ),
    //Note:  do not translate opportunity_relationship_type_default_key
//       it is the key for the default opportunity_relationship_type_dom value
    'opportunity_relationship_type_default_key' => '',
    'opportunity_relationship_type_dom' => array(
        '' => '',
        'Primary Decision Maker' => '',
        'Business Decision Maker' => '',
        'Business Evaluator' => '',
        'Technical Decision Maker' => '',
        'Technical Evaluator' => '',
        'Executive Sponsor' => '',
        'Influencer' => '',
        'Other' => '',
    ),
    //Note:  do not translate case_relationship_type_default_key
//       it is the key for the default case_relationship_type_dom value
    'case_relationship_type_default_key' => '',
    'case_relationship_type_dom' => array(
        '' => '',
        'Primary Contact' => '',
        'Alternate Contact' => '',
    ),
    'payment_terms' => array(
        '' => '',
        'Net 15' => '',
        'Net 30' => '',
    ),
    'sales_stage_default_key' => '',
    'sales_stage_dom' => array(
        'Prospecting' => '',
        'Qualification' => '',
        'Needs Analysis' => '',
        'Value Proposition' => '',
        'Id. Decision Makers' => '',
        'Perception Analysis' => '',
        'Proposal/Price Quote' => '',
        'Negotiation/Review' => '',
        'Closed Won' => '',
        'Closed Lost' => '',
    ),
    'sales_probability_dom' => // keys must be the same as sales_stage_dom
        array(
            'Prospecting' => '',
            'Qualification' => '',
            'Needs Analysis' => '',
            'Value Proposition' => '',
            'Id. Decision Makers' => '',
            'Perception Analysis' => '',
            'Proposal/Price Quote' => '',
            'Negotiation/Review' => '',
            'Closed Won' => '',
            'Closed Lost' => '',
        ),
    'activity_dom' => array(
        'Call' => '',
        'Meeting' => '',
        'Task' => '',
        'Email' => '',
        'Note' => '',
    ),
    'salutation_dom' => array(
        '' => '',
        'Mr.' => '',
        'Ms.' => '',
        'Mrs.' => '',
        'Miss' => '',
        'Dr.' => '',
        'Prof.' => '',
    ),
    //time is in seconds; the greater the time the longer it takes;
    'reminder_max_time' => 90000,
    'reminder_time_options' => array(
        60 => '',
        300 => '',
        600 => '',
        900 => '',
        1800 => '',
        3600 => '',
        7200 => '',
        10800 => '',
        18000 => '',
        86400 => '',
    ),

    'task_priority_default' => '',
    'task_priority_dom' => array(
        'High' => '',
        'Medium' => '',
        'Low' => '',
    ),
    'task_status_default' => '',
    'task_status_dom' => array(
        'Not Started' => '',
        'In Progress' => '',
        'Completed' => '',
        'Pending Input' => '',
        'Deferred' => '',
    ),
    'meeting_status_default' => '',
    'meeting_status_dom' => array(
        'Planned' => '',
        'Held' => '',
        'Not Held' => '',
    ),
    'extapi_meeting_password' => array(
        'WebEx' => '',
    ),
    'meeting_type_dom' => array(
        'Other' => '',
        'Sugar' => '',
    ),
    'call_status_default' => '',
    'call_status_dom' => array(
        'Planned' => '',
        'Held' => '',
        'Not Held' => '',
    ),
    'call_direction_default' => '',
    'call_direction_dom' => array(
        'Inbound' => '',
        'Outbound' => '',
    ),
    'lead_status_dom' => array(
        '' => '',
        'New' => '',
        'Assigned' => '',
        'In Process' => '',
        'Converted' => '',
        'Recycled' => '',
        'Dead' => '',
    ),
    'case_priority_default_key' => '',
    'case_priority_dom' => array(
        'P1' => '',
        'P2' => '',
        'P3' => '',
    ),
    'user_type_dom' => array(
        'RegularUser' => '',
        'Administrator' => '',
    ),
    'user_status_dom' => array(
        'Active' => '',
        'Inactive' => '',
    ),
    'user_factor_auth_interface_dom' => array(
        'FactorAuthEmailCode' => '',
    ),
    'employee_status_dom' => array(
        'Active' => '',
        'Terminated' => '',
        'Leave of Absence' => '',
    ),
    'messenger_type_dom' => array(
        '' => '',
        'MSN' => '',
        'Yahoo!' => '',
        'AOL' => '',
    ),
    'project_task_priority_options' => array(
        'High' => '',
        'Medium' => '',
        'Low' => '',
    ),
    'project_task_priority_default' => '',

    'project_task_status_options' => array(
        'Not Started' => '',
        'In Progress' => '',
        'Completed' => '',
        'Pending Input' => '',
        'Deferred' => '',
    ),
    'project_task_utilization_options' => array(
        '0' => '',
        '25' => '',
        '50' => '',
        '75' => '',
        '100' => '',
    ),

    'project_status_dom' => array(
        'Draft' => '',
        'In Review' => '',
        'Underway' => '',
        'On_Hold' => '',
        'Completed' => '',
    ),
    'project_status_default' => '',

    'project_duration_units_dom' => array(
        'Days' => '',
        'Hours' => '',
    ),

    'activity_status_type_dom' => array(
        '' => '',
        'active' => '',
        'inactive' => '',
    ),

    // Note:  do not translate record_type_default_key
    //        it is the key for the default record_type_module value
    'record_type_default_key' => '',
    'record_type_display' => array(
        '' => '',
        'Accounts' => '',
        'Opportunities' => '',
        'Cases' => '',
        'Leads' => '',
        'Contacts' => '', // cn (11/22/2005) added to support Emails

        'Bugs' => '',
        'Project' => '',

        'Prospects' => '',
        'ProjectTask' => '',

        'Tasks' => '',

        'AOS_Contracts' => '',
        'AOS_Invoices' => '',
        'AOS_Quotes' => '',
        'AOS_Products' => '',

    ),
// PR 4606
    'record_type_display_notes' => array(
        'Accounts' => '',
        'Contacts' => '',
        'Opportunities' => '',
        'Campaigns' => '',
        'Tasks' => '',
        'Emails' => '',

        'Bugs' => '',
        'Project' => '',
        'ProjectTask' => '',
        'Prospects' => '',
        'Cases' => '',
        'Leads' => '',

        'Meetings' => '',
        'Calls' => '',

        'AOS_Contracts' => '',
        'AOS_Invoices' => '',
        'AOS_Quotes' => '',
        'AOS_Products' => '',
    ),

    'parent_type_display' => array(
        'Accounts' => '',
        'Contacts' => '',
        'Tasks' => '',
        'Opportunities' => '',

        'Bugs' => '',
        'Cases' => '',
        'Leads' => '',

        'Project' => '',
        'ProjectTask' => '',

        'Prospects' => '',
        
        'AOS_Contracts' => '',
        'AOS_Invoices' => '',
        'AOS_Quotes' => '',
        'AOS_Products' => '', 

    ),
    'parent_line_items' => array(
        'AOS_Quotes' => '',
        'AOS_Invoices' => '',
        'AOS_Contracts' => '',
    ),
    'issue_priority_default_key' => '',
    'issue_priority_dom' => array(
        'Urgent' => '',
        'High' => '',
        'Medium' => '',
        'Low' => '',
    ),
    'issue_resolution_default_key' => '',
    'issue_resolution_dom' => array(
        '' => '',
        'Accepted' => '',
        'Duplicate' => '',
        'Closed' => '',
        'Out of Date' => '',
        'Invalid' => '',
    ),

    'issue_status_default_key' => '',
    'issue_status_dom' => array(
        'New' => '',
        'Assigned' => '',
        'Closed' => '',
        'Pending' => '',
        'Rejected' => '',
    ),

    'bug_priority_default_key' => '',
    'bug_priority_dom' => array(
        'Urgent' => '',
        'High' => '',
        'Medium' => '',
        'Low' => '',
    ),
    'bug_resolution_default_key' => '',
    'bug_resolution_dom' => array(
        '' => '',
        'Accepted' => '',
        'Duplicate' => '',
        'Fixed' => '',
        'Out of Date' => '',
        'Invalid' => '',
        'Later' => '',
    ),
    'bug_status_default_key' => '',
    'bug_status_dom' => array(
        'New' => '',
        'Assigned' => '',
        'Closed' => '',
        'Pending' => '',
        'Rejected' => '',
    ),
    'bug_type_default_key' => '',
    'bug_type_dom' => array(
        'Defect' => '',
        'Feature' => '',
    ),
    'case_type_dom' => array(
        'Administration' => '',
        'Product' => '',
        'User' => '',
    ),

    'source_default_key' => '',
    'source_dom' => array(
        '' => '',
        'Internal' => '',
        'Forum' => '',
        'Web' => '',
        'InboundEmail' => '',
    ),

    'product_category_default_key' => '',
    'product_category_dom' => array(
        '' => '',
        'Accounts' => '',
        'Activities' => '',
        'Bugs' => '',
        'Calendar' => '',
        'Calls' => '',
        'Campaigns' => '',
        'Cases' => '',
        'Contacts' => '',
        'Currencies' => '',
        'Dashboard' => '',
        'Documents' => '',
        'Emails' => '',
        'Feeds' => '',
        'Forecasts' => '',
        'Help' => '',
        'Home' => '',
        'Leads' => '',
        'Meetings' => '',
        'Notes' => '',
        'Opportunities' => '',
        'Outlook Plugin' => '',
        'Projects' => '',
        'Quotes' => '',
        'Releases' => '',
        'RSS' => '',
        'Studio' => '',
        'Upgrade' => '',
        'Users' => '',
    ),
    /*Added entries 'Queued' and 'Sending' for 4.0 release..*/
    'campaign_status_dom' => array(
        '' => '',
        'Planning' => '',
        'Active' => '',
        'Inactive' => '',
        'Complete' => '',
        //'In Queue' => 'In Queue',
        //'Sending' => 'Sending',
    ),
    'campaign_type_dom' => array(
        '' => '',
        'Telesales' => '',
        'Mail' => '',
        'Email' => '',
        'Print' => '',
        'Web' => '',
        'Radio' => '',
        'Television' => '',
        'NewsLetter' => '',
    ),

    'newsletter_frequency_dom' => array(
        '' => '',
        'Weekly' => '',
        'Monthly' => '',
        'Quarterly' => '',
        'Annually' => '',
    ),

    'notifymail_sendtype' => array(
        'SMTP' => '',
    ),
    'dom_cal_month_long' => array(
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
        '8' => '',
        '9' => '',
        '10' => '',
        '11' => '',
        '12' => '',
    ),
    'dom_cal_month_short' => array(
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
        '8' => '',
        '9' => '',
        '10' => '',
        '11' => '',
        '12' => '',
    ),
    'dom_cal_day_long' => array(
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
    ),
    'dom_cal_day_short' => array(
        '0' => '',
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
        '6' => '',
        '7' => '',
    ),
    'dom_meridiem_lowercase' => array(
        'am' => '',
        'pm' => '',
    ),
    'dom_meridiem_uppercase' => array(
        'AM' => '',
        'PM' => '',
    ),

    'dom_inbound_email_account_types' => [
        'personal' => '',
        'group' => '',
        'bounce' => '',
    ],

    'dom_inbound_email_auth_types' => [
        'basic' => '',
        'oauth' => '',
    ],

    'dom_external_oauth_connection_types' => [
        'personal' => '',
        'group' => '',
    ],

    'dom_external_oauth_provider_types' => [
        'personal' => '',
        'group' => '',
    ],

    'dom_outbound_email_account_types' => [
        'user' => '',
        'group' => '',
        'system' => '',
        'system-override' => '',
    ],

    'dom_inbound_email_account_status' => [
        'Active' => '',
        'Inactive' => '',
    ],

    'dom_email_body_filtering_option' => [
      'multi' => '',
      'single' => '',
    ],

    'dom_email_types' => array(
        'out' => '',
        'archived' => '',
        'draft' => '',
        'inbound' => '',
        'campaign' => '',
    ),
    'dom_email_status' => array(
        'archived' => '',
        'closed' => '',
        'draft' => '',
        'read' => '',
        'replied' => '',
        'sent' => '',
        'send_error' => '',
        'unread' => '',
    ),
    'dom_email_archived_status' => array(
        'archived' => '',
    ),

    'dom_email_server_type' => array(
        '' => '',
        'imap' => '',
    ),
    'dom_mailbox_type' => array(/*''           => '--None Specified--',*/
        'pick' => '',
        'createcase' => '',
        'bounce' => '',
    ),
    'dom_email_distribution' => array(
        '' => '',
        'direct' => '',
        'roundRobin' => '',
        'leastBusy' => '',
    ),
    'dom_email_errors' => array(
        1 => '',
        2 => '',
    ),
    'dom_email_bool' => array(
        'bool_true' => '',
        'bool_false' => '',
    ),
    'dom_int_bool' => array(
        1 => '',
        0 => '',
    ),
    'dom_switch_bool' => array(
        'on' => '',
        'off' => '',
        '' => '',
    ),

    'dom_email_link_type' => array(
        'sugar' => '',
        'mailto' => '',
    ),

    'dom_editor_type' => array(
        'none' => '',
        'tinymce' => '',
        'mozaik' => '',
    ),

    'dom_email_editor_option' => array(
        '' => '',
        'html' => '',
        'plain' => '',
    ),

    'schedulers_times_dom' => array(
        'not run' => '',
        'ready' => '',
        'in progress' => '',
        'failed' => '',
        'completed' => '',
        'no curl' => '',
    ),

    'scheduler_status_dom' => array(
        'Active' => '',
        'Inactive' => '',
    ),

    'scheduler_period_dom' => array(
        'min' => '',
        'hour' => '',
    ),
    'document_category_dom' => array(
        '' => '',
        'Marketing' => '',
        'Knowledege Base' => '',
        'Sales' => '',
    ),

    'email_category_dom' => array(
        '' => '',
        'Archived' => '',
        // TODO: add more categories here...
    ),

    'document_subcategory_dom' => array(
        '' => '',
        'Marketing Collateral' => '',
        'Product Brochures' => '',
        'FAQ' => '',
    ),

    'document_status_dom' => array(
        'Active' => '',
        'Draft' => '',
        'FAQ' => '',
        'Expired' => '',
        'Under Review' => '',
        'Pending' => '',
    ),
    'document_template_type_dom' => array(
        '' => '',
        'mailmerge' => '',
        'eula' => '',
        'nda' => '',
        'license' => '',
    ),
    'dom_meeting_accept_options' => array(
        'accept' => '',
        'decline' => '',
        'tentative' => '',
    ),
    'dom_meeting_accept_status' => array(
        'accept' => '',
        'decline' => '',
        'tentative' => '',
        'none' => '',
    ),
    'duration_intervals' => array(
        '0' => '',
        '15' => '',
        '30' => '',
        '45' => '',
    ),
    'repeat_type_dom' => array(
        '' => '',
        'Daily' => '',
        'Weekly' => '',
        'Monthly' => '',
        'Yearly' => '',
    ),

    'repeat_intervals' => array(
        '' => '',
        'Daily' => '',
        'Weekly' => '',
        'Monthly' => '',
        'Yearly' => '',
    ),

    'duration_dom' => array(
        '' => '',
        '900' => '',
        '1800' => '',
        '2700' => '',
        '3600' => '',
        '5400' => '',
        '7200' => '',
        '10800' => '',
        '21600' => '',
        '86400' => '',
        '172800' => '',
        '259200' => '',
        '604800' => '',
    ),


//prospect list type dom
    'prospect_list_type_dom' => array(
        'default' => '',
        'seed' => '',
        'exempt_domain' => '',
        'exempt_address' => '',
        'exempt' => '',
        'test' => '',
    ),

    'email_settings_num_dom' => array(
        '10' => '',
        '20' => '',
        '50' => '',
    ),
    'email_marketing_status_dom' => array(
        '' => '',
        'active' => '',
        'inactive' => '',
    ),

    'campainglog_activity_type_dom' => array(
        '' => '',
        'targeted' => '',
        'send error' => '',
        'invalid email' => '',
        'link' => '',
        'viewed' => '',
        'removed' => '',
        'lead' => '',
        'contact' => '',
        'blocked' => '',
        'Survey' => '',
    ),

    'campainglog_target_type_dom' => array(
        'Contacts' => '',
        'Users' => '',
        'Prospects' => '',
        'Leads' => '',
        'Accounts' => '',
    ),
    'merge_operators_dom' => array(
        'like' => '',
        'exact' => '',
        'start' => '',
    ),

    'custom_fields_importable_dom' => array(
        'true' => '',
        'false' => '',
        'required' => '',
    ),

    'custom_fields_merge_dup_dom' => array(
        0 => '',
        1 => '',
        2 => '',
        3 => '',
        4 => '',
    ),

    'projects_priority_options' => array(
        'high' => '',
        'medium' => '',
        'low' => '',
    ),

    'projects_status_options' => array(
        'notstarted' => '',
        'inprogress' => '',
        'completed' => '',
    ),
    // strings to pass to Flash charts
    'chart_strings' => array(
        'expandlegend' => '',
        'collapselegend' => '',
        'clickfordrilldown' => '',
        'detailview' => '',
        'piechart' => '',
        'groupchart' => '',
        'stackedchart' => '',
        'barchart' => '',
        'horizontalbarchart' => '',
        'linechart' => '',
        'noData' => '',
        'print' => '',
        'pieWedgeName' => '',
    ),
    'release_status_dom' => array(
        'Active' => '',
        'Inactive' => '',
    ),
    'email_settings_for_ssl' => array(
        '0' => '',
        '1' => '',
        '2' => '',
    ),
    'import_enclosure_options' => array(
        '\'' => '',
        '"' => '',
        '' => '',
        'other' => '',
    ),
    'import_delimeter_options' => array(
        ',' => '',
        ';' => '',
        '\t' => '',
        '.' => '',
        ':' => '',
        '|' => '',
        'other' => '',
    ),
    'link_target_dom' => array(
        '_blank' => '',
        '_self' => '',
    ),
    'dashlet_auto_refresh_options' => array(
        '-1' => '',
        '30' => '',
        '60' => '',
        '180' => '',
        '300' => '',
        '600' => '',
    ),
    'dashlet_auto_refresh_options_admin' => array(
        '-1' => '',
        '30' => '',
        '60' => '',
        '180' => '',
        '300' => '',
        '600' => '',
    ),
    'date_range_search_dom' => array(
        '=' => '',
        'not_equal' => '',
        'greater_than' => '',
        'less_than' => '',
        'last_7_days' => '',
        'next_7_days' => '',
        'last_30_days' => '',
        'next_30_days' => '',
        'last_month' => '',
        'this_month' => '',
        'next_month' => '',
        'last_year' => '',
        'this_year' => '',
        'next_year' => '',
        'between' => '',
    ),
    'numeric_range_search_dom' => array(
        '=' => '',
        'not_equal' => '',
        'greater_than' => '',
        'greater_than_equals' => '',
        'less_than' => '',
        'less_than_equals' => '',
        'between' => '',
    ),
    'lead_conv_activity_opt' => array(
        'copy' => '',
        'move' => '',
        'donothing' => '',
    ),
    // PR 6009
    'inboundmail_assign_replies_to_admin' => array(
        'donothing' => '',
        'repliedtoowner' => '',
        'recordowner' => '',
    ),
);

$app_strings = array(
    'LBL_SEARCH_REAULTS_TITLE' => '',
    'ERR_SEARCH_INVALID_QUERY' => '',
    'ERR_SEARCH_NO_RESULTS' => '',
    'LBL_SEARCH_PERFORMED_IN' => '',
    'LBL_EMAIL_CODE' => '',
    'LBL_SEND' => '',
    'LBL_LOGOUT' => '',
    'LBL_TOUR_NEXT' => '',
    'LBL_TOUR_SKIP' => '',
    'LBL_TOUR_BACK' => '',
    'LBL_TOUR_TAKE_TOUR' => '',
    'LBL_MOREDETAIL' => '' /*for 508 compliance fix*/,
    'LBL_EDIT_INLINE' => '' /*for 508 compliance fix*/,
    'LBL_VIEW_INLINE' => '' /*for 508 compliance fix*/,
    'LBL_BASIC_SEARCH' => '' /*for 508 compliance fix*/,
    'LBL_Blank' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_ADD' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_ADD_EMAIL' => '' /*for 508 compliance fix*/,
    'LBL_HIDE_SHOW' => '' /*for 508 compliance fix*/,
    'LBL_DELETE_INLINE' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_CLEAR' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_VCARD' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_REMOVE' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_REMOVE_EMAIL' => '' /*for 508 compliance fix*/,
    'LBL_ID_FF_OPT_OUT' => '',
    'LBL_ID_FF_OPT_IN' => '',
    'LBL_ID_FF_INVALID' => '',
    'LBL_ADD' => '' /*for 508 compliance fix*/,
    'LBL_COMPANY_LOGO' => '' /*for 508 compliance fix*/,
    'LBL_CONNECTORS_POPUPS' => '',
    'LBL_CLOSEINLINE' => '',
    'LBL_VIEWINLINE' => '',
    'LBL_INFOINLINE' => '',
    'LBL_PRINT' => '',
    'LBL_HELP' => '',
    'LBL_ID_FF_SELECT' => '',
    'DEFAULT' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_SORT' => '',
    'LBL_EMAIL_SMTP_SSL_OR_TLS' => '',
    'LBL_NO_ACTION' => '',
    'LBL_NO_SHORTCUT_MENU' => '',
    'LBL_NO_DATA' => '',

    'LBL_ERROR_UNDEFINED_BEHAVIOR' => '', //PR 3669
    'LBL_ERROR_UNHANDLED_VALUE' => '', //PR 3669
    'LBL_ERROR_UNUSABLE_VALUE' => '', //PR 3669
    'LBL_ERROR_INVALID_TYPE' => '', //PR 3669

    'LBL_ROUTING_FLAGGED' => '',
    'LBL_NOTIFICATIONS' => '',

    'LBL_ROUTING_TO' => '',
    'LBL_ROUTING_TO_ADDRESS' => '',
    'LBL_ROUTING_WITH_TEMPLATE' => '',

    'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM' => '',
    'LBL_DROP_HERE' => '',
    'LBL_EMAIL_ACCOUNTS_GMAIL_DEFAULTS' => '',
    'LBL_EMAIL_ACCOUNTS_NAME' => '',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND' => '',
    'LBL_EMAIL_ACCOUNTS_SMTPPASS' => '',
    'LBL_EMAIL_ACCOUNTS_SMTPPORT' => '',
    'LBL_EMAIL_ACCOUNTS_SMTPSERVER' => '',
    'LBL_EMAIL_ACCOUNTS_SMTPUSER' => '',
    'LBL_EMAIL_ACCOUNTS_SMTPDEFAULT' => '',
    'LBL_EMAIL_WARNING_MISSING_USER_CREDS' => '',
    'LBL_EMAIL_ACCOUNTS_SUBTITLE' => '',
    'LBL_EMAIL_ACCOUNTS_OUTBOUND_SUBTITLE' => '',

    'LBL_EMAIL_ADDRESS_BOOK_ADD' => '',
    'LBL_EMAIL_ADDRESS_BOOK_CLEAR' => '',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_TO' => '',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_CC' => '',
    'LBL_EMAIL_ADDRESS_BOOK_ADD_BCC' => '',
    'LBL_EMAIL_ADDRESS_BOOK_ADRRESS_TYPE' => '',
    'LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR' => '',
    'LBL_EMAIL_ADDRESS_BOOK_FILTER' => '',
    'LBL_EMAIL_ADDRESS_BOOK_NAME' => '',
    'LBL_EMAIL_ADDRESS_BOOK_NOT_FOUND' => '',
    'LBL_EMAIL_ADDRESS_BOOK_SAVE_AND_ADD' => '',
    'LBL_EMAIL_ADDRESS_BOOK_SELECT_TITLE' => '',
    'LBL_EMAIL_ADDRESS_BOOK_TITLE' => '',
    'LBL_EMAIL_REPORTS_TITLE' => '',
    'LBL_EMAIL_REMOVE_SMTP_WARNING' => '',
    'LBL_EMAIL_ADDRESSES' => '',
    'LBL_EMAIL_ADDRESS_PRIMARY' => '',
    'LBL_EMAIL_ADDRESS_OPT_IN' => '',
    'LBL_EMAIL_ADDRESS_OPT_IN_ERR' => '',
    'LBL_EMAIL_ARCHIVE_TO_SUITE' => '',
    'LBL_EMAIL_ASSIGNMENT' => '',
    'LBL_EMAIL_ATTACH_FILE_TO_EMAIL' => '',
    'LBL_EMAIL_ATTACHMENT' => '',
    'LBL_EMAIL_ATTACHMENTS' => '',
    'LBL_EMAIL_ATTACHMENTS2' => '',
    'LBL_EMAIL_ATTACHMENTS3' => '',
    'LBL_EMAIL_ATTACHMENTS_FILE' => '',
    'LBL_EMAIL_ATTACHMENTS_DOCUMENT' => '',
    'LBL_EMAIL_BCC' => '',
    'LBL_EMAIL_CANCEL' => '',
    'LBL_EMAIL_CC' => '',
    'LBL_EMAIL_CHARSET' => '',
    'LBL_EMAIL_CHECK' => '',
    'LBL_EMAIL_CHECKING_NEW' => '',
    'LBL_EMAIL_CHECKING_DESC' => '',
    'LBL_EMAIL_CLOSE' => '',
    'LBL_EMAIL_COFFEE_BREAK' => '',

    'LBL_EMAIL_COMPOSE' => '',
    'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS' => '',
    'LBL_EMAIL_COMPOSE_NO_BODY' => '',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT' => '',
    'LBL_EMAIL_COMPOSE_NO_SUBJECT_LITERAL' => '',
    'LBL_EMAIL_COMPOSE_INVALID_ADDRESS' => '',

    'LBL_EMAIL_CONFIRM_CLOSE' => '',
    'LBL_EMAIL_CONFIRM_DELETE_SIGNATURE' => '',

    'LBL_EMAIL_SENT_SUCCESS' => '',

    'LBL_EMAIL_CREATE_NEW' => '',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS' => '',
    'LBL_EMAIL_MULT_GROUP_FOLDER_ACCOUNTS_EMPTY' => '',
    'LBL_EMAIL_DATE_SENT_BY_SENDER' => '',
    'LBL_EMAIL_DATE_TODAY' => '',
    'LBL_EMAIL_DELETE' => '',
    'LBL_EMAIL_DELETE_CONFIRM' => '',
    'LBL_EMAIL_DELETE_SUCCESS' => '',
    'LBL_EMAIL_DELETING_MESSAGE' => '',
    'LBL_EMAIL_DETAILS' => '',

    'LBL_EMAIL_EDIT_CONTACT_WARN' => '',

    'LBL_EMAIL_EMPTYING_TRASH' => '',
    'LBL_EMAIL_DELETING_OUTBOUND' => '',
    'LBL_EMAIL_CLEARING_CACHE_FILES' => '',
    'LBL_EMAIL_EMPTY_MSG' => '',
    'LBL_EMAIL_EMPTY_ADDR_MSG' => '',

    'LBL_EMAIL_ERROR_ADD_GROUP_FOLDER' => '',
    'LBL_EMAIL_ERROR_DELETE_GROUP_FOLDER' => '',
    'LBL_EMAIL_ERROR_CANNOT_FIND_NODE' => '',
    'LBL_EMAIL_ERROR_CHECK_IE_SETTINGS' => '',
    'LBL_EMAIL_ERROR_DESC' => '',
    'LBL_EMAIL_DELETE_ERROR_DESC' => '',
    'LBL_EMAIL_ERROR_DUPE_FOLDER_NAME' => '',
    'LBL_EMAIL_ERROR_EMPTY' => '',
    'LBL_EMAIL_ERROR_GENERAL_TITLE' => '',
    'LBL_EMAIL_ERROR_MESSAGE_DELETED' => '',
    'LBL_EMAIL_ERROR_IMAP_MESSAGE_DELETED' => '',
    'LBL_EMAIL_ERROR_MAILSERVERCONNECTION' => '',
    'LBL_EMAIL_ERROR_MOVE' => '',
    'LBL_EMAIL_ERROR_MOVE_TITLE' => '',
    'LBL_EMAIL_ERROR_NAME' => '',
    'LBL_EMAIL_ERROR_FROM_ADDRESS' => '',
    'LBL_EMAIL_ERROR_NO_FILE' => '',
    'LBL_EMAIL_ERROR_SERVER' => '',
    'LBL_EMAIL_ERROR_SAVE_ACCOUNT' => '',
    'LBL_EMAIL_ERROR_TIMEOUT' => '',
    'LBL_EMAIL_ERROR_USER' => '',
    'LBL_EMAIL_ERROR_PORT' => '',
    'LBL_EMAIL_ERROR_PROTOCOL' => '',
    'LBL_EMAIL_ERROR_MONITORED_FOLDER' => '',
    'LBL_EMAIL_ERROR_TRASH_FOLDER' => '',
    'LBL_EMAIL_ERROR_VIEW_RAW_SOURCE' => '',
    'LBL_EMAIL_ERROR_NO_OUTBOUND' => '',
    'LBL_EMAIL_ERROR_SENDING' => '',
    'LBL_EMAIL_FOLDERS' => SugarThemeRegistry::current()->getImage('icon_email_folder', 'align=absmiddle border=0', null, null, '.gif', '') . '',
    'LBL_EMAIL_FOLDERS_SHORT' => SugarThemeRegistry::current()->getImage('icon_email_folder', 'align=absmiddle border=0', null, null, '.gif', ''),
    'LBL_EMAIL_FOLDERS_ADD' => '',
    'LBL_EMAIL_FOLDERS_ADD_DIALOG_TITLE' => '',
    'LBL_EMAIL_FOLDERS_RENAME_DIALOG_TITLE' => '',
    'LBL_EMAIL_FOLDERS_ADD_NEW_FOLDER' => '',
    'LBL_EMAIL_FOLDERS_ADD_THIS_TO' => '',
    'LBL_EMAIL_FOLDERS_CHANGE_HOME' => '',
    'LBL_EMAIL_FOLDERS_DELETE_CONFIRM' => '',
    'LBL_EMAIL_FOLDERS_NEW_FOLDER' => '',
    'LBL_EMAIL_FOLDERS_NO_VALID_NODE' => '',
    'LBL_EMAIL_FOLDERS_TITLE' => '',

    'LBL_EMAIL_FORWARD' => '',
    'LBL_EMAIL_DELIMITER' => '',
    'LBL_EMAIL_DOWNLOAD_STATUS' => '',
    'LBL_EMAIL_FROM' => '',
    'LBL_EMAIL_GROUP' => '',
    'LBL_EMAIL_UPPER_CASE_GROUP' => '',
    'LBL_EMAIL_HOME_FOLDER' => '',
    'LBL_EMAIL_IE_DELETE' => '',
    'LBL_EMAIL_IE_DELETE_SIGNATURE' => '',
    'LBL_EMAIL_IE_DELETE_CONFIRM' => '',
    'LBL_EMAIL_IE_DELETE_SUCCESSFUL' => '',
    'LBL_EMAIL_IE_SAVE' => '',
    'LBL_EMAIL_IMPORTING_EMAIL' => '',
    'LBL_EMAIL_IMPORT_EMAIL' => '',
    'LBL_EMAIL_IMPORT_SETTINGS' => '',
    'LBL_EMAIL_INVALID' => '',
    'LBL_EMAIL_LOADING' => '',
    'LBL_EMAIL_MARK' => '',
    'LBL_EMAIL_MARK_FLAGGED' => '',
    'LBL_EMAIL_MARK_READ' => '',
    'LBL_EMAIL_MARK_UNFLAGGED' => '',
    'LBL_EMAIL_MARK_UNREAD' => '',
    'LBL_EMAIL_ASSIGN_TO' => '',

    'LBL_EMAIL_MENU_ADD_FOLDER' => '',
    'LBL_EMAIL_MENU_COMPOSE' => '',
    'LBL_EMAIL_MENU_DELETE_FOLDER' => '',
    'LBL_EMAIL_MENU_EMPTY_TRASH' => '',
    'LBL_EMAIL_MENU_SYNCHRONIZE' => '',
    'LBL_EMAIL_MENU_CLEAR_CACHE' => '',
    'LBL_EMAIL_MENU_REMOVE' => '',
    'LBL_EMAIL_MENU_RENAME_FOLDER' => '',
    'LBL_EMAIL_MENU_RENAMING_FOLDER' => '',
    'LBL_EMAIL_MENU_MAKE_SELECTION' => '',

    'LBL_EMAIL_MENU_HELP_ADD_FOLDER' => '',
    'LBL_EMAIL_MENU_HELP_DELETE_FOLDER' => '',
    'LBL_EMAIL_MENU_HELP_EMPTY_TRASH' => '',
    'LBL_EMAIL_MENU_HELP_MARK_READ' => '',
    'LBL_EMAIL_MENU_HELP_MARK_UNFLAGGED' => '',
    'LBL_EMAIL_MENU_HELP_RENAME_FOLDER' => '',

    'LBL_EMAIL_MESSAGES' => '',

    'LBL_EMAIL_ML_NAME' => '',
    'LBL_EMAIL_ML_ADDRESSES_1' => '',
    'LBL_EMAIL_ML_ADDRESSES_2' => '',

    'LBL_EMAIL_MULTISELECT' => '',

    'LBL_EMAIL_NO' => '',
    'LBL_EMAIL_NOT_SENT' => '',

    'LBL_EMAIL_OK' => '',
    'LBL_EMAIL_ONE_MOMENT' => '',
    'LBL_EMAIL_OPEN_ALL' => '',
    'LBL_EMAIL_OPTIONS' => '',
    'LBL_EMAIL_QUICK_COMPOSE' => '',
    'LBL_EMAIL_OPT_OUT' => '',
    'LBL_EMAIL_OPT_IN' => '',
    'LBL_EMAIL_OPT_IN_AND_INVALID' => '',
    'LBL_EMAIL_OPT_OUT_AND_INVALID' => '',
    'LBL_EMAIL_PERFORMING_TASK' => '',
    'LBL_EMAIL_PRIMARY' => '',
    'LBL_EMAIL_PRINT' => '',

    'LBL_EMAIL_QC_BUGS' => '',
    'LBL_EMAIL_QC_CASES' => '',
    'LBL_EMAIL_QC_LEADS' => '',
    'LBL_EMAIL_QC_CONTACTS' => '',
    'LBL_EMAIL_QC_TASKS' => '',
    'LBL_EMAIL_QC_OPPORTUNITIES' => '',
    'LBL_EMAIL_QUICK_CREATE' => '',

    'LBL_EMAIL_REBUILDING_FOLDERS' => '',
    'LBL_EMAIL_RELATE_TO' => '',
    'LBL_EMAIL_VIEW_RELATIONSHIPS' => '',
    'LBL_EMAIL_RECORD' => '',
    'LBL_EMAIL_REMOVE' => '',
    'LBL_EMAIL_REPLY' => '',
    'LBL_EMAIL_REPLY_ALL' => '',
    'LBL_EMAIL_REPLY_TO' => '',
    'LBL_EMAIL_RETRIEVING_MESSAGE' => '',
    'LBL_EMAIL_RETRIEVING_RECORD' => '',
    'LBL_EMAIL_SELECT_ONE_RECORD' => '',
    'LBL_EMAIL_RETURN_TO_VIEW' => '',
    'LBL_EMAIL_REVERT' => '',
    'LBL_EMAIL_RELATE_EMAIL' => '',

    'LBL_EMAIL_RULES_TITLE' => '',

    'LBL_EMAIL_SAVE' => '',
    'LBL_EMAIL_SAVE_AND_REPLY' => '',
    'LBL_EMAIL_SAVE_DRAFT' => '',
    'LBL_EMAIL_DRAFT_SAVED' => '',

    'LBL_EMAIL_SEARCH' => SugarThemeRegistry::current()->getImage('Search', 'align=absmiddle border=0', null, null,    '.gif', ''),
    'LBL_EMAIL_SEARCH_SHORT' => SugarThemeRegistry::current()->getImage('Search', 'align=absmiddle border=0', null,        null, '.gif', ''),
    'LBL_EMAIL_SEARCH_DATE_FROM' => '',
    'LBL_EMAIL_SEARCH_DATE_UNTIL' => '',
    'LBL_EMAIL_SEARCH_NO_RESULTS' => '',
    'LBL_EMAIL_SEARCH_RESULTS_TITLE' => '',

    'LBL_EMAIL_SELECT' => '',

    'LBL_EMAIL_SEND' => '',
    'LBL_EMAIL_SENDING_EMAIL' => '',

    'LBL_EMAIL_SETTINGS' => '',
    'LBL_EMAIL_SETTINGS_ACCOUNTS' => '',
    'LBL_EMAIL_SETTINGS_ADD_ACCOUNT' => '',
    'LBL_EMAIL_SETTINGS_CHECK_INTERVAL' => '',
    'LBL_EMAIL_SETTINGS_FROM_ADDR' => '',
    'LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR' => '',
    'LBL_EMAIL_SETTINGS_FROM_NAME' => '',
    'LBL_EMAIL_SETTINGS_REPLY_TO_ADDR' => '',
    'LBL_EMAIL_SETTINGS_FULL_SYNC' => '',
    'LBL_EMAIL_TEST_NOTIFICATION_SENT' => '',
    'LBL_EMAIL_TEST_SEE_FULL_SMTP_LOG' => '',
    'LBL_EMAIL_SETTINGS_FULL_SYNC_WARN' => '',
    'LBL_EMAIL_SUBSCRIPTION_FOLDER_HELP' => '',
    'LBL_EMAIL_SETTINGS_GENERAL' => '',
    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_CREATE' => '',

    'LBL_EMAIL_SETTINGS_GROUP_FOLDERS_EDIT' => '',

    'LBL_EMAIL_SETTINGS_NAME' => '',
    'LBL_EMAIL_SETTINGS_REQUIRE_REFRESH' => '',
    'LBL_EMAIL_SETTINGS_RETRIEVING_ACCOUNT' => '',
    'LBL_EMAIL_SETTINGS_SAVED' => '',
    'LBL_EMAIL_SETTINGS_SEND_EMAIL_AS' => '',
    'LBL_EMAIL_SETTINGS_SHOW_NUM_IN_LIST' => '',
    'LBL_EMAIL_SETTINGS_TITLE_LAYOUT' => '',
    'LBL_EMAIL_SETTINGS_TITLE_PREFERENCES' => '',
    'LBL_EMAIL_SETTINGS_USER_FOLDERS' => '',
    'LBL_EMAIL_ERROR_PREPEND' => '',
    'LBL_EMAIL_INVALID_PERSONAL_OUTBOUND' => '',
    'LBL_EMAIL_INVALID_SYSTEM_OUTBOUND' => '',
    'LBL_DEFAULT_EMAIL_SIGNATURES' => '',
    'LBL_EMAIL_SIGNATURES' => '',
    'LBL_SMTPTYPE_GMAIL' => '',
    'LBL_SMTPTYPE_YAHOO' => '',
    'LBL_SMTPTYPE_EXCHANGE' => '',
    'LBL_SMTPTYPE_OTHER' => '',
    'LBL_EMAIL_SPACER_MAIL_SERVER' => '',
    'LBL_EMAIL_SPACER_LOCAL_FOLDER' => '',
    'LBL_EMAIL_SUBJECT' => '',
    'LBL_EMAIL_SUCCESS' => '',
    'LBL_EMAIL_SUITE_FOLDER' => '',
    'LBL_EMAIL_TEMPLATE_EDIT_PLAIN_TEXT' => '',
    'LBL_EMAIL_TEMPLATES' => '',
    'LBL_EMAIL_TO' => '',
    'LBL_EMAIL_VIEW' => '',
    'LBL_EMAIL_VIEW_HEADERS' => '',
    'LBL_EMAIL_VIEW_RAW' => '',
    'LBL_EMAIL_VIEW_UNSUPPORTED' => '',
    'LBL_DEFAULT_LINK_TEXT' => '',
    'LBL_EMAIL_YES' => '',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS' => '',
    'LBL_EMAIL_TEST_OUTBOUND_SETTINGS_SENT' => '',
    'LBL_EMAIL_MESSAGE_NO' => '', // Counter. Message number xx
    'LBL_EMAIL_IMPORT_SUCCESS' => '',
    'LBL_EMAIL_IMPORT_FAIL' => '',

    'LBL_LINK_NONE' => '',
    'LBL_LINK_ALL' => '',
    'LBL_LINK_RECORDS' => '',
    'LBL_LINK_SELECT' => '',
    'LBL_LINK_ACTIONS' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_CLOSE_ACTIVITY_HEADER' => '',
    'LBL_CLOSE_ACTIVITY_CONFIRM' => '',
    'LBL_INVALID_FILE_EXTENSION' => '',

    'ERR_AJAX_LOAD' => '',
    'ERR_AJAX_LOAD_FAILURE' => '',
    'ERR_AJAX_LOAD_FOOTER' => '',
    'ERR_DECIMAL_SEP_EQ_THOUSANDS_SEP' => '',
    'ERR_DELETE_RECORD' => '',
    'ERR_EXPORT_DISABLED' => '',
    'ERR_EXPORT_TYPE' => '',
    'ERR_INVALID_EMAIL_ADDRESS' => '',
    'ERR_INVALID_FILE_REFERENCE' => '',
    'ERR_NO_HEADER_ID' => '',
    'ERR_NOT_ADMIN' => '',
    'ERR_MISSING_REQUIRED_FIELDS' => '',
    'ERR_INVALID_REQUIRED_FIELDS' => '',
    'ERR_INVALID_VALUE' => '',
    'ERR_NO_SUCH_FILE' => '',
    'ERR_FILE_EMPTY' => '', // PR 6672
    'ERR_NO_SINGLE_QUOTE' => '',
    'ERR_NOTHING_SELECTED' => '',
    'ERR_SELF_REPORTING' => '',
    'ERR_SQS_NO_MATCH_FIELD' => '',
    'ERR_SQS_NO_MATCH' => '',
    'ERR_ADDRESS_KEY_NOT_SPECIFIED' => '',
    'ERR_EXISTING_PORTAL_USERNAME' => '',
    'ERR_COMPATIBLE_PRECISION_VALUE' => '',
    'ERR_EXTERNAL_API_SAVE_FAIL' => '',
    'ERR_NO_DB' => '',
    'ERR_DB_FAIL' => '',
    'ERR_DB_VERSION' => '',

    'LBL_ACCOUNT' => '',
    'LBL_ACCOUNTS' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_ACCUMULATED_HISTORY_BUTTON_KEY' => '',
    'LBL_ACCUMULATED_HISTORY_BUTTON_LABEL' => '',
    'LBL_ACCUMULATED_HISTORY_BUTTON_TITLE' => '',
    'LBL_ADD_BUTTON' => '',
    'LBL_ADD_DOCUMENT' => '',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY' => '',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL' => '',
    'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL_ACCOUNTS_CONTACTS' => '',
    'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE' => '',
    'LBL_ADDITIONAL_DETAILS' => '',
    'LBL_ADMIN' => '',
    'LBL_ALT_HOT_KEY' => '',
    'LBL_ARCHIVE' => '',
    'LBL_ASSIGNED_TO_USER' => '',
    'LBL_ASSIGNED_TO' => '',
    'LBL_BACK' => '',
    'LBL_BILLING_ADDRESS' => '',
    'LBL_QUICK_CREATE' => '',
    'LBL_BROWSER_TITLE' => '',
    'LBL_BUGS' => '',
    'LBL_BY' => '',
    'LBL_CALLS' => '',
    'LBL_CAMPAIGNS_SEND_QUEUED' => '',
    'LBL_SUBMIT_BUTTON_LABEL' => '',
    'LBL_CASE' => '',
    'LBL_CASES' => '',
    'LBL_CHANGE_PASSWORD' => '',
    'LBL_CHARSET' => '',
    'LBL_CHECKALL' => '',
    'LBL_CITY' => '',
    'LBL_CLEAR_BUTTON_LABEL' => '',
    'LBL_CLEAR_BUTTON_TITLE' => '',
    'LBL_CLEARALL' => '',
    'LBL_CLOSE_BUTTON_TITLE' => '', // As in closing a task
    'LBL_CLOSE_AND_CREATE_BUTTON_LABEL' => '', // As in closing a task
    'LBL_CLOSE_AND_CREATE_BUTTON_TITLE' => '', // As in closing a task
    'LBL_CLOSE_AND_CREATE_BUTTON_KEY' => '',
    'LBL_OPEN_ITEMS' => '',
    'LBL_COMPOSE_EMAIL_BUTTON_KEY' => '',
    'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => '',
    'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => '',
    'LBL_SEARCH_DROPDOWN_YES' => '',
    'LBL_SEARCH_DROPDOWN_NO' => '',
    'LBL_CONTACT_LIST' => '',
    'LBL_CONTACT' => '',
    'LBL_CONTACTS' => '',
    'LBL_CONTRACT' => '',
    'LBL_CONTRACTS' => '',
    'LBL_COUNTRY' => '',
    'LBL_CREATE_BUTTON_LABEL' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_CREATED_BY_USER' => '',
    'LBL_CREATED_USER' => '',
    'LBL_CREATED' => '',
    'LBL_CURRENT_USER_FILTER' => '',
    'LBL_CURRENCY' => '',
    'LBL_DOCUMENTS' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_EDIT_BUTTON' => '',
    // STIC-Custom 20240214 JBL - QuickEdit view
    // https://github.com/SinergiaTIC/SinergiaCRM/pull/93
    'LBL_QUICKEDIT_BUTTON' => '',
    // END STIC-Custom
    'LBL_DUPLICATE_BUTTON' => '',
    'LBL_DELETE_BUTTON' => '',
    'LBL_DELETE' => '',
    'LBL_DELETED' => '',
    'LBL_DIRECT_REPORTS' => '',
    'LBL_DONE_BUTTON_LABEL' => '',
    'LBL_DONE_BUTTON_TITLE' => '',
    'LBL_FAVORITES' => '',
    'LBL_VCARD' => '',
    'LBL_EMPTY_VCARD' => '',
    'LBL_EMPTY_REQUIRED_VCARD' => '',
    'LBL_VCARD_ERROR_FILESIZE' => '',
    'LBL_VCARD_ERROR_DEFAULT' => '',
    'LBL_IMPORT_VCARD' => '',
    'LBL_IMPORT_VCARD_BUTTON_LABEL' => '',
    'LBL_IMPORT_VCARD_BUTTON_TITLE' => '',
    'LBL_VIEW_BUTTON' => '',
    'LBL_EMAIL_PDF_BUTTON_LABEL' => '',
    'LBL_EMAIL_PDF_BUTTON_TITLE' => '',
    'LBL_EMAILS' => '',
    'LBL_EMPLOYEES' => '',
    'LBL_ENTER_DATE' => '',
    'LBL_EXPORT' => '',
    'LBL_FAVORITES_FILTER' => '',
    'LBL_GO_BUTTON_LABEL' => '',
    'LBL_HIDE' => '',
    'LBL_ID' => '',
    'LBL_IMPORT' => '',
    'LBL_IMPORT_STARTED' => '',
    'LBL_LAST_VIEWED' => '',
    'LBL_LEADS' => '',
    'LBL_LESS' => '',
    'LBL_CAMPAIGN' => '',
    'LBL_CAMPAIGNS' => '',
    'LBL_CAMPAIGNLOG' => '',
    'LBL_CAMPAIGN_CONTACT' => '',
    'LBL_CAMPAIGN_ID' => '',
    'LBL_CAMPAIGN_NONE' => '',
    'LBL_THEME' => '',
    'LBL_FOUND_IN_RELEASE' => '',
    'LBL_FIXED_IN_RELEASE' => '',
    'LBL_LIST_ACCOUNT_NAME' => '',
    'LBL_LIST_ASSIGNED_USER' => '',
    'LBL_LIST_CONTACT_NAME' => '',
    'LBL_LIST_CONTACT_ROLE' => '',
    'LBL_LIST_DATE_ENTERED' => '',
    'LBL_LIST_EMAIL' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_OF' => '',
    'LBL_LIST_PHONE' => '',
    'LBL_LIST_RELATED_TO' => '',
    'LBL_LIST_USER_NAME' => '',
    'LBL_LISTVIEW_NO_SELECTED' => '',
    'LBL_LISTVIEW_TWO_REQUIRED' => '',
    'LBL_LISTVIEW_OPTION_SELECTED' => '',
    'LBL_LISTVIEW_SELECTED_OBJECTS' => '',

    'LBL_LOCALE_NAME_EXAMPLE_FIRST' => '',
    'LBL_LOCALE_NAME_EXAMPLE_LAST' => '',
    'LBL_LOCALE_NAME_EXAMPLE_SALUTATION' => '',
    'LBL_LOCALE_NAME_EXAMPLE_TITLE' => '',
    'LBL_CANCEL' => '',
    'LBL_VERIFY' => '',
    'LBL_RESEND' => '',
    'LBL_PROFILE' => '',
    'LBL_MAILMERGE' => '',
    'LBL_MASS_UPDATE' => '',
    // STIC-Custom - 20220704 - JCH - Duplicate & Mass Update
    // STIC#776
    'LBL_MASS_DUPLICATE_UPDATE' => '',
    'LBL_MASS_DUPLICATE_REMOVE_NAME' => '',
    'LBL_MASS_DUPLICATE_UPDATE_CONFIRMATION_NUM' => '',
    'LBL_MASS_DUPLICATE_UPDATE_BTN' => '',
    // END STIC
    'LBL_NO_MASS_UPDATE_FIELDS_AVAILABLE' => '',
    'LBL_OPT_OUT_FLAG_PRIMARY' => '',
    'LBL_OPT_IN_FLAG_PRIMARY' => '',
    'LBL_MEETINGS' => '',
    'LBL_MEETING_GO_BACK' => '',
    'LBL_MEMBERS' => '',
    'LBL_MEMBER_OF' => '',
    'LBL_MODIFIED_BY_USER' => '',
    'LBL_MODIFIED_USER' => '',
    'LBL_MODIFIED' => '',
    'LBL_MODIFIED_NAME' => '',
    'LBL_MORE' => '',
    'LBL_MY_ACCOUNT' => '',
    'LBL_NAME' => '',
    'LBL_NEW_BUTTON_KEY' => '',
    'LBL_NEW_BUTTON_LABEL' => '',
    'LBL_NEW_BUTTON_TITLE' => '',
    'LBL_NEXT_BUTTON_LABEL' => '',
    'LBL_NONE' => '',
    'LBL_NOTES' => '',
    'LBL_OPPORTUNITIES' => '',
    'LBL_OPPORTUNITY_NAME' => '',
    'LBL_OPPORTUNITY' => '',
    'LBL_OR' => '',
    'LBL_PANEL_OVERVIEW' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_PANEL_ASSIGNMENT' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_PANEL_ADVANCED' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_PARENT_TYPE' => '',
    'LBL_PERCENTAGE_SYMBOL' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_PRIMARY_ADDRESS_CITY' => '',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => '',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => '',
    'LBL_PRIMARY_ADDRESS_STATE' => '',
    'LBL_PRIMARY_ADDRESS_STREET_2' => '',
    'LBL_PRIMARY_ADDRESS_STREET_3' => '',
    'LBL_PRIMARY_ADDRESS_STREET' => '',
    'LBL_PRIMARY_ADDRESS' => '',

    'LBL_PROSPECTS' => '',
    'LBL_PRODUCTS' => '',
    'LBL_PROJECT_TASKS' => '',
    'LBL_PROJECTS' => '',
    'LBL_QUOTES' => '',

    'LBL_RELATED' => '',
    'LBL_RELATED_RECORDS' => '',
    'LBL_REMOVE' => '',
    'LBL_REPORTS_TO' => '',
    'LBL_REQUIRED_SYMBOL' => '',
    'LBL_REQUIRED_TITLE' => '',
    'LBL_EMAIL_DONE_BUTTON_LABEL' => '',
    'LBL_FULL_FORM_BUTTON_KEY' => '',
    'LBL_FULL_FORM_BUTTON_LABEL' => '',
    'LBL_FULL_FORM_BUTTON_TITLE' => '',
    'LBL_SAVE_NEW_BUTTON_LABEL' => '',
    'LBL_SAVE_NEW_BUTTON_TITLE' => '',
    'LBL_SAVE_OBJECT' => '',
    'LBL_SEARCH_BUTTON_KEY' => '',
    'LBL_SEARCH_BUTTON_LABEL' => '',
    'LBL_SEARCH_BUTTON_TITLE' => '',
    'LBL_FILTER' => '',
    'LBL_SEARCH' => '',
    'LBL_SEARCH_ALT' => '',
    'LBL_SEARCH_MORE' => '',
    'LBL_UPLOAD_IMAGE_FILE_INVALID' => '',
    'LBL_SELECT_BUTTON_KEY' => '',
    'LBL_SELECT_BUTTON_LABEL' => '',
    'LBL_SELECT_BUTTON_TITLE' => '',
    'LBL_BROWSE_DOCUMENTS_BUTTON_LABEL' => '',
    'LBL_BROWSE_DOCUMENTS_BUTTON_TITLE' => '',
    'LBL_SELECT_CONTACT_BUTTON_KEY' => '',
    'LBL_SELECT_CONTACT_BUTTON_LABEL' => '',
    'LBL_SELECT_CONTACT_BUTTON_TITLE' => '',
    'LBL_SELECT_REPORTS_BUTTON_LABEL' => '',
    'LBL_SELECT_REPORTS_BUTTON_TITLE' => '',
    'LBL_SELECT_USER_BUTTON_KEY' => '',
    'LBL_SELECT_USER_BUTTON_LABEL' => '',
    'LBL_SELECT_USER_BUTTON_TITLE' => '',
    // Clear buttons take up too many keys, lets default the relate and collection ones to be empty
    'LBL_ACCESSKEY_CLEAR_RELATE_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_RELATE_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_RELATE_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_COLLECTION_LABEL' => '',
    'LBL_ACCESSKEY_SELECT_FILE_KEY' => '',
    'LBL_ACCESSKEY_SELECT_FILE_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_FILE_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_FILE_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_FILE_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_FILE_LABEL' => '',

    'LBL_ACCESSKEY_SELECT_USERS_KEY' => '',
    'LBL_ACCESSKEY_SELECT_USERS_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_USERS_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_USERS_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_USERS_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_USERS_LABEL' => '',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_KEY' => '',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_ACCOUNTS_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_ACCOUNTS_LABEL' => '',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_KEY' => '',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_CAMPAIGNS_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_CAMPAIGNS_LABEL' => '',
    'LBL_ACCESSKEY_SELECT_CONTACTS_KEY' => '',
    'LBL_ACCESSKEY_SELECT_CONTACTS_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_CONTACTS_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_CONTACTS_LABEL' => '',
    'LBL_ACCESSKEY_SELECT_TEAMSET_KEY' => '',
    'LBL_ACCESSKEY_SELECT_TEAMSET_TITLE' => '',
    'LBL_ACCESSKEY_SELECT_TEAMSET_LABEL' => '',
    'LBL_ACCESSKEY_CLEAR_TEAMS_KEY' => '',
    'LBL_ACCESSKEY_CLEAR_TEAMS_TITLE' => '',
    'LBL_ACCESSKEY_CLEAR_TEAMS_LABEL' => '',
    'LBL_SERVER_RESPONSE_RESOURCES' => '',
    'LBL_SERVER_RESPONSE_TIME_SECONDS' => '',
    'LBL_SERVER_RESPONSE_TIME' => '',
    'LBL_SERVER_MEMORY_BYTES' => '',
    'LBL_SERVER_MEMORY_USAGE' => '',
    'LBL_SERVER_MEMORY_LOG_MESSAGE' => '',
    'LBL_SERVER_PEAK_MEMORY_USAGE' => '',
    'LBL_SHIPPING_ADDRESS' => '',
    'LBL_SHOW' => '',
    'LBL_STATE' => '', //Used for Case State, situation, condition
    'LBL_STATUS_UPDATED' => '',
    'LBL_STATUS' => '',
    'LBL_STREET' => '',
    'LBL_SUBJECT' => '',

    'LBL_INBOUNDEMAIL_ID' => '',

    'LBL_SCENARIO_SALES' => '',
    'LBL_SCENARIO_MARKETING' => '',
    'LBL_SCENARIO_FINANCE' => '',
    'LBL_SCENARIO_SERVICE' => '',
    'LBL_SCENARIO_PROJECT' => '',

    'LBL_SCENARIO_SALES_DESCRIPTION' => '',
    'LBL_SCENARIO_MAKETING_DESCRIPTION' => '',
    'LBL_SCENARIO_FINANCE_DESCRIPTION' => '',
    'LBL_SCENARIO_SERVICE_DESCRIPTION' => '',
    'LBL_SCENARIO_PROJECT_DESCRIPTION' => '',

    'LBL_SYNC' => '',
    'LBL_TABGROUP_ALL' => '',
    'LBL_TABGROUP_ACTIVITIES' => '',
    'LBL_TABGROUP_COLLABORATION' => '',
    'LBL_TABGROUP_MARKETING' => '',
    'LBL_TABGROUP_OTHER' => '',
    'LBL_TABGROUP_SALES' => '',
    'LBL_TABGROUP_SUPPORT' => '',
    'LBL_TASKS' => '',
    'LBL_THOUSANDS_SYMBOL' => '',
    'LBL_TRACK_EMAIL_BUTTON_LABEL' => '',
    'LBL_TRACK_EMAIL_BUTTON_TITLE' => '',
    'LBL_UNDELETE_BUTTON_LABEL' => '',
    'LBL_UNDELETE_BUTTON_TITLE' => '',
    'LBL_UNDELETE_BUTTON' => '',
    'LBL_UNDELETE' => '',
    'LBL_UNSYNC' => '',
    'LBL_UPDATE' => '',
    'LBL_USER_LIST' => '',
    'LBL_USERS' => '',
    'LBL_VERIFY_EMAIL_ADDRESS' => '',
    'LBL_VERIFY_PORTAL_NAME' => '',
    'LBL_VIEW_IMAGE' => '',

    'LNK_ABOUT' => '',
    'LNK_ADVANCED_FILTER' => '',
    'LNK_BASIC_FILTER' => '',
    'LBL_ADVANCED_SEARCH' => '',
    'LBL_QUICK_FILTER' => '',
    'LNK_SEARCH_NONFTS_VIEW_ALL' => '',
    'LNK_CLOSE' => '',
    'LBL_MODIFY_CURRENT_FILTER' => '',
    'LNK_SAVED_VIEWS' => '',
    'LNK_DELETE' => '',
    'LNK_EDIT' => '',
    'LNK_GET_LATEST' => '',
    'LNK_GET_LATEST_TOOLTIP' => '',
    'LNK_HELP' => '',
    'LNK_CREATE' => '',
    'LNK_LIST_END' => '',
    'LNK_LIST_NEXT' => '',
    'LNK_LIST_PREVIOUS' => '',
    'LNK_LIST_RETURN' => '',
    'LNK_LIST_START' => '',
    'LNK_LOAD_SIGNED' => '',
    'LNK_LOAD_SIGNED_TOOLTIP' => '',
    'LNK_PRINT' => '',
    'LNK_BACKTOTOP' => '',
    'LNK_REMOVE' => '',
    'LNK_RESUME' => '',
    'LNK_VIEW_CHANGE_LOG' => '',

    'NTC_CLICK_BACK' => '',
    'NTC_DATE_FORMAT' => '',
    'NTC_DELETE_CONFIRMATION_MULTIPLE' => '',
    'NTC_TEMPLATE_IS_USED' => '',
    'NTC_TEMPLATES_IS_USED' => '' . PHP_EOL,
    'NTC_DELETE_CONFIRMATION' => '',
    'NTC_DELETE_CONFIRMATION_NUM' => '',
    'NTC_UPDATE_CONFIRMATION_NUM' => '',
    'NTC_DELETE_SELECTED_RECORDS' => '',
    'NTC_LOGIN_MESSAGE' => '',
    'NTC_NO_ITEMS_DISPLAY' => '',
    'NTC_REMOVE_CONFIRMATION' => '',
    'NTC_REQUIRED' => '',
    'NTC_TIME_FORMAT' => '',
    'NTC_WELCOME' => '',
    'NTC_YEAR_FORMAT' => '',
    'WARN_UNSAVED_CHANGES' => '',
    'ERROR_NO_RECORD' => '',
    'WARN_BROWSER_VERSION_WARNING' => '',
    'WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING' => '',
    'ERROR_TYPE_NOT_VALID' => '',
    'ERROR_NO_BEAN' => '',
    'LBL_DUP_MERGE' => '',
    'LBL_MANAGE_SUBSCRIPTIONS' => '',
    'LBL_MANAGE_SUBSCRIPTIONS_FOR' => '',
    // Ajax status strings
    'LBL_LOADING' => '',
    'LBL_SEARCHING' => '',
    'LBL_SAVING_LAYOUT' => '',
    'LBL_SAVED_LAYOUT' => '',
    'LBL_SAVED' => '',
    'LBL_SAVING' => '',
    'LBL_DISPLAY_COLUMNS' => '',
    'LBL_HIDE_COLUMNS' => '',
    'LBL_SEARCH_CRITERIA' => '',
    'LBL_SAVED_VIEWS' => '',
    'LBL_PROCESSING_REQUEST' => '',
    'LBL_REQUEST_PROCESSED' => '',
    'LBL_AJAX_FAILURE' => '',
    'LBL_MERGE_DUPLICATES' => '',
    'LBL_SAVED_FILTER_SHORTCUT' => '',
    'LBL_SEARCH_POPULATE_ONLY' => '',
    'LBL_DETAILVIEW' => '',
    'LBL_LISTVIEW' => '',
    'LBL_EDITVIEW' => '',
    'LBL_BILLING_STREET' => '',
    'LBL_SHIPPING_STREET' => '',
    'LBL_SEARCHFORM' => '',
    'LBL_SAVED_SEARCH_ERROR' => '',
    'LBL_DISPLAY_LOG' => '',
    'ERROR_JS_ALERT_SYSTEM_CLASS' => '',
    'ERROR_JS_ALERT_TIMEOUT_TITLE' => '',
    'ERROR_JS_ALERT_TIMEOUT_MSG_1' => '',
    'ERROR_JS_ALERT_TIMEOUT_MSG_2' => '',
    'MSG_JS_ALERT_MTG_REMINDER_AGENDA' => "",
    'MSG_JS_ALERT_MTG_REMINDER_MEETING' => '',
    'MSG_JS_ALERT_MTG_REMINDER_CALL' => '',
    'MSG_JS_ALERT_MTG_REMINDER_TIME' => '',
    'MSG_JS_ALERT_MTG_REMINDER_LOC' => '',
    'MSG_JS_ALERT_MTG_REMINDER_DESC' => '',
    'MSG_JS_ALERT_MTG_REMINDER_STATUS' => '',
    'MSG_JS_ALERT_MTG_REMINDER_RELATED_TO' => '',
    'MSG_JS_ALERT_MTG_REMINDER_CALL_MSG' => "",
    'MSG_JS_ALERT_MTG_REMINDER_MEETING_MSG' => "",
    'MSG_JS_ALERT_MTG_REMINDER_NO_EVENT_NAME' => '',
    'MSG_JS_ALERT_MTG_REMINDER_NO_DESCRIPTION' => '',
    'MSG_JS_ALERT_MTG_REMINDER_NO_LOCATION' => '',
    'MSG_JS_ALERT_MTG_REMINDER_NO_START_DATE' => '',
    'MSG_LIST_VIEW_NO_RESULTS_BASIC' => '',
    'MSG_LIST_VIEW_NO_RESULTS_CHANGE_CRITERIA' => '',
    'MSG_LIST_VIEW_NO_RESULTS' => '',
    'MSG_LIST_VIEW_NO_RESULTS_SUBMSG' => '',
    'MSG_LIST_VIEW_CHANGE_SEARCH' => '',
    'MSG_EMPTY_LIST_VIEW_NO_RESULTS' => '',

    'LBL_CLICK_HERE' => '',
    // contextMenu strings
    'LBL_ADD_TO_FAVORITES' => '',
    'LBL_CREATE_CONTACT' => '',
    'LBL_CREATE_CASE' => '',
    'LBL_CREATE_NOTE' => '',
    'LBL_CREATE_OPPORTUNITY' => '',
    'LBL_SCHEDULE_CALL' => '',
    'LBL_SCHEDULE_MEETING' => '',
    'LBL_CREATE_TASK' => '',
    //web to lead
    'LBL_GENERATE_WEB_TO_LEAD_FORM' => '',
    'LBL_SAVE_WEB_TO_LEAD_FORM' => '',
    'LBL_AVAILABLE_FIELDS' => '',
    'LBL_FIRST_FORM_COLUMN' => '',
    'LBL_SECOND_FORM_COLUMN' => '',
    'LBL_ASSIGNED_TO_REQUIRED' => '',
    'LBL_RELATED_CAMPAIGN_REQUIRED' => '',
    'LBL_TYPE_OF_PERSON_FOR_FORM' => '',
    'LBL_TYPE_OF_PERSON_FOR_FORM_DESC' => '',

    'LBL_ADD_ALL_LEAD_FIELDS' => '',
    'LBL_RESET_ALL_LEAD_FIELDS' => '',
    'LBL_REMOVE_ALL_LEAD_FIELDS' => '',
    'LBL_NEXT_BTN' => '',
    'LBL_ONLY_IMAGE_ATTACHMENT' => '',
    'LBL_TRAINING' => '',
    'ERR_MSSQL_DB_CONTEXT' => '',
    'ERR_MSSQL_WARNING' => '',

    //Meta-Data framework
    'ERR_CANNOT_CREATE_METADATA_FILE' => '',
    'ERR_CANNOT_FIND_MODULE' => '',
    'LBL_ALT_ADDRESS' => '',
    'ERR_SMARTY_UNEQUAL_RELATED_FIELD_PARAMETERS' => '',

    /* MySugar Framework (for Home and Dashboard) */
    'LBL_DASHLET_CONFIGURE_GENERAL' => '',
    'LBL_DASHLET_CONFIGURE_FILTERS' => '',
    'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY' => '',
    'LBL_DASHLET_CONFIGURE_TITLE' => '',
    'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS' => '',

    // MySugar status strings
    'LBL_MAX_DASHLETS_REACHED' => '',
    'LBL_ADDING_DASHLET' => '',
    'LBL_ADDED_DASHLET' => '',
    'LBL_REMOVE_DASHLET_CONFIRM' => '',
    'LBL_REMOVING_DASHLET' => '',
    'LBL_REMOVED_DASHLET' => '',
    'LBL_MAX_DASHLET_COLUMNS' => "",
    'LBL_RETRIEVING_XML_DATA' => "",

    // MySugar Menu Options

    'LBL_LOADING_PAGE' => '',

    'LBL_RELOAD_PAGE' => '',
    'LBL_ADD_DASHLETS' => '',
    'LBL_CLOSE_DASHLETS' => '',
    'LBL_OPTIONS' => '',
    'LBL_1_COLUMN' => '',
    'LBL_2_COLUMN' => '',
    'LBL_3_COLUMN' => '',
    'LBL_PAGE_NAME' => '',

    'LBL_SEARCH_RESULTS' => '',
    'LBL_SEARCH_MODULES' => '',
    'LBL_SEARCH_TOOLS' => '',
    'LBL_SEARCH_HELP_TITLE' => '',
    /* End MySugar Framework strings */

    'LBL_NO_IMAGE' => '',

    //adding a label for address copy from left
    'LBL_COPY_ADDRESS_FROM_LEFT' => '',
    'LBL_SAVE_AND_CONTINUE' => '',

    'LBL_SEARCH_HELP_TEXT' => '',

    //resource management
    'ERR_QUERY_LIMIT' => '',
    'ERROR_NOTIFY_OVERRIDE' => '',

    //tracker labels
    'ERR_MONITOR_FILE_MISSING' => '',
    'ERR_MONITOR_NOT_CONFIGURED' => '',
    'ERR_UNDEFINED_METRIC' => '',
    'ERR_STORE_FILE_MISSING' => '',

    'LBL_MONITOR_ID' => '',
    'LBL_USER_ID' => '',
    'LBL_MODULE' => '',
    'LBL_ITEM_ID' => '',
    'LBL_ITEM_SUMMARY' => '',
    'LBL_ACTION' => '',
    'LBL_SESSION_ID' => '',
    'LBL_BREADCRUMBSTACK_CREATED' => '',
    'LBL_VISIBLE' => '',
    'LBL_DATE_LAST_ACTION' => '',
    
    //jc:#12287 - For javascript validation messages
    'MSG_IS_NOT_BEFORE' => '',
    'MSG_IS_MORE_THAN' => '',
    'MSG_IS_LESS_THAN' => '',
    'MSG_SHOULD_BE' => '',
    'MSG_OR_GREATER' => '',

    'LBL_LIST' => '',
    'LBL_CREATE_BUG' => '',

    'LBL_OBJECT_IMAGE' => '',
    //jchi #12300
    'LBL_MASSUPDATE_DATE' => '',

    'LBL_VALUE' => '',
    'LBL_VALIDATE_RANGE' => '',
    'LBL_CHOOSE_START_AND_END_DATES' => '',
    'LBL_CHOOSE_START_AND_END_ENTRIES' => '',

    //jchi #  20776
    'LBL_DROPDOWN_LIST_ALL' => '',

    //Connector
    'ERR_CONNECTOR_FILL_BEANS_SIZE_MISMATCH' => '',
    'ERR_MISSING_MAPPING_ENTRY_FORM_MODULE' => '',
    'ERROR_UNABLE_TO_RETRIEVE_DATA' => '',

    // fastcgi checks
    'LBL_FASTCGI_LOGGING' => '',

    //Collection Field
    'LBL_COLLECTION_NAME' => '',
    'LBL_COLLECTION_PRIMARY' => '',
    'ERROR_MISSING_COLLECTION_SELECTION' => '',

    //MB -Fixed Bug #32812 -Max
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_DESCRIPTION' => '',

    'LBL_YESTERDAY' => '',
    'LBL_NOW' => '',
    'LBL_TODAY' => '',
    'LBL_TOMORROW' => '',
    'LBL_NEXT_WEEK' => '',
    'LBL_NEXT_MONDAY' => '',
    'LBL_NEXT_FRIDAY' => '',
    'LBL_TWO_WEEKS' => '',
    'LBL_NEXT_MONTH' => '',
    'LBL_FIRST_DAY_OF_NEXT_MONTH' => '',
    'LBL_THREE_MONTHS' => '',
    'LBL_SIXMONTHS' => '',
    'LBL_NEXT_YEAR' => '',

    //Datetimecombo fields
    'LBL_HOURS' => '',
    'LBL_MINUTES' => '',
    'LBL_MERIDIEM' => '',
    'LBL_DATE' => '',
    'LBL_DASHLET_CONFIGURE_AUTOREFRESH' => '',

    'LBL_DURATION_DAY' => '',
    'LBL_DURATION_HOUR' => '',
    'LBL_DURATION_MINUTE' => '',
    'LBL_DURATION_DAYS' => '',
    'LBL_DURATION_HOURS' => '',
    'LBL_DURATION_MINUTES' => '',

    //Calendar widget labels
    'LBL_CHOOSE_MONTH' => '',
    'LBL_ENTER_YEAR' => '',
    'LBL_ENTER_VALID_YEAR' => '',

    //File write error label
    'ERR_FILE_WRITE' => '',
    'ERR_FILE_NOT_FOUND' => '',

    'LBL_AND' => '',

    // File fields
    'LBL_SEARCH_EXTERNAL_API' => '',
    'LBL_EXTERNAL_SECURITY_LEVEL' => '',

    //IMPORT SAMPLE TEXT
    'LBL_IMPORT_SAMPLE_FILE_TEXT' => '',
    //define labels to be used for overriding local values during import/export

    'LBL_NOTIFICATIONS_NONE' => '',
    'LBL_ALT_SORT_DESC' => '',
    'LBL_ALT_SORT_ASC' => '',
    'LBL_ALT_SORT' => '',
    'LBL_ALT_SHOW_OPTIONS' => '',
    'LBL_ALT_HIDE_OPTIONS' => '',
    'LBL_ALT_MOVE_COLUMN_LEFT' => '',
    'LBL_ALT_MOVE_COLUMN_RIGHT' => '',
    'LBL_ALT_MOVE_COLUMN_UP' => '',
    'LBL_ALT_MOVE_COLUMN_DOWN' => '',
    'LBL_ALT_INFO' => '',
    'MSG_DUPLICATE' => '',
    'MSG_SHOW_DUPLICATES' => '',
    'LBL_EMAIL_TITLE' => '',
    'LBL_EMAIL_OPT_TITLE' => '',
    'LBL_EMAIL_INV_TITLE' => '',
    'LBL_EMAIL_PRIM_TITLE' => '',
    'LBL_SELECT_ALL_TITLE' => '',
    'LBL_SELECT_THIS_ROW_TITLE' => '',

    //for upload errors
    'UPLOAD_ERROR_TEXT' => '',
    'UPLOAD_ERROR_TEXT_SIZEINFO' => '',
    'UPLOAD_ERROR_HOME_TEXT' => '',
    'UPLOAD_MAXIMUM_EXCEEDED' => '',
    'UPLOAD_REQUEST_ERROR' => '',

    //508 used Access Keys
    'LBL_EDIT_BUTTON_KEY' => '',
    'LBL_EDIT_BUTTON_LABEL' => '',
    'LBL_EDIT_BUTTON_TITLE' => '',
    'LBL_DUPLICATE_BUTTON_KEY' => '',
    'LBL_DUPLICATE_BUTTON_LABEL' => '',
    'LBL_DUPLICATE_BUTTON_TITLE' => '',
    'LBL_DELETE_BUTTON_KEY' => '',
    'LBL_DELETE_BUTTON_LABEL' => '',
    'LBL_DELETE_BUTTON_TITLE' => '',
    'LBL_BULK_ACTION_BUTTON_LABEL' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_BULK_ACTION_BUTTON_LABEL_MOBILE' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_SAVE_BUTTON_KEY' => '',
    'LBL_SAVE_BUTTON_LABEL' => '',
    'LBL_SAVE_BUTTON_TITLE' => '',
    'LBL_CANCEL_BUTTON_KEY' => '',
    'LBL_CANCEL_BUTTON_LABEL' => '',
    'LBL_CANCEL_BUTTON_TITLE' => '',
    'LBL_FIRST_INPUT_EDIT_VIEW_KEY' => '',
    'LBL_ADV_SEARCH_LNK_KEY' => '',
    'LBL_FIRST_INPUT_SEARCH_KEY' => '',

    'ERR_CONNECTOR_NOT_ARRAY' => '',
    'ERR_SUHOSIN' => '',
    'ERR_BAD_RESPONSE_FROM_SERVER' => '',
    'LBL_ACCOUNT_PRODUCT_QUOTE_LINK' => '',
    'LBL_ACCOUNT_PRODUCT_SALE_PRICE' => '',
    'LBL_EMAIL_CHECK_INTERVAL_DOM' => array(
        '-1' => '',
        '5' => '',
        '15' => '',
        '30' => '',
        '60' => '',
    ),

    'ERR_A_REMINDER_IS_EMPTY_OR_INCORRECT' => '',
    'ERR_REMINDER_IS_NOT_SET_POPUP_OR_EMAIL' => '',
    'ERR_NO_INVITEES_FOR_REMINDER' => '',
    'LBL_DELETE_REMINDER_CONFIRM' => '',
    'LBL_DELETE_REMINDER' => '',
    'LBL_OK' => '',

    'LBL_COLUMNS_FILTER_HEADER_TITLE' => '',
    'LBL_COLUMN_CHOOSER' => '',
    'LBL_SAVE_CHANGES_BUTTON_TITLE' => '',
    'LBL_DISPLAYED' => '',
    'LBL_HIDDEN' => '',
    'ERR_EMPTY_COLUMNS_LIST' => '',

    'LBL_FILTER_HEADER_TITLE' => '',

    'LBL_CATEGORY' => '',
    'LBL_LIST_CATEGORY' => '',
    'ERR_FACTOR_TPL_INVALID' => '',
    'LBL_SUBTHEMES' => '',
    'LBL_SUBTHEME_OPTIONS_DAWN' => '',
    'LBL_SUBTHEME_OPTIONS_DAY' => '',
    'LBL_SUBTHEME_OPTIONS_DUSK' => '',
    'LBL_SUBTHEME_OPTIONS_NIGHT' => '',
    'LBL_SUBTHEME_OPTIONS_NOON' => '', 

    'LBL_CONFIRM_DISREGARD_DRAFT_TITLE' => '',
    'LBL_CONFIRM_DISREGARD_DRAFT_BODY' => '',
    'LBL_CONFIRM_DISREGARD_EMAIL_TITLE' => '',
    'LBL_CONFIRM_DISREGARD_EMAIL_BODY' => '',
    'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE' => '',
    'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_BODY' => '',

    'LBL_CONFIRM_OPT_IN_TITLE' => '',
    'LBL_OPT_IN_TITLE' => '',
    'LBL_CONFIRM_OPT_IN_DATE' => '',
    'LBL_CONFIRM_OPT_IN_SENT_DATE' => '',
    'LBL_CONFIRM_OPT_IN_FAIL_DATE' => '',
    'LBL_CONFIRM_OPT_IN_TOKEN' => '',
    'ERR_OPT_IN_TPL_NOT_SET' => '',
    'ERR_OPT_IN_RELATION_INCORRECT' => '',

    'LBL_SECURITYGROUP_NONINHERITABLE' => '',
    'LBL_PRIMARY_GROUP' => "",

    // footer
    'LBL_SUITE_TOP' => '',
    'LBL_SUITE_SUPERCHARGED' => '',
    'LBL_SUITE_POWERED_BY' => '',
    'LBL_SUITE_DESC1' => '',
    'LBL_SUITE_DESC2' => '',
    'LBL_SUITE_DESC3' => '',
    'LBL_GENERATE_PASSWORD_BUTTON_TITLE' => '',
    'LBL_SEND_CONFIRM_OPT_IN_EMAIL' => '',
    'LBL_CONFIRM_OPT_IN_ONLY_FOR_PERSON' => '',
    'LBL_CONFIRM_OPT_IN_IS_DISABLED' => '',
    'LBL_CONTACT_HAS_NO_PRIMARY_EMAIL' => '',
    'LBL_CONFIRM_EMAIL_SENDING_FAILED' => '',
    'LBL_CONFIRM_EMAIL_SENT' => '',
);

$app_list_strings['moduleList']['Library'] = '';
$app_list_strings['moduleList']['EmailAddresses'] = '';
$app_list_strings['project_priority_default'] = '';
$app_list_strings['project_priority_options'] = array(
    'High' => '',
    'Medium' => '',
    'Low' => '',
);

//GDPR lawful basis options
$app_list_strings['lawful_basis_dom'] = array(
    '' => '',
    'consent' => '',
    'contract' => '',
    'legal_obligation' => '',
    'protection_of_interest' => '',
    'public_interest' => '',
    'legitimate_interest' => '',
    'withdrawn' => '',
);
//End GDPR lawful basis options

//GDPR lawful basis source options
$app_list_strings['lawful_basis_source_dom'] = array(
    '' => '',
    'website' => '',
    'phone' => '',
    'given_to_user' => '',
    'email' => '',
    'third_party' => '',
);
//End GDPR lawful basis source options

$app_list_strings['moduleList']['KBDocuments'] = '';

$app_list_strings['countries_dom'] = array(
    '' => '',
    'ABU DHABI' => '',
    'ADEN' => '',
    'AFGHANISTAN' => '',
    'ALBANIA' => '',
    'ALGERIA' => '',
    'AMERICAN SAMOA' => '',
    'ANDORRA' => '',
    'ANGOLA' => '',
    'ANTARCTICA' => '',
    'ANTIGUA' => '',
    'ARGENTINA' => '',
    'ARMENIA' => '',
    'ARUBA' => '',
    'AUSTRALIA' => '',
    'AUSTRIA' => '',
    'AZERBAIJAN' => '',
    'BAHAMAS' => '',
    'BAHRAIN' => '',
    'BANGLADESH' => '',
    'BARBADOS' => '',
    'BELARUS' => '',
    'BELGIUM' => '',
    'BELIZE' => '',
    'BENIN' => '',
    'BERMUDA' => '',
    'BHUTAN' => '',
    'BOLIVIA' => '',
    'BOSNIA' => '',
    'BOTSWANA' => '',
    'BOUVET ISLAND' => '',
    'BRAZIL' => '',
    'BRITISH ANTARCTICA TERRITORY' => '',
    'BRITISH INDIAN OCEAN TERRITORY' => '',
    'BRITISH VIRGIN ISLANDS' => '',
    'BRITISH WEST INDIES' => '',
    'BRUNEI' => '',
    'BULGARIA' => '',
    'BURKINA FASO' => '',
    'BURUNDI' => '',
    'CAMBODIA' => '',
    'CAMEROON' => '',
    'CANADA' => '',
    'CANAL ZONE' => '',
    'CANARY ISLAND' => '',
    'CAPE VERDI ISLANDS' => '',
    'CAYMAN ISLANDS' => '',
    'CHAD' => '',
    'CHANNEL ISLAND UK' => '',
    'CHILE' => '',
    'CHINA' => '',
    'CHRISTMAS ISLAND' => '',
    'COCOS (KEELING) ISLAND' => '',
    'COLOMBIA' => '',
    'COMORO ISLANDS' => '',
    'CONGO' => '',
    'CONGO KINSHASA' => '',
    'COOK ISLANDS' => '',
    'COSTA RICA' => '',
    'CROATIA' => '',
    'CUBA' => '',
    'CURACAO' => '',
    'CYPRUS' => '',
    'CZECH REPUBLIC' => '',
    'DAHOMEY' => '',
    'DENMARK' => '',
    'DJIBOUTI' => '',
    'DOMINICA' => '',
    'DOMINICAN REPUBLIC' => '',
    'DUBAI' => '',
    'ECUADOR' => '',
    'EGYPT' => '',
    'EL SALVADOR' => '',
    'EQUATORIAL GUINEA' => '',
    'ESTONIA' => '',
    'ETHIOPIA' => '',
    'FAEROE ISLANDS' => '',
    'FALKLAND ISLANDS' => '',
    'FIJI' => '',
    'FINLAND' => '',
    'FRANCE' => '',
    'FRENCH GUIANA' => '',
    'FRENCH POLYNESIA' => '',
    'GABON' => '',
    'GAMBIA' => '',
    'GEORGIA' => '',
    'GERMANY' => '',
    'GHANA' => '',
    'GIBRALTAR' => '',
    'GREECE' => '',
    'GREENLAND' => '',
    'GUADELOUPE' => '',
    'GUAM' => '',
    'GUATEMALA' => '',
    'GUINEA' => '',
    'GUYANA' => '',
    'HAITI' => '',
    'HONDURAS' => '',
    'HONG KONG' => '',
    'HUNGARY' => '',
    'ICELAND' => '',
    'IFNI' => '',
    'INDIA' => '',
    'INDONESIA' => '',
    'IRAN' => '',
    'IRAQ' => '',
    'IRELAND' => '',
    'ISRAEL' => '',
    'ITALY' => '',
    'IVORY COAST' => '',
    'JAMAICA' => '',
    'JAPAN' => '',
    'JORDAN' => '',
    'KAZAKHSTAN' => '',
    'KENYA' => '',
    'KOREA' => '',
    'KOREA, SOUTH' => '',
    'KUWAIT' => '',
    'KYRGYZSTAN' => '',
    'LAOS' => '',
    'LATVIA' => '',
    'LEBANON' => '',
    'LEEWARD ISLANDS' => '',
    'LESOTHO' => '',
    'LIBYA' => '',
    'LIECHTENSTEIN' => '',
    'LITHUANIA' => '',
    'LUXEMBOURG' => '',
    'MACAO' => '',
    'MACEDONIA' => '',
    'MADAGASCAR' => '',
    'MALAWI' => '',
    'MALAYSIA' => '',
    'MALDIVES' => '',
    'MALI' => '',
    'MALTA' => '',
    'MARTINIQUE' => '',
    'MAURITANIA' => '',
    'MAURITIUS' => '',
    'MELANESIA' => '',
    'MEXICO' => '',
    'MOLDOVIA' => '',
    'MONACO' => '',
    'MONGOLIA' => '',
    'MOROCCO' => '',
    'MOZAMBIQUE' => '',
    'MYANAMAR' => '',
    'NAMIBIA' => '',
    'NEPAL' => '',
    'NETHERLANDS' => '',
    'NETHERLANDS ANTILLES' => '',
    'NETHERLANDS ANTILLES NEUTRAL ZONE' => '',
    'NEW CALADONIA' => '',
    'NEW HEBRIDES' => '',
    'NEW ZEALAND' => '',
    'NICARAGUA' => '',
    'NIGER' => '',
    'NIGERIA' => '',
    'NORFOLK ISLAND' => '',
    'NORWAY' => '',
    'OMAN' => '',
    'OTHER' => '',
    'PACIFIC ISLAND' => '',
    'PAKISTAN' => '',
    'PANAMA' => '',
    'PAPUA NEW GUINEA' => '',
    'PARAGUAY' => '',
    'PERU' => '',
    'PHILIPPINES' => '',
    'POLAND' => '',
    'PORTUGAL' => '',
    'PORTUGUESE TIMOR' => '',
    'PUERTO RICO' => '',
    'QATAR' => '',
    'REPUBLIC OF BELARUS' => '',
    'REPUBLIC OF SOUTH AFRICA' => '',
    'REUNION' => '',
    'ROMANIA' => '',
    'RUSSIA' => '',
    'RWANDA' => '',
    'RYUKYU ISLANDS' => '',
    'SABAH' => '',
    'SAN MARINO' => '',
    'SAUDI ARABIA' => '',
    'SENEGAL' => '',
    'SERBIA' => '',
    'SEYCHELLES' => '',
    'SIERRA LEONE' => '',
    'SINGAPORE' => '',
    'SLOVAKIA' => '',
    'SLOVENIA' => '',
    'SOMALILIAND' => '',
    'SOUTH AFRICA' => '',
    'SOUTH YEMEN' => '',
    'SPAIN' => '',
    'SPANISH SAHARA' => '',
    'SRI LANKA' => '',
    'ST. KITTS AND NEVIS' => '',
    'ST. LUCIA' => '',
    'SUDAN' => '',
    'SURINAM' => '',
    'SW AFRICA' => '',
    'SWAZILAND' => '',
    'SWEDEN' => '',
    'SWITZERLAND' => '',
    'SYRIA' => '',
    'TAIWAN' => '',
    'TAJIKISTAN' => '',
    'TANZANIA' => '',
    'THAILAND' => '',
    'TONGA' => '',
    'TRINIDAD' => '',
    'TUNISIA' => '',
    'TURKEY' => '',
    'UGANDA' => '',
    'UKRAINE' => '',
    'UNITED ARAB EMIRATES' => '',
    'UNITED KINGDOM' => '',
    'URUGUAY' => '',
    'US PACIFIC ISLAND' => '',
    'US VIRGIN ISLANDS' => '',
    'USA' => '',
    'UZBEKISTAN' => '',
    'VANUATU' => '',
    'VATICAN CITY' => '',
    'VENEZUELA' => '',
    'VIETNAM' => '',
    'WAKE ISLAND' => '',
    'WEST INDIES' => '',
    'WESTERN SAHARA' => '',
    'YEMEN' => '',
    'ZAIRE' => '',
    'ZAMBIA' => '',
    'ZIMBABWE' => '',
);

$app_list_strings['charset_dom'] = array(
    'BIG-5' => '',
    /*'CP866'     => 'CP866', // ms-dos Cyrillic */
    /*'CP949'     => 'CP949 (Microsoft Korean)', */
    'CP1251' => '',
    'CP1252' => '',
    'EUC-CN' => '',
    'EUC-JP' => '',
    'EUC-KR' => '',
    'EUC-TW' => '',
    'ISO-2022-JP' => '',
    'ISO-2022-KR' => '',
    'ISO-8859-1' => '',
    'ISO-8859-2' => '',
    'ISO-8859-3' => '',
    'ISO-8859-4' => '',
    'ISO-8859-5' => '',
    'ISO-8859-6' => '',
    'ISO-8859-7' => '',
    'ISO-8859-8' => '',
    'ISO-8859-9' => '',
    'ISO-8859-10' => '',
    'ISO-8859-13' => '',
    'ISO-8859-14' => '',
    'ISO-8859-15' => '',
    'KOI8-R' => '',
    'KOI8-U' => '',
    'SJIS' => '',
    'UTF-8' => '',
);

$app_list_strings['timezone_dom'] = array(

    'Africa/Algiers' => '',
    'Africa/Luanda' => '',
    'Africa/Porto-Novo' => '',
    'Africa/Gaborone' => '',
    'Africa/Ouagadougou' => '',
    'Africa/Bujumbura' => '',
    'Africa/Douala' => '',
    'Atlantic/Cape_Verde' => '',
    'Africa/Bangui' => '',
    'Africa/Ndjamena' => '',
    'Indian/Comoro' => '',
    'Africa/Kinshasa' => '',
    'Africa/Lubumbashi' => '',
    'Africa/Brazzaville' => '',
    'Africa/Abidjan' => '',
    'Africa/Djibouti' => '',
    'Africa/Cairo' => '',
    'Africa/Malabo' => '',
    'Africa/Asmera' => '',
    'Africa/Addis_Ababa' => '',
    'Africa/Libreville' => '',
    'Africa/Banjul' => '',
    'Africa/Accra' => '',
    'Africa/Conakry' => '',
    'Africa/Bissau' => '',
    'Africa/Nairobi' => '',
    'Africa/Maseru' => '',
    'Africa/Monrovia' => '',
    'Africa/Tripoli' => '',
    'Indian/Antananarivo' => '',
    'Africa/Blantyre' => '',
    'Africa/Bamako' => '',
    'Africa/Nouakchott' => '',
    'Indian/Mauritius' => '',
    'Indian/Mayotte' => '',
    'Africa/Casablanca' => '',
    'Africa/El_Aaiun' => '',
    'Africa/Maputo' => '',
    'Africa/Windhoek' => '',
    'Africa/Niamey' => '',
    'Africa/Lagos' => '',
    'Indian/Reunion' => '',
    'Africa/Kigali' => '',
    'Atlantic/St_Helena' => '',
    'Africa/Sao_Tome' => '',
    'Africa/Dakar' => '',
    'Indian/Mahe' => '',
    'Africa/Freetown' => '',
    'Africa/Mogadishu' => '',
    'Africa/Johannesburg' => '',
    'Africa/Khartoum' => '',
    'Africa/Mbabane' => '',
    'Africa/Dar_es_Salaam' => '',
    'Africa/Lome' => '',
    'Africa/Tunis' => '',
    'Africa/Kampala' => '',
    'Africa/Lusaka' => '',
    'Africa/Harare' => '',
    'Antarctica/Casey' => '',
    'Antarctica/Davis' => '',
    'Antarctica/Mawson' => '',
    'Indian/Kerguelen' => '',
    'Antarctica/DumontDUrville' => '',
    'Antarctica/Syowa' => '',
    'Antarctica/Vostok' => '',
    'Antarctica/Rothera' => '',
    'Antarctica/Palmer' => '',
    'Antarctica/McMurdo' => '',
    'Asia/Kabul' => '',
    'Asia/Yerevan' => '',
    'Asia/Baku' => '',
    'Asia/Bahrain' => '',
    'Asia/Dhaka' => '',
    'Asia/Thimphu' => '',
    'Indian/Chagos' => '',
    'Asia/Brunei' => '',
    'Asia/Rangoon' => '',
    'Asia/Phnom_Penh' => '',
    'Asia/Beijing' => '',
    'Asia/Harbin' => '',
    'Asia/Shanghai' => '',
    'Asia/Chongqing' => '',
    'Asia/Urumqi' => '',
    'Asia/Kashgar' => '',
    'Asia/Hong_Kong' => '',
    'Asia/Taipei' => '',
    'Asia/Macau' => '',
    'Asia/Nicosia' => '',
    'Asia/Tbilisi' => '',
    'Asia/Dili' => '',
    'Asia/Calcutta' => '',
    'Asia/Jakarta' => '',
    'Asia/Pontianak' => '',
    'Asia/Makassar' => '',
    'Asia/Jayapura' => '',
    'Asia/Tehran' => '',
    'Asia/Baghdad' => '',
    'Asia/Jerusalem' => '',
    'Asia/Tokyo' => '',
    'Asia/Amman' => '',
    'Asia/Almaty' => '',
    'Asia/Qyzylorda' => '',
    'Asia/Aqtobe' => '',
    'Asia/Aqtau' => '',
    'Asia/Oral' => '',
    'Asia/Bishkek' => '',
    'Asia/Seoul' => '',
    'Asia/Pyongyang' => '',
    'Asia/Kuwait' => '',
    'Asia/Vientiane' => '',
    'Asia/Beirut' => '',
    'Asia/Kuala_Lumpur' => '',
    'Asia/Kuching' => '',
    'Indian/Maldives' => '',
    'Asia/Hovd' => '',
    'Asia/Ulaanbaatar' => '',
    'Asia/Choibalsan' => '',
    'Asia/Katmandu' => '',
    'Asia/Muscat' => '',
    'Asia/Karachi' => '',
    'Asia/Gaza' => '',
    'Asia/Manila' => '',
    'Asia/Qatar' => '',
    'Asia/Riyadh' => '',
    'Asia/Singapore' => '',
    'Asia/Colombo' => '',
    'Asia/Damascus' => '',
    'Asia/Dushanbe' => '',
    'Asia/Bangkok' => '',
    'Asia/Ashgabat' => '',
    'Asia/Dubai' => '',
    'Asia/Samarkand' => '',
    'Asia/Tashkent' => '',
    'Asia/Saigon' => '',
    'Asia/Aden' => '',
    'Australia/Darwin' => '',
    'Australia/Perth' => '',
    'Australia/Brisbane' => '',
    'Australia/Lindeman' => '',
    'Australia/Adelaide' => '',
    'Australia/Hobart' => '',
    'Australia/Currie' => '',
    'Australia/Melbourne' => '',
    'Australia/Sydney' => '',
    'Australia/Broken_Hill' => '',
    'Indian/Christmas' => '',
    'Pacific/Rarotonga' => '',
    'Indian/Cocos' => '',
    'Pacific/Fiji' => '',
    'Pacific/Gambier' => '',
    'Pacific/Marquesas' => '',
    'Pacific/Tahiti' => '',
    'Pacific/Guam' => '',
    'Pacific/Tarawa' => '',
    'Pacific/Enderbury' => '',
    'Pacific/Kiritimati' => '',
    'Pacific/Saipan' => '',
    'Pacific/Majuro' => '',
    'Pacific/Kwajalein' => '',
    'Pacific/Truk' => '',
    'Pacific/Pohnpei' => '',
    'Pacific/Kosrae' => '',
    'Pacific/Nauru' => '',
    'Pacific/Noumea' => '',
    'Pacific/Auckland' => '',
    'Pacific/Chatham' => '',
    'Pacific/Niue' => '',
    'Pacific/Norfolk' => '',
    'Pacific/Palau' => '',
    'Pacific/Port_Moresby' => '',
    'Pacific/Pitcairn' => '',
    'Pacific/Pago_Pago' => '',
    'Pacific/Apia' => '',
    'Pacific/Guadalcanal' => '',
    'Pacific/Fakaofo' => '',
    'Pacific/Tongatapu' => '',
    'Pacific/Funafuti' => '',
    'Pacific/Johnston' => '',
    'Pacific/Midway' => '',
    'Pacific/Wake' => '',
    'Pacific/Efate' => '',
    'Pacific/Wallis' => '',
    'Europe/London' => '',
    'Europe/Dublin' => '',
    'WET' => '',
    'CET' => '',
    'MET' => '',
    'EET' => '',
    'Europe/Tirane' => '',
    'Europe/Andorra' => '',
    'Europe/Vienna' => '',
    'Europe/Minsk' => '',
    'Europe/Brussels' => '',
    'Europe/Sofia' => '',
    'Europe/Prague' => '',
    'Europe/Copenhagen' => '',
    'Atlantic/Faeroe' => '',
    'America/Danmarkshavn' => '',
    'America/Scoresbysund' => '',
    'America/Godthab' => '',
    'America/Thule' => '',
    'Europe/Tallinn' => '',
    'Europe/Helsinki' => '',
    'Europe/Paris' => '',
    'Europe/Berlin' => '',
    'Europe/Gibraltar' => '',
    'Europe/Athens' => '',
    'Europe/Budapest' => '',
    'Atlantic/Reykjavik' => '',
    'Europe/Rome' => '',
    'Europe/Riga' => '',
    'Europe/Vaduz' => '',
    'Europe/Vilnius' => '',
    'Europe/Luxembourg' => '',
    'Europe/Malta' => '',
    'Europe/Chisinau' => '',
    'Europe/Monaco' => '',
    'Europe/Amsterdam' => '',
    'Europe/Oslo' => '',
    'Europe/Warsaw' => '',
    'Europe/Lisbon' => '',
    'Atlantic/Azores' => '',
    'Atlantic/Madeira' => '',
    'Europe/Bucharest' => '',
    'Europe/Kaliningrad' => '',
    'Europe/Moscow' => '',
    'Europe/Samara' => '',
    'Asia/Yekaterinburg' => '',
    'Asia/Omsk' => '',
    'Asia/Novosibirsk' => '',
    'Asia/Krasnoyarsk' => '',
    'Asia/Irkutsk' => '',
    'Asia/Yakutsk' => '',
    'Asia/Vladivostok' => '',
    'Asia/Sakhalin' => '',
    'Asia/Magadan' => '',
    'Asia/Kamchatka' => '',
    'Asia/Anadyr' => '',
    'Europe/Belgrade' => '',
    'Europe/Madrid' => '',
    'Africa/Ceuta' => '',
    'Atlantic/Canary' => '',
    'Europe/Stockholm' => '',
    'Europe/Zurich' => '',
    'Europe/Istanbul' => '',
    'Europe/Kiev' => '',
    'Europe/Uzhgorod' => '',
    'Europe/Zaporozhye' => '',
    'Europe/Simferopol' => '',
    'America/New_York' => '',
    'America/Chicago' => '',
    'America/North_Dakota/Center' => '',
    'America/Denver' => '',
    'America/Los_Angeles' => '',
    'America/Juneau' => '',
    'America/Yakutat' => '',
    'America/Anchorage' => '',
    'America/Nome' => '',
    'America/Adak' => '',
    'Pacific/Honolulu' => '',
    'America/Phoenix' => '',
    'America/Boise' => '',
    'America/Indiana/Indianapolis' => '',
    'America/Indiana/Marengo' => '',
    'America/Indiana/Knox' => '',
    'America/Indiana/Vevay' => '',
    'America/Kentucky/Louisville' => '',
    'America/Kentucky/Monticello' => '',
    'America/Detroit' => '',
    'America/Menominee' => '',
    'America/St_Johns' => '',
    'America/Goose_Bay' => '',
    'America/Halifax' => '',
    'America/Glace_Bay' => '',
    'America/Montreal' => '',
    'America/Toronto' => '',
    'America/Thunder_Bay' => '',
    'America/Nipigon' => '',
    'America/Rainy_River' => '',
    'America/Winnipeg' => '',
    'America/Regina' => '',
    'America/Swift_Current' => '',
    'America/Edmonton' => '',
    'America/Vancouver' => '',
    'America/Dawson_Creek' => '',
    'America/Pangnirtung' => '',
    'America/Iqaluit' => '',
    'America/Coral_Harbour' => '',
    'America/Rankin_Inlet' => '',
    'America/Cambridge_Bay' => '',
    'America/Yellowknife' => '',
    'America/Inuvik' => '',
    'America/Whitehorse' => '',
    'America/Dawson' => '',
    'America/Cancun' => '',
    'America/Merida' => '',
    'America/Monterrey' => '',
    'America/Mexico_City' => '',
    'America/Chihuahua' => '',
    'America/Hermosillo' => '',
    'America/Mazatlan' => '',
    'America/Tijuana' => '',
    'America/Anguilla' => '',
    'America/Antigua' => '',
    'America/Nassau' => '',
    'America/Barbados' => '',
    'America/Belize' => '',
    'Atlantic/Bermuda' => '',
    'America/Cayman' => '',
    'America/Costa_Rica' => '',
    'America/Havana' => '',
    'America/Dominica' => '',
    'America/Santo_Domingo' => '',
    'America/El_Salvador' => '',
    'America/Grenada' => '',
    'America/Guadeloupe' => '',
    'America/Guatemala' => '',
    'America/Port-au-Prince' => '',
    'America/Tegucigalpa' => '',
    'America/Jamaica' => '',
    'America/Martinique' => '',
    'America/Montserrat' => '',
    'America/Managua' => '',
    'America/Panama' => '',
    'America/Puerto_Rico' => '',
    'America/St_Kitts' => '',
    'America/St_Lucia' => '',
    'America/Miquelon' => '',
    'America/St_Vincent' => '',
    'America/Grand_Turk' => '',
    'America/Tortola' => '',
    'America/St_Thomas' => '',
    'America/Argentina/Buenos_Aires' => '',
    'America/Argentina/Cordoba' => '',
    'America/Argentina/Tucuman' => '',
    'America/Argentina/La_Rioja' => '',
    'America/Argentina/San_Juan' => '',
    'America/Argentina/Jujuy' => '',
    'America/Argentina/Catamarca' => '',
    'America/Argentina/Mendoza' => '',
    'America/Argentina/Rio_Gallegos' => '',
    'America/Argentina/Ushuaia' => '',
    'America/Aruba' => '',
    'America/La_Paz' => '',
    'America/Noronha' => '',
    'America/Belem' => '',
    'America/Fortaleza' => '',
    'America/Recife' => '',
    'America/Araguaina' => '',
    'America/Maceio' => '',
    'America/Bahia' => '',
    'America/Sao_Paulo' => '',
    'America/Campo_Grande' => '',
    'America/Cuiaba' => '',
    'America/Porto_Velho' => '',
    'America/Boa_Vista' => '',
    'America/Manaus' => '',
    'America/Eirunepe' => '',
    'America/Rio_Branco' => '',
    'America/Santiago' => '',
    'Pacific/Easter' => '',
    'America/Bogota' => '',
    'America/Curacao' => '',
    'America/Guayaquil' => '',
    'Pacific/Galapagos' => '',
    'Atlantic/Stanley' => '',
    'America/Cayenne' => '',
    'America/Guyana' => '',
    'America/Asuncion' => '',
    'America/Lima' => '',
    'Atlantic/South_Georgia' => '',
    'America/Paramaribo' => '',
    'America/Port_of_Spain' => '',
    'America/Montevideo' => '',
    'America/Caracas' => '',
);

$app_list_strings['eapm_list'] = array(
    'Sugar' => '',
    'WebEx' => '',
    'GoToMeeting' => '',
    'IBMSmartCloud' => '',
    'Google' => '',
    'Box' => '',
    'Facebook' => '',
    'Twitter' => '',
);
$app_list_strings['eapm_list_import'] = array(
    'Google' => '',
);
$app_list_strings['eapm_list_documents'] = array(
    'Google' => '',
);
$app_list_strings['token_status'] = array(
    1 => '',
    2 => '',
    3 => '',
);
// PR 5464
$app_list_strings ['emailTemplates_type_list'] = array(
    '' => '',
    'campaign' => '',
    'email' => '',
    'event' => '',
);

$app_list_strings ['emailTemplates_type_list_campaigns'] = array(
    '' => '',
    'campaign' => '',
);

$app_list_strings ['emailTemplates_type_list_no_workflow'] = array(
    '' => '',
    'campaign' => '',
    'email' => '',
    'event' => '',
    'system' => '',
);

// knowledge base
$app_list_strings['moduleList']['AOK_KnowledgeBase'] = ''; // Shows in the ALL menu entries
$app_list_strings['moduleList']['AOK_Knowledge_Base_Categories'] = ''; // Shows in the ALL menu entries
$app_list_strings['aok_status_list']['Draft'] = '';
$app_list_strings['aok_status_list']['Expired'] = '';
$app_list_strings['aok_status_list']['In_Review'] = '';
//$app_list_strings['aok_status_list']['Published'] = 'Published';
$app_list_strings['aok_status_list']['published_private'] = '';
$app_list_strings['aok_status_list']['published_public'] = '';

$app_list_strings['moduleList']['FP_events'] = '';
$app_list_strings['moduleList']['FP_Event_Locations'] = '';

//events
$app_list_strings['fp_event_invite_status_dom']['Invited'] = '';
$app_list_strings['fp_event_invite_status_dom']['Not Invited'] = '';
$app_list_strings['fp_event_invite_status_dom']['Attended'] = '';
$app_list_strings['fp_event_invite_status_dom']['Not Attended'] = '';
$app_list_strings['fp_event_status_dom']['Accepted'] = '';
$app_list_strings['fp_event_status_dom']['Declined'] = '';
$app_list_strings['fp_event_status_dom']['No Response'] = '';

$app_strings['LBL_STATUS_EVENT'] = '';
$app_strings['LBL_ACCEPT_STATUS'] = '';
$app_strings['LBL_LISTVIEW_OPTION_CURRENT'] = '';
$app_strings['LBL_LISTVIEW_OPTION_ENTIRE'] = '';
$app_strings['LBL_LISTVIEW_NONE'] = '';

//aod
$app_list_strings['moduleList']['AOD_IndexEvent'] = '';
$app_list_strings['moduleList']['AOD_Index'] = '';

$app_list_strings['moduleList']['AOP_Case_Events'] = '';
$app_list_strings['moduleList']['AOP_Case_Updates'] = '';
$app_strings['LBL_AOP_EMAIL_REPLY_DELIMITER'] = '';


//aop PR 5426
$app_list_strings['moduleList']['JAccount'] = '';

$app_list_strings['case_state_default_key'] = '';
$app_list_strings['case_state_dom'] =
    array(
        'Open' => '',
        'Closed' => '',
    );
$app_list_strings['case_status_default_key'] = '';
$app_list_strings['case_status_dom'] =
    array(
        'Open_New' => '',
        'Open_Assigned' => '',
        'Closed_Closed' => '',
        'Open_Pending Input' => '',
        'Closed_Rejected' => '',
        'Closed_Duplicate' => '',
    );
$app_list_strings['contact_portal_user_type_dom'] =
    array(
        'Single' => '',
        'Account' => '',
    );
$app_list_strings['dom_email_distribution_for_auto_create'] = array(
    'AOPDefault' => '',
    'singleUser' => '',
    'roundRobin' => '',
    'leastBusy' => '',
    'random' => '',
);

//aor
$app_list_strings['moduleList']['AOR_Reports'] = '';
$app_list_strings['moduleList']['AOR_Conditions'] = '';
$app_list_strings['moduleList']['AOR_Charts'] = '';
$app_list_strings['moduleList']['AOR_Fields'] = '';
$app_list_strings['moduleList']['AOR_Scheduled_Reports'] = '';
$app_list_strings['aor_operator_list']['Equal_To'] = '';
$app_list_strings['aor_operator_list']['Not_Equal_To'] = '';
$app_list_strings['aor_operator_list']['Greater_Than'] = '';
$app_list_strings['aor_operator_list']['Less_Than'] = '';
$app_list_strings['aor_operator_list']['Greater_Than_or_Equal_To'] = '';
$app_list_strings['aor_operator_list']['Less_Than_or_Equal_To'] = '';
$app_list_strings['aor_operator_list']['Contains'] = '';
$app_list_strings['aor_operator_list']['Not_Contains'] = '';
$app_list_strings['aor_operator_list']['Starts_With'] = '';
$app_list_strings['aor_operator_list']['Ends_With'] = '';
$app_list_strings['aor_format_options'][''] = '';
$app_list_strings['aor_format_options']['Y-m-d'] = '';
$app_list_strings['aor_format_options']['m-d-Y'] = '';
$app_list_strings['aor_format_options']['d-m-Y'] = '';
$app_list_strings['aor_format_options']['Y/m/d'] = '';
$app_list_strings['aor_format_options']['m/d/Y'] = '';
$app_list_strings['aor_format_options']['d/m/Y'] = '';
$app_list_strings['aor_format_options']['Y.m.d'] = '';
$app_list_strings['aor_format_options']['m.d.Y'] = '';
$app_list_strings['aor_format_options']['d.m.Y'] = '';
$app_list_strings['aor_format_options']['Ymd'] = '';
$app_list_strings['aor_format_options']['Y-m'] = '';
$app_list_strings['aor_format_options']['Y'] = '';
$app_list_strings['aor_condition_operator_list']['And'] = '';
$app_list_strings['aor_condition_operator_list']['OR'] = '';
$app_list_strings['aor_condition_type_list']['Value'] = '';
$app_list_strings['aor_condition_type_list']['Field'] = '';
$app_list_strings['aor_condition_type_list']['Date'] = '';
$app_list_strings['aor_condition_type_list']['Multi'] = '';
$app_list_strings['aor_condition_type_list']['Period'] = '';
$app_list_strings['aor_condition_type_list']['CurrentUserID'] = '';
$app_list_strings['aor_date_type_list'][''] = '';
$app_list_strings['aor_date_type_list']['minute'] = '';
$app_list_strings['aor_date_type_list']['hour'] = '';
$app_list_strings['aor_date_type_list']['day'] = '';
$app_list_strings['aor_date_type_list']['week'] = '';
$app_list_strings['aor_date_type_list']['month'] = '';
$app_list_strings['aor_date_type_list']['business_hours'] = '';
$app_list_strings['aor_date_options']['now'] = '';
$app_list_strings['aor_date_options']['field'] = '';
$app_list_strings['aor_date_operator']['now'] = '';
$app_list_strings['aor_date_operator']['plus'] = '';
$app_list_strings['aor_date_operator']['minus'] = '';
$app_list_strings['aor_sort_operator'][''] = '';
$app_list_strings['aor_sort_operator']['ASC'] = '';
$app_list_strings['aor_sort_operator']['DESC'] = '';
$app_list_strings['aor_function_list'][''] = '';
$app_list_strings['aor_function_list']['COUNT'] = '';
$app_list_strings['aor_function_list']['MIN'] = '';
$app_list_strings['aor_function_list']['MAX'] = '';
$app_list_strings['aor_function_list']['SUM'] = '';
$app_list_strings['aor_function_list']['AVG'] = '';
$app_list_strings['aor_total_options'][''] = '';
$app_list_strings['aor_total_options']['COUNT'] = '';
$app_list_strings['aor_total_options']['SUM'] = '';
$app_list_strings['aor_total_options']['AVG'] = '';
$app_list_strings['aor_chart_types']['bar'] = '';
$app_list_strings['aor_chart_types']['line'] = '';
$app_list_strings['aor_chart_types']['pie'] = '';
$app_list_strings['aor_chart_types']['radar'] = '';
$app_list_strings['aor_chart_types']['stacked_bar'] = '';
$app_list_strings['aor_chart_types']['grouped_bar'] = '';
$app_list_strings['aor_scheduled_report_schedule_types']['monthly'] = '';
$app_list_strings['aor_scheduled_report_schedule_types']['weekly'] = '';
$app_list_strings['aor_scheduled_report_schedule_types']['daily'] = '';
$app_list_strings['aor_scheduled_reports_status_dom']['active'] = '';
$app_list_strings['aor_scheduled_reports_status_dom']['inactive'] = '';
$app_list_strings['aor_email_type_list']['Email Address'] = '';
$app_list_strings['aor_email_type_list']['Specify User'] = '';
$app_list_strings['aor_email_type_list']['Users'] = '';
$app_list_strings['aor_assign_options']['all'] = '';
$app_list_strings['aor_assign_options']['role'] = '';
$app_list_strings['aor_assign_options']['security_group'] = '';
$app_list_strings['date_time_period_list']['today'] = '';
$app_list_strings['date_time_period_list']['yesterday'] = '';
$app_list_strings['date_time_period_list']['this_week'] = '';
$app_list_strings['date_time_period_list']['last_week'] = '';
$app_list_strings['date_time_period_list']['last_month'] = '';
$app_list_strings['date_time_period_list']['this_month'] = '';
$app_list_strings['date_time_period_list']['this_quarter'] = '';
$app_list_strings['date_time_period_list']['last_quarter'] = '';
$app_list_strings['date_time_period_list']['this_year'] = '';
$app_list_strings['date_time_period_list']['last_year'] = '';
$app_strings['LBL_CRON_ON_THE_MONTHDAY'] = '';
$app_strings['LBL_CRON_ON_THE_WEEKDAY'] = '';
$app_strings['LBL_CRON_AT'] = '';
$app_strings['LBL_CRON_RAW'] = '';
$app_strings['LBL_CRON_MIN'] = '';
$app_strings['LBL_CRON_HOUR'] = '';
$app_strings['LBL_CRON_DAY'] = '';
$app_strings['LBL_CRON_MONTH'] = '';
$app_strings['LBL_CRON_DOW'] = '';
$app_strings['LBL_CRON_DAILY'] = '';
$app_strings['LBL_CRON_WEEKLY'] = '';
$app_strings['LBL_CRON_MONTHLY'] = '';

//aos
$app_list_strings['moduleList']['AOS_Contracts'] = '';
$app_list_strings['moduleList']['AOS_Invoices'] = '';
$app_list_strings['moduleList']['AOS_PDF_Templates'] = '';
$app_list_strings['moduleList']['AOS_Product_Categories'] = '';
$app_list_strings['moduleList']['AOS_Products'] = '';
$app_list_strings['moduleList']['AOS_Products_Quotes'] = '';
$app_list_strings['moduleList']['AOS_Line_Item_Groups'] = '';
$app_list_strings['moduleList']['AOS_Quotes'] = '';
$app_list_strings['aos_quotes_type_dom'][''] = '';
$app_list_strings['aos_quotes_type_dom']['Analyst'] = '';
$app_list_strings['aos_quotes_type_dom']['Competitor'] = '';
$app_list_strings['aos_quotes_type_dom']['Customer'] = '';
$app_list_strings['aos_quotes_type_dom']['Integrator'] = '';
$app_list_strings['aos_quotes_type_dom']['Investor'] = '';
$app_list_strings['aos_quotes_type_dom']['Partner'] = '';
$app_list_strings['aos_quotes_type_dom']['Press'] = '';
$app_list_strings['aos_quotes_type_dom']['Prospect'] = '';
$app_list_strings['aos_quotes_type_dom']['Reseller'] = '';
$app_list_strings['aos_quotes_type_dom']['Other'] = '';
$app_list_strings['template_ddown_c_list'][''] = '';
$app_list_strings['quote_stage_dom']['Draft'] = '';
$app_list_strings['quote_stage_dom']['Negotiation'] = '';
$app_list_strings['quote_stage_dom']['Delivered'] = '';
$app_list_strings['quote_stage_dom']['On Hold'] = '';
$app_list_strings['quote_stage_dom']['Confirmed'] = '';
$app_list_strings['quote_stage_dom']['Closed Accepted'] = '';
$app_list_strings['quote_stage_dom']['Closed Lost'] = '';
$app_list_strings['quote_stage_dom']['Closed Dead'] = '';
$app_list_strings['quote_term_dom']['Net 15'] = '';
$app_list_strings['quote_term_dom']['Net 30'] = '';
$app_list_strings['quote_term_dom'][''] = '';
$app_list_strings['approval_status_dom']['Approved'] = '';
$app_list_strings['approval_status_dom']['Not Approved'] = '';
$app_list_strings['approval_status_dom'][''] = '';
$app_list_strings['vat_list']['0.0'] = '';
$app_list_strings['vat_list']['5.0'] = '';
$app_list_strings['vat_list']['7.5'] = '';
$app_list_strings['vat_list']['17.5'] = '';
$app_list_strings['vat_list']['20.0'] = '';
$app_list_strings['discount_list']['Percentage'] = '';
$app_list_strings['discount_list']['Amount'] = '';
$app_list_strings['aos_invoices_type_dom'][''] = '';
$app_list_strings['aos_invoices_type_dom']['Analyst'] = '';
$app_list_strings['aos_invoices_type_dom']['Competitor'] = '';
$app_list_strings['aos_invoices_type_dom']['Customer'] = '';
$app_list_strings['aos_invoices_type_dom']['Integrator'] = '';
$app_list_strings['aos_invoices_type_dom']['Investor'] = '';
$app_list_strings['aos_invoices_type_dom']['Partner'] = '';
$app_list_strings['aos_invoices_type_dom']['Press'] = '';
$app_list_strings['aos_invoices_type_dom']['Prospect'] = '';
$app_list_strings['aos_invoices_type_dom']['Reseller'] = '';
$app_list_strings['aos_invoices_type_dom']['Other'] = '';
$app_list_strings['invoice_status_dom']['Paid'] = '';
$app_list_strings['invoice_status_dom']['Unpaid'] = '';
$app_list_strings['invoice_status_dom']['Cancelled'] = '';
$app_list_strings['invoice_status_dom'][''] = '';
$app_list_strings['quote_invoice_status_dom']['Not Invoiced'] = '';
$app_list_strings['quote_invoice_status_dom']['Invoiced'] = '';
$app_list_strings['product_code_dom']['XXXX'] = '';
$app_list_strings['product_code_dom']['YYYY'] = '';
$app_list_strings['product_category_dom']['Laptops'] = '';
$app_list_strings['product_category_dom']['Desktops'] = '';
$app_list_strings['product_category_dom'][''] = '';
$app_list_strings['product_type_dom']['Good'] = '';
$app_list_strings['product_type_dom']['Service'] = '';
$app_list_strings['product_quote_parent_type_dom']['AOS_Quotes'] = '';
$app_list_strings['product_quote_parent_type_dom']['AOS_Invoices'] = '';
$app_list_strings['product_quote_parent_type_dom']['AOS_Contracts'] = '';
// STIC-Custom 20220124 MHP - Delete the values of the pdf_template_type_dom  
// STIC#564            
// $app_list_strings['pdf_template_type_dom']['AOS_Quotes'] = 'Presupuestos';
// $app_list_strings['pdf_template_type_dom']['AOS_Invoices'] = 'Facturas';
// $app_list_strings['pdf_template_type_dom']['AOS_Contracts'] = 'Contratos';
// $app_list_strings['pdf_template_type_dom']['Accounts'] = 'Cuentas';
// $app_list_strings['pdf_template_type_dom']['Contacts'] = 'Contactos';
// $app_list_strings['pdf_template_type_dom']['Leads'] = 'Clientes Potenciales';
// END STIC-Custom
$app_list_strings['pdf_template_sample_dom'][''] = '';
$app_list_strings['contract_status_list']['Not Started'] = '';
$app_list_strings['contract_status_list']['In Progress'] = '';
$app_list_strings['contract_status_list']['Signed'] = '';
$app_list_strings['contract_type_list']['Type'] = '';
$app_strings['LBL_PRINT_AS_PDF'] = '';
$app_strings['LBL_SELECT_TEMPLATE'] = '';
$app_strings['LBL_NO_TEMPLATE'] = '';

//aow PR 5775
$app_list_strings['moduleList']['AOW_WorkFlow'] = '';
$app_list_strings['moduleList']['AOW_Conditions'] = '';
$app_list_strings['moduleList']['AOW_Processed'] = '';
$app_list_strings['moduleList']['AOW_Actions'] = '';
$app_list_strings['aow_status_list']['Active'] = '';
$app_list_strings['aow_status_list']['Inactive'] = '';
$app_list_strings['aow_operator_list']['Equal_To'] = '';
$app_list_strings['aow_operator_list']['Not_Equal_To'] = '';
$app_list_strings['aow_operator_list']['Greater_Than'] = '';
$app_list_strings['aow_operator_list']['Less_Than'] = '';
$app_list_strings['aow_operator_list']['Greater_Than_or_Equal_To'] = '';
$app_list_strings['aow_operator_list']['Less_Than_or_Equal_To'] = '';
$app_list_strings['aow_operator_list']['Contains'] = '';
$app_list_strings['aow_operator_list']['Not_Contains'] = '';
$app_list_strings['aow_operator_list']['Starts_With'] = '';
$app_list_strings['aow_operator_list']['Ends_With'] = '';
$app_list_strings['aow_operator_list']['is_null'] = '';
$app_list_strings['aow_operator_list']['is_not_null'] = '';
$app_list_strings['aow_operator_list']['Anniversary'] = '';
$app_list_strings['aow_process_status_list']['Complete'] = '';
$app_list_strings['aow_process_status_list']['Running'] = '';
$app_list_strings['aow_process_status_list']['Pending'] = '';
$app_list_strings['aow_process_status_list']['Failed'] = '';
$app_list_strings['aow_condition_operator_list']['And'] = '';
$app_list_strings['aow_condition_operator_list']['OR'] = '';
$app_list_strings['aow_condition_type_list']['Value'] = '';
$app_list_strings['aow_condition_type_list']['Field'] = '';
$app_list_strings['aow_condition_type_list']['Any_Change'] = '';
$app_list_strings['aow_condition_type_list']['SecurityGroup'] = '';
$app_list_strings['aow_condition_type_list']['Date'] = '';
$app_list_strings['aow_condition_type_list']['Multi'] = '';
$app_list_strings['aow_action_type_list']['Value'] = '';
$app_list_strings['aow_action_type_list']['Field'] = '';
$app_list_strings['aow_action_type_list']['Date'] = '';
$app_list_strings['aow_action_type_list']['Round_Robin'] = '';
$app_list_strings['aow_action_type_list']['Least_Busy'] = '';
$app_list_strings['aow_action_type_list']['Random'] = '';
$app_list_strings['aow_rel_action_type_list']['Value'] = '';
$app_list_strings['aow_rel_action_type_list']['Field'] = '';
$app_list_strings['aow_date_type_list'][''] = '';
$app_list_strings['aow_date_type_list']['minute'] = '';
$app_list_strings['aow_date_type_list']['hour'] = '';
$app_list_strings['aow_date_type_list']['day'] = '';
$app_list_strings['aow_date_type_list']['week'] = '';
$app_list_strings['aow_date_type_list']['month'] = '';
$app_list_strings['aow_date_type_list']['business_hours'] = '';
$app_list_strings['aow_date_options']['now'] = '';
$app_list_strings['aow_date_options']['today'] = '';
$app_list_strings['aow_date_options']['field'] = '';
$app_list_strings['aow_date_operator']['now'] = '';
$app_list_strings['aow_date_operator']['plus'] = '';
$app_list_strings['aow_date_operator']['minus'] = '';
$app_list_strings['aow_assign_options']['all'] = '';
$app_list_strings['aow_assign_options']['role'] = '';
$app_list_strings['aow_assign_options']['security_group'] = '';
$app_list_strings['aow_email_type_list']['Email Address'] = '';
$app_list_strings['aow_email_type_list']['Record Email'] = '';
$app_list_strings['aow_email_type_list']['Related Field'] = '';
$app_list_strings['aow_email_type_list']['Specify User'] = '';
$app_list_strings['aow_email_type_list']['Users'] = '';
$app_list_strings['aow_email_to_list']['to'] = '';
$app_list_strings['aow_email_to_list']['cc'] = '';
$app_list_strings['aow_email_to_list']['bcc'] = '';
$app_list_strings['aow_run_on_list']['All_Records'] = '';
$app_list_strings['aow_run_on_list']['New_Records'] = '';
$app_list_strings['aow_run_on_list']['Modified_Records'] = '';
$app_list_strings['aow_run_when_list']['Always'] = '';
$app_list_strings['aow_run_when_list']['On_Save'] = '';
$app_list_strings['aow_run_when_list']['In_Scheduler'] = '';

//gant
$app_list_strings['moduleList']['AM_ProjectTemplates'] = '';
$app_list_strings['moduleList']['AM_TaskTemplates'] = '';
$app_list_strings['relationship_type_list']['FS'] = '';
$app_list_strings['relationship_type_list']['SS'] = '';
$app_list_strings['duration_unit_dom']['Days'] = '';
$app_list_strings['duration_unit_dom']['Hours'] = '';
$app_strings['LBL_GANTT_BUTTON_LABEL'] = '';
$app_strings['LBL_DETAIL_BUTTON_LABEL'] = '';
$app_strings['LBL_CREATE_PROJECT'] = '';

//gmaps
$app_strings['LBL_MAP'] = '';

$app_strings['LBL_JJWG_MAPS_LNG'] = '';
$app_strings['LBL_JJWG_MAPS_LAT'] = '';
$app_strings['LBL_JJWG_MAPS_GEOCODE_STATUS'] = '';
$app_strings['LBL_JJWG_MAPS_ADDRESS'] = '';

$app_list_strings['moduleList']['jjwg_Maps'] = '';
$app_list_strings['moduleList']['jjwg_Markers'] = '';
$app_list_strings['moduleList']['jjwg_Areas'] = '';
$app_list_strings['moduleList']['jjwg_Address_Cache'] = '';

$app_list_strings['moduleList']['jjwp_Partners'] = '';

$app_list_strings['map_unit_type_list']['mi'] = '';
$app_list_strings['map_unit_type_list']['km'] = '';

$app_list_strings['map_module_type_list']['Accounts'] = '';
$app_list_strings['map_module_type_list']['Contacts'] = '';
$app_list_strings['map_module_type_list']['Cases'] = '';
$app_list_strings['map_module_type_list']['Leads'] = '';
$app_list_strings['map_module_type_list']['Meetings'] = '';
$app_list_strings['map_module_type_list']['Opportunities'] = '';
$app_list_strings['map_module_type_list']['Project'] = '';
$app_list_strings['map_module_type_list']['Prospects'] = '';

$app_list_strings['map_relate_type_list']['Accounts'] = '';
$app_list_strings['map_relate_type_list']['Contacts'] = '';
$app_list_strings['map_relate_type_list']['Cases'] = '';
$app_list_strings['map_relate_type_list']['Leads'] = '';
$app_list_strings['map_relate_type_list']['Meetings'] = '';
$app_list_strings['map_relate_type_list']['Opportunities'] = '';
$app_list_strings['map_relate_type_list']['Project'] = '';
$app_list_strings['map_relate_type_list']['Prospects'] = '';

$app_list_strings['marker_image_list']['accident'] = '';
$app_list_strings['marker_image_list']['administration'] = '';
$app_list_strings['marker_image_list']['agriculture'] = '';
$app_list_strings['marker_image_list']['aircraft_small'] = '';
$app_list_strings['marker_image_list']['airplane_tourism'] = '';
$app_list_strings['marker_image_list']['airport'] = '';
$app_list_strings['marker_image_list']['amphitheater'] = '';
$app_list_strings['marker_image_list']['apartment'] = '';
$app_list_strings['marker_image_list']['aquarium'] = '';
$app_list_strings['marker_image_list']['arch'] = '';
$app_list_strings['marker_image_list']['atm'] = '';
$app_list_strings['marker_image_list']['audio'] = '';
$app_list_strings['marker_image_list']['bank'] = '';
$app_list_strings['marker_image_list']['bank_euro'] = '';
$app_list_strings['marker_image_list']['bank_pound'] = '';
$app_list_strings['marker_image_list']['bar'] = '';
$app_list_strings['marker_image_list']['beach'] = '';
$app_list_strings['marker_image_list']['beautiful'] = '';
$app_list_strings['marker_image_list']['bicycle_parking'] = '';
$app_list_strings['marker_image_list']['big_city'] = '';
$app_list_strings['marker_image_list']['bridge'] = '';
$app_list_strings['marker_image_list']['bridge_modern'] = '';
$app_list_strings['marker_image_list']['bus'] = '';
$app_list_strings['marker_image_list']['cable_car'] = '';
$app_list_strings['marker_image_list']['car'] = '';
$app_list_strings['marker_image_list']['car_rental'] = '';
$app_list_strings['marker_image_list']['carrepair'] = '';
$app_list_strings['marker_image_list']['castle'] = '';
$app_list_strings['marker_image_list']['cathedral'] = '';
$app_list_strings['marker_image_list']['chapel'] = '';
$app_list_strings['marker_image_list']['church'] = '';
$app_list_strings['marker_image_list']['city_square'] = '';
$app_list_strings['marker_image_list']['cluster'] = '';
$app_list_strings['marker_image_list']['cluster_2'] = '';
$app_list_strings['marker_image_list']['cluster_3'] = '';
$app_list_strings['marker_image_list']['cluster_4'] = '';
$app_list_strings['marker_image_list']['cluster_5'] = '';
$app_list_strings['marker_image_list']['coffee'] = '';
$app_list_strings['marker_image_list']['community_centre'] = '';
$app_list_strings['marker_image_list']['company'] = '';
$app_list_strings['marker_image_list']['conference'] = '';
$app_list_strings['marker_image_list']['construction'] = '';
$app_list_strings['marker_image_list']['convenience'] = '';
$app_list_strings['marker_image_list']['court'] = '';
$app_list_strings['marker_image_list']['cruise'] = '';
$app_list_strings['marker_image_list']['currency_exchange'] = '';
$app_list_strings['marker_image_list']['customs'] = '';
$app_list_strings['marker_image_list']['cycling'] = '';
$app_list_strings['marker_image_list']['dam'] = '';
$app_list_strings['marker_image_list']['dentist'] = '';
$app_list_strings['marker_image_list']['deptartment_store'] = '';
$app_list_strings['marker_image_list']['disability'] = '';
$app_list_strings['marker_image_list']['disabled_parking'] = '';
$app_list_strings['marker_image_list']['doctor'] = '';
$app_list_strings['marker_image_list']['dog_leash'] = '';
$app_list_strings['marker_image_list']['down'] = '';
$app_list_strings['marker_image_list']['down_left'] = '';
$app_list_strings['marker_image_list']['down_right'] = '';
$app_list_strings['marker_image_list']['down_then_left'] = '';
$app_list_strings['marker_image_list']['down_then_right'] = '';
$app_list_strings['marker_image_list']['drugs'] = '';
$app_list_strings['marker_image_list']['elevator'] = '';
$app_list_strings['marker_image_list']['embassy'] = '';
$app_list_strings['marker_image_list']['expert'] = '';
$app_list_strings['marker_image_list']['factory'] = '';
$app_list_strings['marker_image_list']['falling_rocks'] = '';
$app_list_strings['marker_image_list']['fast_food'] = '';
$app_list_strings['marker_image_list']['festival'] = '';
$app_list_strings['marker_image_list']['fjord'] = '';
$app_list_strings['marker_image_list']['forest'] = '';
$app_list_strings['marker_image_list']['fountain'] = '';
$app_list_strings['marker_image_list']['friday'] = '';
$app_list_strings['marker_image_list']['garden'] = '';
$app_list_strings['marker_image_list']['gas_station'] = '';
$app_list_strings['marker_image_list']['geyser'] = '';
$app_list_strings['marker_image_list']['gifts'] = '';
$app_list_strings['marker_image_list']['gourmet'] = '';
$app_list_strings['marker_image_list']['grocery'] = '';
$app_list_strings['marker_image_list']['hairsalon'] = '';
$app_list_strings['marker_image_list']['helicopter'] = '';
$app_list_strings['marker_image_list']['highway'] = '';
$app_list_strings['marker_image_list']['historical_quarter'] = '';
$app_list_strings['marker_image_list']['home'] = '';
$app_list_strings['marker_image_list']['hospital'] = '';
$app_list_strings['marker_image_list']['hostel'] = '';
$app_list_strings['marker_image_list']['hotel'] = '';
$app_list_strings['marker_image_list']['hotel_1_star'] = '';
$app_list_strings['marker_image_list']['hotel_2_stars'] = '';
$app_list_strings['marker_image_list']['hotel_3_stars'] = '';
$app_list_strings['marker_image_list']['hotel_4_stars'] = '';
$app_list_strings['marker_image_list']['hotel_5_stars'] = '';
$app_list_strings['marker_image_list']['info'] = '';
$app_list_strings['marker_image_list']['justice'] = '';
$app_list_strings['marker_image_list']['lake'] = '';
$app_list_strings['marker_image_list']['laundromat'] = '';
$app_list_strings['marker_image_list']['left'] = '';
$app_list_strings['marker_image_list']['left_then_down'] = '';
$app_list_strings['marker_image_list']['left_then_up'] = '';
$app_list_strings['marker_image_list']['library'] = '';
$app_list_strings['marker_image_list']['lighthouse'] = '';
$app_list_strings['marker_image_list']['liquor'] = '';
$app_list_strings['marker_image_list']['lock'] = '';
$app_list_strings['marker_image_list']['main_road'] = '';
$app_list_strings['marker_image_list']['massage'] = '';
$app_list_strings['marker_image_list']['mobile_phone_tower'] = '';
$app_list_strings['marker_image_list']['modern_tower'] = '';
$app_list_strings['marker_image_list']['monastery'] = '';
$app_list_strings['marker_image_list']['monday'] = '';
$app_list_strings['marker_image_list']['monument'] = '';
$app_list_strings['marker_image_list']['mosque'] = '';
$app_list_strings['marker_image_list']['motorcycle'] = '';
$app_list_strings['marker_image_list']['museum'] = '';
$app_list_strings['marker_image_list']['music_live'] = '';
$app_list_strings['marker_image_list']['oil_pump_jack'] = '';
$app_list_strings['marker_image_list']['pagoda'] = '';
$app_list_strings['marker_image_list']['palace'] = '';
$app_list_strings['marker_image_list']['panoramic'] = '';
$app_list_strings['marker_image_list']['park'] = '';
$app_list_strings['marker_image_list']['park_and_ride'] = '';
$app_list_strings['marker_image_list']['parking'] = '';
$app_list_strings['marker_image_list']['photo'] = '';
$app_list_strings['marker_image_list']['picnic'] = '';
$app_list_strings['marker_image_list']['places_unvisited'] = '';
$app_list_strings['marker_image_list']['places_visited'] = '';
$app_list_strings['marker_image_list']['playground'] = '';
$app_list_strings['marker_image_list']['police'] = '';
$app_list_strings['marker_image_list']['port'] = '';
$app_list_strings['marker_image_list']['postal'] = '';
$app_list_strings['marker_image_list']['power_line_pole'] = '';
$app_list_strings['marker_image_list']['power_plant'] = '';
$app_list_strings['marker_image_list']['power_substation'] = '';
$app_list_strings['marker_image_list']['public_art'] = '';
$app_list_strings['marker_image_list']['rain'] = '';
$app_list_strings['marker_image_list']['real_estate'] = '';
$app_list_strings['marker_image_list']['regroup'] = '';
$app_list_strings['marker_image_list']['resort'] = '';
$app_list_strings['marker_image_list']['restaurant'] = '';
$app_list_strings['marker_image_list']['restaurant_african'] = '';
$app_list_strings['marker_image_list']['restaurant_barbecue'] = '';
$app_list_strings['marker_image_list']['restaurant_buffet'] = '';
$app_list_strings['marker_image_list']['restaurant_chinese'] = '';
$app_list_strings['marker_image_list']['restaurant_fish'] = '';
$app_list_strings['marker_image_list']['restaurant_fish_chips'] = '';
$app_list_strings['marker_image_list']['restaurant_gourmet'] = '';
$app_list_strings['marker_image_list']['restaurant_greek'] = '';
$app_list_strings['marker_image_list']['restaurant_indian'] = '';
$app_list_strings['marker_image_list']['restaurant_italian'] = '';
$app_list_strings['marker_image_list']['restaurant_japanese'] = '';
$app_list_strings['marker_image_list']['restaurant_kebab'] = '';
$app_list_strings['marker_image_list']['restaurant_korean'] = '';
$app_list_strings['marker_image_list']['restaurant_mediterranean'] = '';
$app_list_strings['marker_image_list']['restaurant_mexican'] = '';
$app_list_strings['marker_image_list']['restaurant_romantic'] = '';
$app_list_strings['marker_image_list']['restaurant_thai'] = '';
$app_list_strings['marker_image_list']['restaurant_turkish'] = '';
$app_list_strings['marker_image_list']['right'] = '';
$app_list_strings['marker_image_list']['right_then_down'] = '';
$app_list_strings['marker_image_list']['right_then_up'] = '';
$app_list_strings['marker_image_list']['saturday'] = '';
$app_list_strings['marker_image_list']['school'] = '';
$app_list_strings['marker_image_list']['shopping_mall'] = '';
$app_list_strings['marker_image_list']['shore'] = '';
$app_list_strings['marker_image_list']['sight'] = '';
$app_list_strings['marker_image_list']['small_city'] = '';
$app_list_strings['marker_image_list']['snow'] = '';
$app_list_strings['marker_image_list']['spaceport'] = '';
$app_list_strings['marker_image_list']['speed_100'] = '';
$app_list_strings['marker_image_list']['speed_110'] = '';
$app_list_strings['marker_image_list']['speed_120'] = '';
$app_list_strings['marker_image_list']['speed_130'] = '';
$app_list_strings['marker_image_list']['speed_20'] = '';
$app_list_strings['marker_image_list']['speed_30'] = '';
$app_list_strings['marker_image_list']['speed_40'] = '';
$app_list_strings['marker_image_list']['speed_50'] = '';
$app_list_strings['marker_image_list']['speed_60'] = '';
$app_list_strings['marker_image_list']['speed_70'] = '';
$app_list_strings['marker_image_list']['speed_80'] = '';
$app_list_strings['marker_image_list']['speed_90'] = '';
$app_list_strings['marker_image_list']['speed_hump'] = '';
$app_list_strings['marker_image_list']['stadium'] = '';
$app_list_strings['marker_image_list']['statue'] = '';
$app_list_strings['marker_image_list']['steam_train'] = '';
$app_list_strings['marker_image_list']['stop'] = '';
$app_list_strings['marker_image_list']['stoplight'] = '';
$app_list_strings['marker_image_list']['subway'] = '';
$app_list_strings['marker_image_list']['sun'] = '';
$app_list_strings['marker_image_list']['sunday'] = '';
$app_list_strings['marker_image_list']['supermarket'] = '';
$app_list_strings['marker_image_list']['synagogue'] = '';
$app_list_strings['marker_image_list']['tapas'] = '';
$app_list_strings['marker_image_list']['taxi'] = '';
$app_list_strings['marker_image_list']['taxiway'] = '';
$app_list_strings['marker_image_list']['teahouse'] = '';
$app_list_strings['marker_image_list']['telephone'] = '';
$app_list_strings['marker_image_list']['temple_hindu'] = '';
$app_list_strings['marker_image_list']['terrace'] = '';
$app_list_strings['marker_image_list']['text'] = '';
$app_list_strings['marker_image_list']['theater'] = '';
$app_list_strings['marker_image_list']['theme_park'] = '';
$app_list_strings['marker_image_list']['thursday'] = '';
$app_list_strings['marker_image_list']['toilets'] = '';
$app_list_strings['marker_image_list']['toll_station'] = '';
$app_list_strings['marker_image_list']['tower'] = '';
$app_list_strings['marker_image_list']['traffic_enforcement_camera'] = '';
$app_list_strings['marker_image_list']['train'] = '';
$app_list_strings['marker_image_list']['tram'] = '';
$app_list_strings['marker_image_list']['truck'] = '';
$app_list_strings['marker_image_list']['tuesday'] = '';
$app_list_strings['marker_image_list']['tunnel'] = '';
$app_list_strings['marker_image_list']['turn_left'] = '';
$app_list_strings['marker_image_list']['turn_right'] = '';
$app_list_strings['marker_image_list']['university'] = '';
$app_list_strings['marker_image_list']['up'] = '';
$app_list_strings['marker_image_list']['up_left'] = '';
$app_list_strings['marker_image_list']['up_right'] = '';
$app_list_strings['marker_image_list']['up_then_left'] = '';
$app_list_strings['marker_image_list']['up_then_right'] = '';
$app_list_strings['marker_image_list']['vespa'] = '';
$app_list_strings['marker_image_list']['video'] = '';
$app_list_strings['marker_image_list']['villa'] = '';
$app_list_strings['marker_image_list']['water'] = '';
$app_list_strings['marker_image_list']['waterfall'] = '';
$app_list_strings['marker_image_list']['watermill'] = '';
$app_list_strings['marker_image_list']['waterpark'] = '';
$app_list_strings['marker_image_list']['watertower'] = '';
$app_list_strings['marker_image_list']['wednesday'] = '';
$app_list_strings['marker_image_list']['wifi'] = '';
$app_list_strings['marker_image_list']['wind_turbine'] = '';
$app_list_strings['marker_image_list']['windmill'] = '';
$app_list_strings['marker_image_list']['winery'] = '';
$app_list_strings['marker_image_list']['work_office'] = '';
$app_list_strings['marker_image_list']['world_heritage_site'] = '';
$app_list_strings['marker_image_list']['zoo'] = '';

//Reschedule
$app_list_strings['call_reschedule_dom'][''] = '';
$app_list_strings['call_reschedule_dom']['Out of Office'] = '';
$app_list_strings['call_reschedule_dom']['In a Meeting'] = '';

$app_strings['LBL_RESCHEDULE_LABEL'] = '';
$app_strings['LBL_RESCHEDULE_TITLE'] = '';
$app_strings['LBL_RESCHEDULE_DATE'] = '';
$app_strings['LBL_RESCHEDULE_REASON'] = '';
$app_strings['LBL_RESCHEDULE_ERROR1'] = '';
$app_strings['LBL_RESCHEDULE_ERROR2'] = '';

$app_strings['LBL_RESCHEDULE_PANEL'] = '';
$app_strings['LBL_RESCHEDULE_HISTORY'] = '';
$app_strings['LBL_RESCHEDULE_COUNT'] = '';

//SecurityGroups
$app_list_strings['moduleList']['SecurityGroups'] = '';
$app_strings['LBL_SECURITYGROUP'] = '';

$app_list_strings['moduleList']['OutboundEmailAccounts'] = '';

//social
$app_strings['FACEBOOK_USER_C'] = '';
$app_strings['TWITTER_USER_C'] = '';
$app_strings['LBL_PANEL_SOCIAL_FEED'] = '';

$app_strings['LBL_SUBPANEL_FILTER_LABEL'] = '';

$app_strings['LBL_COLLECTION_TYPE'] = '';

$app_strings['LBL_ADD_TAB'] = '';
$app_strings['LBL_EDIT_TAB'] = '';
$app_strings['LBL_SUITE_DASHBOARD'] = ''; //Can be translated in all caps. This string will be used by SuiteP template menu actions
$app_strings['LBL_ENTER_DASHBOARD_NAME'] = '';
$app_strings['LBL_NUMBER_OF_COLUMNS'] = '';
$app_strings['LBL_DELETE_DASHBOARD1'] = '';
$app_strings['LBL_DELETE_DASHBOARD2'] = '';
$app_strings['LBL_ADD_DASHBOARD_PAGE'] = '';
$app_strings['LBL_DELETE_DASHBOARD_PAGE'] = '';
$app_strings['LBL_RENAME_DASHBOARD_PAGE'] = '';
$app_strings['LBL_SUITE_DASHBOARD_ACTIONS'] = ''; //Can be translated in all caps. This string will be used by SuiteP template menu actions

$app_list_strings['collection_temp_list'] = array(
    'Tasks' => '',
    'Meetings' => '',
    'Calls' => '',
    'Notes' => '',
    'Emails' => ''
);

$app_list_strings['moduleList']['TemplateEditor'] = '';
$app_strings['LBL_CONFIRM_CANCEL_INLINE_EDITING'] = "";
$app_strings['LBL_LOADING_ERROR_INLINE_EDITING'] = "";

//SuiteSpots
$app_list_strings['spots_areas'] = array(
    'getSalesSpotsData' => '',
    'getAccountsSpotsData' => '',
    'getLeadsSpotsData' => '',
    'getServiceSpotsData' => '',
    'getMarketingSpotsData' => '',
    'getMarketingActivitySpotsData' => '',
    'getActivitiesSpotsData' => '',
    'getQuotesSpotsData' => ''
);

$app_list_strings['moduleList']['Spots'] = '';

$app_list_strings['moduleList']['AOBH_BusinessHours'] = '';
$app_list_strings['business_hours_list']['0'] = '';
$app_list_strings['business_hours_list']['1'] = '';
$app_list_strings['business_hours_list']['2'] = '';
$app_list_strings['business_hours_list']['3'] = '';
$app_list_strings['business_hours_list']['4'] = '';
$app_list_strings['business_hours_list']['5'] = '';
$app_list_strings['business_hours_list']['6'] = '';
$app_list_strings['business_hours_list']['7'] = '';
$app_list_strings['business_hours_list']['8'] = '';
$app_list_strings['business_hours_list']['9'] = '';
$app_list_strings['business_hours_list']['10'] = '';
$app_list_strings['business_hours_list']['11'] = '';
$app_list_strings['business_hours_list']['12'] = '';
$app_list_strings['business_hours_list']['13'] = '';
$app_list_strings['business_hours_list']['14'] = '';
$app_list_strings['business_hours_list']['15'] = '';
$app_list_strings['business_hours_list']['16'] = '';
$app_list_strings['business_hours_list']['17'] = '';
$app_list_strings['business_hours_list']['18'] = '';
$app_list_strings['business_hours_list']['19'] = '';
$app_list_strings['business_hours_list']['20'] = '';
$app_list_strings['business_hours_list']['21'] = '';
$app_list_strings['business_hours_list']['22'] = '';
$app_list_strings['business_hours_list']['23'] = '';
$app_list_strings['day_list']['Monday'] = '';
$app_list_strings['day_list']['Tuesday'] = '';
$app_list_strings['day_list']['Wednesday'] = '';
$app_list_strings['day_list']['Thursday'] = '';
$app_list_strings['day_list']['Friday'] = '';
$app_list_strings['day_list']['Saturday'] = '';
$app_list_strings['day_list']['Sunday'] = '';
$app_list_strings['pdf_page_size_dom']['A4'] = '';
$app_list_strings['pdf_page_size_dom']['Letter'] = '';
$app_list_strings['pdf_page_size_dom']['Legal'] = '';
$app_list_strings['pdf_orientation_dom']['Portrait'] = '';
$app_list_strings['pdf_orientation_dom']['Landscape'] = '';
$app_list_strings['run_when_dom']['When True'] = ''; // PR 6143
$app_list_strings['run_when_dom']['Once True'] = '';
$app_list_strings['sa_status_list']['Complete'] = '';
$app_list_strings['sa_status_list']['In_Review'] = '';
$app_list_strings['sa_status_list']['Issue_Resolution'] = '';
$app_list_strings['sa_status_list']['Pending_Apttus_Submission'] = '';
$app_list_strings['sharedGroupRule']['none'] = '';
$app_list_strings['sharedGroupRule']['view'] = '';
$app_list_strings['sharedGroupRule']['view_edit'] = '';
$app_list_strings['sharedGroupRule']['view_edit_delete'] = '';
$app_list_strings['moduleList']['SharedSecurityRulesFields'] = '';
$app_list_strings['moduleList']['SharedSecurityRules'] = '';
$app_list_strings['moduleList']['SharedSecurityRulesActions'] = '';
$app_list_strings['shared_email_type_list'][''] = '';
$app_list_strings['shared_email_type_list']['Specify User'] = '';
$app_list_strings['shared_email_type_list']['Users'] = '';
$app_list_strings['aow_condition_type_list']['Value'] = '';
$app_list_strings['aow_condition_type_list']['Field'] = '';
$app_list_strings['aow_condition_type_list']['Any_Change'] = '';
$app_list_strings['aow_condition_type_list']['SecurityGroup'] = '';
$app_list_strings['aow_condition_type_list']['currentUser'] = '';
$app_list_strings['aow_condition_type_list']['Date'] = '';
$app_list_strings['aow_condition_type_list']['Multi'] = '';


$app_list_strings['moduleList']['SurveyResponses'] = '';
$app_list_strings['moduleList']['Surveys'] = '';
$app_list_strings['moduleList']['SurveyQuestionResponses'] = '';
$app_list_strings['moduleList']['SurveyQuestions'] = '';
$app_list_strings['moduleList']['SurveyQuestionOptions'] = '';
$app_list_strings['survey_status_list']['Draft'] = '';
$app_list_strings['survey_status_list']['Public'] = '';
$app_list_strings['survey_status_list']['Closed'] = '';
$app_list_strings['surveys_question_type']['Text'] = '';
$app_list_strings['surveys_question_type']['Textbox'] = '';
$app_list_strings['surveys_question_type']['Checkbox'] = '';
$app_list_strings['surveys_question_type']['Radio'] = '';
$app_list_strings['surveys_question_type']['Dropdown'] = '';
$app_list_strings['surveys_question_type']['Multiselect'] = '';
$app_list_strings['surveys_question_type']['Matrix'] = '';
$app_list_strings['surveys_question_type']['DateTime'] = '';
$app_list_strings['surveys_question_type']['Date'] = '';
$app_list_strings['surveys_question_type']['Scale'] = '';
$app_list_strings['surveys_question_type']['Rating'] = '';
$app_list_strings['surveys_matrix_options'][0] = '';
$app_list_strings['surveys_matrix_options'][1] = '';
$app_list_strings['surveys_matrix_options'][2] = '';

$app_strings['LBL_OPT_IN_PENDING_EMAIL_NOT_SENT'] = '';
$app_strings['LBL_OPT_IN_PENDING_EMAIL_FAILED'] = '';
$app_strings['LBL_OPT_IN_PENDING_EMAIL_SENT'] = '';
$app_strings['LBL_OPT_IN'] = '';
$app_strings['LBL_OPT_IN_CONFIRMED'] = '';
$app_strings['LBL_OPT_IN_OPT_OUT'] = '';
$app_strings['LBL_OPT_IN_INVALID'] = '';

/** @see SugarEmailAddress */
$app_list_strings['email_settings_opt_in_dom'] = array(
    'not-opt-in' => '',
    'opt-in' => '',
    'confirmed-opt-in' => ''
);

$app_list_strings['email_confirmed_opt_in_dom'] = array(
    'not-opt-in' => '',
    'opt-in' => '',
    'confirmed-opt-in' => ''
);

$app_strings['RESPONSE_SEND_CONFIRM_OPT_IN_EMAIL'] = '';
$app_strings['RESPONSE_SEND_CONFIRM_OPT_IN_EMAIL_NOT_OPT_IN'] = '';
$app_strings['RESPONSE_SEND_CONFIRM_OPT_IN_EMAIL_MISSING_EMAIL_ADDRESS_ID'] = '';

$app_strings['ERR_TWO_FACTOR_FAILED'] = '';
$app_strings['ERR_TWO_FACTOR_CODE_SENT'] = '';
$app_strings['ERR_TWO_FACTOR_CODE_FAILED'] = '';
$app_strings['LBL_THANKS_FOR_SUBMITTING'] = '';

$app_strings['ERR_IP_CHANGE'] = '';
$app_strings['ERR_RETURN'] = '';


$app_list_strings['oauth2_grant_type_dom'] = array(
    'password' => '',
    'client_credentials' => '',
    'implicit' => '',
    'authorization_code' => ''
);

$app_list_strings['oauth2_duration_units'] = [
    'minute' => '',
    'hour' => '',
    'day' => '',
    'week' => '',
    'month' => '',
];

$app_list_strings['search_controllers'] = [
    'Search' => '',
    'UnifiedSearch' => ''
];


$app_strings['LBL_DEFAULT_API_ERROR_TITLE'] = '';
$app_strings['LBL_DEFAULT_API_ERROR_DETAIL'] = '';
$app_strings['LBL_API_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_BAD_REQUEST_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_EMPTY_BODY_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_INVALID_JSON_API_REQUEST_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_INVALID_JSON_API_RESPONSE_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_MODULE_NOT_FOUND_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_NOT_ACCEPTABLE_EXCEPTION_DETAIL'] = '';
$app_strings['LBL_UNSUPPORTED_MEDIA_TYPE_EXCEPTION_DETAIL'] = '';

$app_strings['MSG_BROWSER_NOTIFICATIONS_ENABLED'] = '';
$app_strings['MSG_BROWSER_NOTIFICATIONS_DISABLED'] = '';
$app_strings['MSG_BROWSER_NOTIFICATIONS_UNSUPPORTED'] = '';

$app_strings['LBL_GOOGLE_SYNC_ERR'] = '';
$app_strings['LBL_THERE_WAS_AN_ERR'] = '';
$app_strings['LBL_CLICK_HERE'] = '';
$app_strings['LBL_TO_CONTINUE'] = '';

$app_strings['IMAP_HANDLER_ERROR'] = '';
$app_strings['IMAP_HANDLER_SUCCESS'] = '';
$app_strings['IMAP_HANDLER_ERROR_INVALID_REQUEST'] = '';
$app_strings['IMAP_HANDLER_ERROR_UNKNOWN_BY_KEY'] = '';
$app_strings['IMAP_HANDLER_ERROR_NO_TEST_SET'] = '';
$app_strings['IMAP_HANDLER_ERROR_NO_KEY'] = '';
$app_strings['IMAP_HANDLER_ERROR_KEY_SAVE'] = '';
$app_strings['IMAP_HANDLER_ERROR_UNKNOWN'] = '';
$app_strings['LBL_SEARCH_TITLE']                   = '';
$app_strings['LBL_SEARCH_TEXT_FIELD_TITLE_ATTR']   = '';
$app_strings['LBL_SEARCH_SUBMIT_FIELD_TITLE_ATTR'] = '';
$app_strings['LBL_SEARCH_SUBMIT_FIELD_VALUE']      = '';
$app_strings['LBL_SEARCH_QUERY']                   = '';
$app_strings['LBL_SEARCH_RESULTS_PER_PAGE']        = '';
$app_strings['LBL_SEARCH_ENGINE']                  = '';
$app_strings['LBL_SEARCH_TOTAL'] = '';
$app_strings['LBL_SEARCH_PREV'] = '';
$app_strings['LBL_SEARCH_NEXT'] = '';
$app_strings['LBL_SEARCH_PAGE'] = '';
$app_strings['LBL_SEARCH_OF'] = ''; // Usage: Page 1 of 5
$app_strings['LBL_USE_ADVANCED_SEARCH'] = '';
$app_strings['LBL_USE_BASIC_SEARCH'] = '';

// PDF Engines
$app_strings['LBL_LEGACY_MPDF_ENGINE'] = '';
$app_strings['LBL_TCPDF_ENGINE'] = '';

$app_strings['ERR_INVALID_FILE_NAME'] = '';
$app_strings['LBL_LOGGER_VALID_FILENAME_CHARACTERS'] = '';
$app_strings['LBL_LOGGER_INVALID_FILENAME'] = '';

$app_strings['LBL_PASSWORD_SET_NEW_VALUE_TO_RESET'] = '';
$app_strings['LBL_VALUE_SET_PLACEHOLDER'] = '';
