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
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_LOGIN' => '',
    'LBL_RESET_PREFERENCES' => '',
    'LBL_TIME_FORMAT' => '',
    'LBL_DATE_FORMAT' => '',
    'LBL_TIMEZONE' => '',
    'LBL_CURRENCY' => '',
    'LBL_LIST_NAME' => '',
    'LBL_LIST_LAST_NAME' => '',
    'LBL_LIST_EMPLOYEE_NAME' => '',
    'LBL_LIST_DEPARTMENT' => '',
    'LBL_LIST_REPORTS_TO_NAME' => '',
    'LBL_LIST_EMAIL' => '',
    'LBL_LIST_USER_NAME' => '',
    'LBL_ERROR' => '',
    'LBL_PASSWORD' => '',
    'LBL_USER_NAME' => '',
    'LBL_USER_TYPE' => '',
    'LBL_FIRST_NAME' => '',
    'LBL_LAST_NAME' => '',
    'LBL_THEME' => '',
    'LBL_LANGUAGE' => '',
    'LBL_ADMIN' => '',
    'LBL_EMPLOYEE_INFORMATION' => '',
    'LBL_OFFICE_PHONE' => '',
    'LBL_REPORTS_TO' => '',
    'LBL_REPORTS_TO_NAME' => '',
    'LBL_OTHER_PHONE' => '',
    'LBL_NOTES' => '',
    'LBL_DEPARTMENT' => '',
    'LBL_TITLE' => '',
    'LBL_ANY_ADDRESS' => '',
    'LBL_ANY_PHONE' => '',
    'LBL_ANY_EMAIL' => '',
    'LBL_ADDRESS' => '',
    'LBL_CITY' => '',
    'LBL_STATE' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_COUNTRY' => '',
    'LBL_NAME' => '',
    'LBL_MOBILE_PHONE' => '',
    'LBL_FAX' => '',
    'LBL_EMAIL' => '',
    'LBL_EMAIL_LINK_TYPE' => '',
    'LBL_EMAIL_LINK_TYPE_HELP' => '',
    'LBL_HOME_PHONE' => '',
    'LBL_WORK_PHONE' => '',
    'LBL_EMPLOYEE_STATUS' => '',
    'LBL_PRIMARY_ADDRESS' => '',
    'LBL_SAVED_SEARCH' => '',
    'LBL_MESSENGER_ID' => '',
    'LBL_MESSENGER_TYPE' => '',
    'ERR_LAST_ADMIN_1' => '',
    'ERR_LAST_ADMIN_2' => '',
    'LNK_NEW_EMPLOYEE' => '',
    'LNK_EMPLOYEE_LIST' => '',
    'ERR_DELETE_RECORD' => '',
    'LBL_LIST_EMPLOYEE_STATUS' => '',

    'LBL_SUITE_LOGIN' => '',
    'LBL_RECEIVE_NOTIFICATIONS' => '',
    'LBL_IS_ADMIN' => '',
    'LBL_GROUP' => '',
    'LBL_PHOTO' => '',
    'LBL_DELETE_USER_CONFIRM' => '',
    'LBL_DELETE_EMPLOYEE_CONFIRM' => '',
    'LBL_ONLY_ACTIVE' => '',
    'LBL_SELECT' => '' /*for 508 compliance fix*/,
    'LBL_AUTHENTICATE_ID' => '',
    'LBL_EXT_AUTHENTICATE' => '',
    'LBL_GROUP_USER' => '',
    'LBL_LIST_ACCEPT_STATUS' => '',
    'LBL_MODIFIED_BY' => '',
    'LBL_MODIFIED_BY_ID' => '',
    'LBL_CREATED_BY_NAME' => '', //bug48978
    'LBL_PORTAL_ONLY_USER' => '',
    'LBL_PSW_MODIFIED' => '',
    'LBL_SHOW_ON_EMPLOYEES' => '',
    'LBL_USER_HASH' => '',
    'LBL_SYSTEM_GENERATED_PASSWORD' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_FAX_PHONE' => '',
    'LBL_STATUS' => '',
    'LBL_ADDRESS_CITY' => '',
    'LBL_ADDRESS_COUNTRY' => '',
    'LBL_ADDRESS_INFORMATION' => '',
    'LBL_ADDRESS_POSTALCODE' => '',
    'LBL_ADDRESS_STATE' => '',
    'LBL_ADDRESS_STREET' => '',

    'LBL_DATE_MODIFIED' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DELETED' => '',

    'LBL_BUTTON_SELECT' => '',
    'LBL_BUTTON_CLEAR' => '',

    'LBL_CONTACTS_SYNC' => '',
    'LBL_OAUTH_TOKENS' => '',
    'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE' => '',
    'LBL_PROJECT_CONTACTS_1_FROM_CONTACTS_TITLE' => '',
    'LBL_ROLES' => '',
    'LBL_SECURITYGROUPS' => '',
    'LBL_PROSPECT_LIST' => '',

    'LBL_FACTOR_AUTH_INTERFACE' => '',
    'LBL_EDITOR_TYPE' => '',
);
