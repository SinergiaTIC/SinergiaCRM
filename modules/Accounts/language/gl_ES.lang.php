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
    // DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_name' => '',
    'db_website' => '',
    'db_billing_address_city' => '',
    // END DON'T CONVERT
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => '',
    // Dashlet Categories
    'LBL_CHARTS' => '',
    'LBL_DEFAULT' => '',
    // END Dashlet Categories

    'ERR_DELETE_RECORD' => '',
    'LBL_ACCOUNT_INFORMATION' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_ACCOUNT_NAME' => '',
    'LBL_ACCOUNT' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_ADDRESS_INFORMATION' => '',
    'LBL_ANNUAL_REVENUE' => '',
    'LBL_ANY_ADDRESS' => '',
    'LBL_ANY_EMAIL' => '',
    'LBL_ANY_PHONE' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_ASSIGNED_TO_ID' => '',
    'LBL_BILLING_ADDRESS_CITY' => '',
    'LBL_BILLING_ADDRESS_COUNTRY' => '',
    'LBL_BILLING_ADDRESS_POSTALCODE' => '',
    'LBL_BILLING_ADDRESS_STATE' => '',
    'LBL_BILLING_ADDRESS_STREET_2' => '',
    'LBL_BILLING_ADDRESS_STREET_3' => '',
    'LBL_BILLING_ADDRESS_STREET_4' => '',
    'LBL_BILLING_ADDRESS_STREET' => '',
    'LBL_BILLING_ADDRESS' => '',
    'LBL_BUGS_SUBPANEL_TITLE' => '',
    'LBL_CAMPAIGN_ID' => '',
    'LBL_CASES_SUBPANEL_TITLE' => '',
    'LBL_CITY' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_COUNTRY' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_DESCRIPTION_INFORMATION' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DUPLICATE' => '',
    'LBL_EMAIL' => '',
    'LBL_EMAIL_OPT_OUT' => '',
    'LBL_EMAIL_ADDRESSES' => '',
    'LBL_EMPLOYEES' => '',
    'LBL_FAX' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    'LBL_INDUSTRY' => '',
    'LBL_INVALID_EMAIL' => '',
    'LBL_INVITEE' => '',
    'LBL_LEADS_SUBPANEL_TITLE' => '',
    'LBL_LIST_ACCOUNT_NAME' => '',
    'LBL_LIST_CITY' => '',
    'LBL_LIST_CONTACT_NAME' => '',
    'LBL_LIST_EMAIL_ADDRESS' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_LIST_PHONE' => '',
    'LBL_LIST_STATE' => '',
    'LBL_MEMBER_OF' => '',
    'LBL_MEMBER_ORG_SUBPANEL_TITLE' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_MODULE_ID' => '',
    'LBL_NAME' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => '',
    'LBL_OTHER_EMAIL_ADDRESS' => '',
    'LBL_OTHER_PHONE' => '',
    'LBL_OWNERSHIP' => '',
    'LBL_PARENT_ACCOUNT_ID' => '',
    'LBL_PHONE_ALT' => '',
    'LBL_PHONE_FAX' => '',
    'LBL_PHONE_OFFICE' => '',
    'LBL_PHONE' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_PRODUCTS_TITLE' => '',
    'LBL_PROJECTS_SUBPANEL_TITLE' => '',
    'LBL_PUSH_CONTACTS_BUTTON_LABEL' => '',
    'LBL_PUSH_CONTACTS_BUTTON_TITLE' => '',
    'LBL_RATING' => '',
    'LBL_SAVE_ACCOUNT' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_SHIPPING_ADDRESS_CITY' => '',
    'LBL_SHIPPING_ADDRESS_COUNTRY' => '',
    'LBL_SHIPPING_ADDRESS_POSTALCODE' => '',
    'LBL_SHIPPING_ADDRESS_STATE' => '',
    'LBL_SHIPPING_ADDRESS_STREET_2' => '',
    'LBL_SHIPPING_ADDRESS_STREET_3' => '',
    'LBL_SHIPPING_ADDRESS_STREET_4' => '',
    'LBL_SHIPPING_ADDRESS_STREET' => '',
    'LBL_SHIPPING_ADDRESS' => '',
    'LBL_SIC_CODE' => '',
    'LBL_STATE' => '',
    'LBL_TICKER_SYMBOL' => '',
    'LBL_TYPE' => '',
    'LBL_WEBSITE' => '',
    'LBL_CAMPAIGNS' => '',
    'LNK_ACCOUNT_LIST' => '',
    'LNK_NEW_ACCOUNT' => '',
    'LNK_IMPORT_ACCOUNTS' => '',
    'MSG_DUPLICATE' => '',
    'MSG_SHOW_DUPLICATES' => '',
    'LBL_ASSIGNED_USER_NAME' => '',
    'LBL_PROSPECT_LIST' => '',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => '',
    'LBL_PROJECT_SUBPANEL_TITLE' => '',
    //For export labels
    'LBL_PARENT_ID' => '',
    // SNIP
    'LBL_PRODUCTS_SERVICES_PURCHASED_SUBPANEL_TITLE' => '',

    'LBL_AOS_CONTRACTS' => '',
    'LBL_AOS_INVOICES' => '',
    'LBL_AOS_QUOTES' => '',
);