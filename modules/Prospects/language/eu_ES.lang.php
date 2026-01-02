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
    'LBL_MODULE_ID' => '',
    'LBL_INVITEE' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_LAST_NAME' => '',
    'LBL_LIST_TITLE' => '',
    'LBL_LIST_EMAIL_ADDRESS' => '',
    'LBL_LIST_PHONE' => '',
    'LBL_LIST_FIRST_NAME' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_ASSIGNED_TO_ID' => '',
    'LBL_CAMPAIGN_ID' => '',
    'LBL_EXISTING_ACCOUNT' => '',
    'LBL_CREATED_ACCOUNT' => '',
    'LBL_CREATED_CALL' => '',
    'LBL_CREATED_MEETING' => '',
    'LBL_NAME' => '',
    'LBL_PROSPECT_INFORMATION' => '', //Can be translated in all caps. This string will be used by SuiteP template menu actions
    'LBL_MORE_INFORMATION' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_OFFICE_PHONE' => '',
    'LBL_ANY_PHONE' => '',
    'LBL_PHONE' => '',
    'LBL_LAST_NAME' => '',
    'LBL_MOBILE_PHONE' => '',
    'LBL_HOME_PHONE' => '',
    'LBL_OTHER_PHONE' => '',
    'LBL_FAX_PHONE' => '',
    'LBL_PRIMARY_ADDRESS_STREET' => '',
    'LBL_PRIMARY_ADDRESS_CITY' => '',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => '',
    'LBL_PRIMARY_ADDRESS_STATE' => '',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => '',
    'LBL_ALT_ADDRESS_STREET' => '',
    'LBL_ALT_ADDRESS_CITY' => '',
    'LBL_ALT_ADDRESS_COUNTRY' => '',
    'LBL_ALT_ADDRESS_STATE' => '',
    'LBL_ALT_ADDRESS_POSTALCODE' => '',
    'LBL_TITLE' => '',
    'LBL_DEPARTMENT' => '',
    'LBL_BIRTHDATE' => '',
    'LBL_EMAIL_ADDRESS' => '',
    'LBL_OTHER_EMAIL_ADDRESS' => '',
    'LBL_ANY_EMAIL' => '',
    'LBL_ASSISTANT' => '',
    'LBL_ASSISTANT_PHONE' => '',
    'LBL_DO_NOT_CALL' => '',
    'LBL_EMAIL_OPT_OUT' => '',
    'LBL_PRIMARY_ADDRESS' => '',
    'LBL_ALTERNATE_ADDRESS' => '',
    'LBL_ANY_ADDRESS' => '',
    'LBL_CITY' => '',
    'LBL_STATE' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_COUNTRY' => '',
    'LBL_ADDRESS_INFORMATION' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_OPP_NAME' => '',
    'LBL_IMPORT_VCARD' => '',
    'LBL_IMPORT_VCARDTEXT' => '',
    'LBL_DUPLICATE' => '',
    'MSG_SHOW_DUPLICATES' => '',
    'MSG_DUPLICATE' => '',
    'LNK_IMPORT_VCARD' => '',
    'LNK_NEW_ACCOUNT' => '',
    'LNK_NEW_OPPORTUNITY' => '',
    'LNK_NEW_CASE' => '',
    'LNK_NEW_NOTE' => '',
    'LNK_NEW_CALL' => '',
    'LNK_NEW_EMAIL' => '',
    'LNK_NEW_MEETING' => '',
    'LNK_NEW_TASK' => '',
    'LNK_NEW_APPOINTMENT' => '',
    'LNK_IMPORT_PROSPECTS' => '',
    'NTC_DELETE_CONFIRMATION' => '',
    'NTC_REMOVE_CONFIRMATION' => '',
    'ERR_DELETE_RECORD' => '',
    'LBL_SALUTATION' => '',
    'LBL_CREATED_OPPORTUNITY' => '',
    'LNK_SELECT_ACCOUNT' => "",
    'LNK_NEW_PROSPECT' => '',
    'LNK_PROSPECT_LIST' => '',
    'LNK_NEW_CAMPAIGN' => '',
    'LNK_CAMPAIGN_LIST' => '',
    'LNK_NEW_PROSPECT_LIST' => '',
    'LNK_PROSPECT_LIST_LIST' => '',
    'LBL_SELECT_CHECKED_BUTTON_LABEL' => '',
    'LBL_SELECT_CHECKED_BUTTON_TITLE' => '',
    'LBL_INVALID_EMAIL' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_PROSPECT_LIST' => '',
    'LBL_CONVERT_BUTTON_TITLE' => '',
    'LBL_CONVERT_BUTTON_LABEL' => '',
    'LNK_NEW_CONTACT' => '',
    'LBL_CREATED_CONTACT' => "",
    'LBL_CAMPAIGNS' => '',
    'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE' => '',
    'LBL_TRACKER_KEY' => '',
    'LBL_LEAD_ID' => '',
    'LBL_CONVERTED_LEAD' => '',
    'LBL_ACCOUNT_NAME' => '',
    'LBL_EDIT_ACCOUNT_NAME' => '',
    'LBL_CREATED_USER' => '',
    'LBL_MODIFIED_USER' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    //For export labels
    'LBL_FP_EVENTS_PROSPECTS_1_FROM_FP_EVENTS_TITLE' => '',
);
