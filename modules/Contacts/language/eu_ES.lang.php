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
    'ERR_DELETE_RECORD' => '',
    'LBL_ACCOUNT_ID' => '',
    'LBL_ACCOUNT_NAME' => '',
    'LBL_CAMPAIGN' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_ADDRESS_INFORMATION' => '',
    'LBL_ALT_ADDRESS_CITY' => '',
    'LBL_ALT_ADDRESS_COUNTRY' => '',
    'LBL_ALT_ADDRESS_POSTALCODE' => '',
    'LBL_ALT_ADDRESS_STATE' => '',
    'LBL_ALT_ADDRESS_STREET_2' => '',
    'LBL_ALT_ADDRESS_STREET_3' => '',
    'LBL_ALT_ADDRESS_STREET' => '',
    'LBL_ALTERNATE_ADDRESS' => '',
    'LBL_ALT_ADDRESS' => '',
    'LBL_ANY_ADDRESS' => '',
    'LBL_ANY_EMAIL' => '',
    'LBL_ANY_PHONE' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_ASSIGNED_TO_ID' => '',
    'LBL_ASSISTANT_PHONE' => '',
    'LBL_ASSISTANT' => '',
    'LBL_BIRTHDATE' => '',
    'LBL_CITY' => '',
    'LBL_CAMPAIGN_ID' => '',
    'LBL_CONTACT_INFORMATION' => '',  //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_CONTACT_NAME' => '',
    'LBL_CONTACT_OPP_FORM_TITLE' => '',
    'LBL_CONTACT_ROLE' => '',
    'LBL_CONTACT' => '',
    'LBL_COUNTRY' => '',
    'LBL_CREATED_ACCOUNT' => '',
    'LBL_CREATED_CALL' => '',
    'LBL_CREATED_CONTACT' => '',
    'LBL_CREATED_MEETING' => '',
    'LBL_CREATED_OPPORTUNITY' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_DEPARTMENT' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DIRECT_REPORTS_SUBPANEL_TITLE' => '',
    'LBL_DO_NOT_CALL' => '',
    'LBL_DUPLICATE' => '',
    'LBL_EMAIL_ADDRESS' => '',
    'LBL_EMAIL_OPT_OUT' => '',
    'LBL_EXISTING_ACCOUNT' => '',
    'LBL_EXISTING_CONTACT' => '',
    'LBL_EXISTING_OPPORTUNITY' => '',
    'LBL_FAX_PHONE' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_HOME_PHONE' => '',
    'LBL_ID' => '',
    'LBL_IMPORT_VCARD' => '',
    'LBL_VCARD' => '',
    'LBL_IMPORT_VCARDTEXT' => '',
    'LBL_INVALID_EMAIL' => '',
    'LBL_INVITEE' => '',
    'LBL_LAST_NAME' => '',
    'LBL_LEAD_SOURCE' => '',
    'LBL_LIST_ACCEPT_STATUS' => '',
    'LBL_LIST_ACCOUNT_NAME' => '',
    'LBL_LIST_CONTACT_NAME' => '',
    'LBL_LIST_CONTACT_ROLE' => '',
    'LBL_LIST_EMAIL_ADDRESS' => '',
    'LBL_LIST_FIRST_NAME' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_LIST_LAST_NAME' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_PHONE' => '',
    'LBL_LIST_TITLE' => '',
    'LBL_MOBILE_PHONE' => '',
    'LBL_MODIFIED' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_NAME' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_NOTE_SUBJECT' => '',
    'LBL_OFFICE_PHONE' => '',
    'LBL_OPP_NAME' => '',
    'LBL_OPPORTUNITY_ROLE_ID' => '',
    'LBL_OPPORTUNITY_ROLE' => '',
    'LBL_OTHER_EMAIL_ADDRESS' => '',
    'LBL_OTHER_PHONE' => '',
    'LBL_PHONE' => '',
    'LBL_PORTAL_APP' => '',
    'LBL_PORTAL_INFORMATION' => '',
    'LBL_PORTAL_NAME' => '',
    'LBL_STREET' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_PRIMARY_ADDRESS_CITY' => '',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => '',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => '',
    'LBL_PRIMARY_ADDRESS_STATE' => '',
    'LBL_PRIMARY_ADDRESS_STREET_2' => '',
    'LBL_PRIMARY_ADDRESS_STREET_3' => '',
    'LBL_PRIMARY_ADDRESS_STREET' => '',
    'LBL_PRIMARY_ADDRESS' => '',
    'LBL_PRODUCTS_TITLE' => '',
    'LBL_REPORTS_TO_ID' => '',
    'LBL_REPORTS_TO' => '',
    'LBL_RESOURCE_NAME' => '',
    'LBL_SALUTATION' => '',
    'LBL_SAVE_CONTACT' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_SELECT_CHECKED_BUTTON_LABEL' => '',
    'LBL_SELECT_CHECKED_BUTTON_TITLE' => '',
    'LBL_STATE' => '',
    'LBL_SYNC_CONTACT' => '',
    'LBL_PROSPECT_LIST' => '',
    'LBL_TITLE' => '',
    'LNK_CONTACT_LIST' => '',
    'LNK_IMPORT_VCARD' => '',
    'LNK_NEW_ACCOUNT' => '',
    'LNK_NEW_APPOINTMENT' => '',
    'LNK_NEW_CALL' => '',
    'LNK_NEW_CASE' => '',
    'LNK_NEW_CONTACT' => '',
    'LNK_NEW_EMAIL' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_NEW_NOTE' => '',
    'LNK_NEW_OPPORTUNITY' => '',
    'LNK_NEW_TASK' => '',
    'LNK_SELECT_ACCOUNT' => "",
    'NTC_DELETE_CONFIRMATION' => '',
    'NTC_OPPORTUNITY_REQUIRES_ACCOUNT' => '',
    'NTC_REMOVE_CONFIRMATION' => '',

    'LBL_LEADS_SUBPANEL_TITLE' => '',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => '',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => '',
    'LBL_COPY_ADDRESS_CHECKED_PRIMARY' => '',
    'LBL_COPY_ADDRESS_CHECKED_ALT' => '',

    'LBL_CASES_SUBPANEL_TITLE' => '',
    'LBL_BUGS_SUBPANEL_TITLE' => '',
    'LBL_PROJECTS_SUBPANEL_TITLE' => '',
    'LBL_PROJECTS_RESOURCES' => '',
    'LBL_CAMPAIGNS' => '',
    'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE' => '',
    'LBL_LIST_CITY' => '',
    'LBL_LIST_STATE' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    'LBL_OPPORTUNITIES' => '',

    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_PROJECT_SUBPANEL_TITLE' => '',
    'LNK_IMPORT_CONTACTS' => '',

    // SNIP
    'LBL_USER_SYNC' => '',

    'LBL_FP_EVENTS_CONTACTS_FROM_FP_EVENTS_TITLE' => '',

    'LBL_AOP_CASE_UPDATES' => '',
    'LBL_CREATE_PORTAL_USER' => '',
    'LBL_ENABLE_PORTAL_USER' => '',
    'LBL_DISABLE_PORTAL_USER' => '',
    'LBL_CREATE_PORTAL_USER_FAILED' => '',
    'LBL_ENABLE_PORTAL_USER_FAILED' => '',
    'LBL_DISABLE_PORTAL_USER_FAILED' => '',
    'LBL_CREATE_PORTAL_USER_SUCCESS' => '',
    'LBL_ENABLE_PORTAL_USER_SUCCESS' => '',
    'LBL_DISABLE_PORTAL_USER_SUCCESS' => '',
    'LBL_NO_JOOMLA_URL' => '',
    'LBL_PORTAL_USER_TYPE' => '',
    'LBL_PORTAL_ACCOUNT_DISABLED' => '',
    'LBL_JOOMLA_ACCOUNT_ID' => '',
   
    'LBL_ERROR_NO_PORTAL_SELECTED' => '', // escaped single quotes required. PR 5426
    'LBL_PLEASE_UPDATE_DEPRECATED_PORTAL_ERROR' => '',
    'LBL_PLEASE_UPDATE_DEPRECATED_PORTAL_WARNING' => '',

    'LBL_INVALID_USER_DATA' => '',
    'LBL_NO_RELATED_JACCOUNT' => '',
    'LBL_UNABLE_READ_PORTAL_VERSION' => '', // PR 5426
 
    'LBL_AOS_CONTRACTS' => '',
    'LBL_AOS_INVOICES' => '',
    'LBL_AOS_QUOTES' => '',
    'LBL_PROJECT_CONTACTS_1_FROM_PROJECT_TITLE' => '',

    'LBL_LIST_INVITE_STATUS' => '',
);
