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
    'LBL_ASSIGNED_TO_ID' => '',
    'LBL_ASSIGNED_TO_NAME' => '',
    'LBL_ASSIGNED_TO' => '',
    'LBL_LIST_ASSIGNED_TO_NAME' => '',
    'LBL_LIST_ASSIGNED_USER' => '',
    'LBL_CREATED' => '',
    'LBL_CREATED_USER' => '',
    'LBL_CREATED_ID' => '',
    'LBL_MODIFIED' => '',
    'LBL_MODIFIED_NAME' => '',
    'LBL_MODIFIED_USER' => '',
    'LBL_MODIFIED_ID' => '',
    'LBL_SECURITYGROUPS' => '',
    'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => '',
    'LBL_ID' => '',
    'LBL_DATE_ENTERED' => '',
    'LBL_DATE_MODIFIED' => '',
    'LBL_DESCRIPTION' => '',
    'LBL_DELETED' => '',
    'LBL_NAME' => '',
    'LBL_LIST_NAME' => '',
    'LBL_EDIT_BUTTON' => '',
    'LBL_REMOVE' => '',

    'ERR_DELETE_RECORD' => '',
    'LBL_ACCOUNT_NAME' => '',
    'LBL_ACCOUNT' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_ADDRESS_INFORMATION' => '',
    'LBL_ANNUAL_REVENUE' => '',
    'LBL_ANY_ADDRESS' => '',
    'LBL_ANY_EMAIL' => '',
    'LBL_ANY_PHONE' => '',
    'LBL_RATING' => '',
    'LBL_ASSIGNED_USER' => '',
    'LBL_BILLING_ADDRESS_CITY' => '',
    'LBL_BILLING_ADDRESS_COUNTRY' => '',
    'LBL_BILLING_ADDRESS_POSTALCODE' => '',
    'LBL_BILLING_ADDRESS_STATE' => '',
    'LBL_BILLING_ADDRESS_STREET_2' => '',
    'LBL_BILLING_ADDRESS_STREET_3' => '',
    'LBL_BILLING_ADDRESS_STREET_4' => '',
    'LBL_BILLING_ADDRESS_STREET' => '',
    'LBL_BILLING_ADDRESS' => '',
    'LBL_ACCOUNT_INFORMATION' => '',
    'LBL_CITY' => '',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '',
    'LBL_COUNTRY' => '',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '',
    'LBL_DUPLICATE' => '',
    'LBL_EMAIL' => '',
    'LBL_EMPLOYEES' => '',
    'LBL_FAX' => '',
    'LBL_INDUSTRY' => '',
    'LBL_LIST_ACCOUNT_NAME' => '',
    'LBL_LIST_CITY' => '',
    'LBL_LIST_EMAIL_ADDRESS' => '',
    'LBL_LIST_PHONE' => '',
    'LBL_LIST_STATE' => '',
    'LBL_MEMBER_OF' => '',
    'LBL_MEMBER_ORG_SUBPANEL_TITLE' => '',
    'LBL_OTHER_EMAIL_ADDRESS' => '',
    'LBL_OTHER_PHONE' => '',
    'LBL_OWNERSHIP' => '',
    'LBL_PARENT_ACCOUNT_ID' => '',
    'LBL_PHONE_ALT' => '',
    'LBL_PHONE_FAX' => '',
    'LBL_PHONE_OFFICE' => '',
    'LBL_PHONE' => '',
    'LBL_POSTAL_CODE' => '',
    'LBL_SAVE_ACCOUNT' => '',
    'LBL_SHIPPING_ADDRESS_CITY' => '',
    'LBL_SHIPPING_ADDRESS_COUNTRY' => '',
    'LBL_SHIPPING_ADDRESS_POSTALCODE' => '',
    'LBL_SHIPPING_ADDRESS_STATE' => '',
    'LBL_SHIPPING_ADDRESS_STREET_2' => '',
    'LBL_SHIPPING_ADDRESS_STREET_3' => '',
    'LBL_SHIPPING_ADDRESS_STREET_4' => '',
    'LBL_SHIPPING_ADDRESS_STREET' => '',
    'LBL_SHIPPING_ADDRESS' => '',
    'LBL_STATE' => '',
    'LBL_TICKER_SYMBOL' => '',
    'LBL_TYPE' => '',
    'LBL_WEBSITE' => '',
    'LNK_ACCOUNT_LIST' => '',
    'LNK_NEW_ACCOUNT' => '',
    'MSG_DUPLICATE' => '',
    'MSG_SHOW_DUPLICATES' => '',
    'NTC_DELETE_CONFIRMATION' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    'LNK_NEW_RECORD' => '',
    'LNK_LIST' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_TERMS_C' => '',
    'LBL_APPROVAL_ISSUE' => '',
    'LBL_APPROVAL_STATUS' => '',
    'LBL_BILLING_ACCOUNT' => '',
    'LBL_BILLING_CONTACT' => '',
    'LBL_EXPIRATION' => '',
    'LBL_QUOTE_NUMBER' => '',
    'LBL_OPPORTUNITY' => '',
    'LBL_TEMPLATE_DDOWN_C' => '',
    'LBL_STAGE' => '',
    'LBL_TERM' => '',
    'LBL_SUBTOTAL_AMOUNT' => '',
    'LBL_DISCOUNT_AMOUNT' => '',
    'LBL_TAX_AMOUNT' => '',
    'LBL_SHIPPING_AMOUNT' => '',
    'LBL_TOTAL_AMT' => '',
    'VALUE' => '',
    'LBL_EMAIL_ADDRESSES' => '',
    'LBL_LINE_ITEMS' => '',
    'LBL_GRAND_TOTAL' => '',
    'LBL_INVOICE_STATUS' => '',
    'LBL_PRODUCT_QUANITY' => '',
    'LBL_PRODUCT_NAME' => '',
    'LBL_PRODUCT_NUMBER' => '', // PR 3296
    'LBL_PART_NUMBER' => '',
    'LBL_PRODUCT_NOTE' => '',
    'LBL_PRODUCT_DESCRIPTION' => '',
    'LBL_LIST_PRICE' => '',
    'LBL_DISCOUNT_AMT' => '',
    'LBL_UNIT_PRICE' => '',
    'LBL_TOTAL_PRICE' => '',
    'LBL_VAT' => '', 
    'LBL_VAT_AMT' => '',
    'LBL_ADD_PRODUCT_LINE' => '',
    'LBL_SERVICE_NAME' => '',
    'LBL_SERVICE_NUMBER' => '', // PR 3296
    'LBL_SERVICE_LIST_PRICE' => '',
    'LBL_SERVICE_PRICE' => '',
    'LBL_SERVICE_DISCOUNT' => '',
    'LBL_ADD_SERVICE_LINE' => '',
    'LBL_REMOVE_PRODUCT_LINE' => '',
    'LBL_CONVERT_TO_INVOICE' => '',
    'LBL_PRINT_AS_PDF' => '',
    'LBL_EMAIL_QUOTE' => '',
    'LBL_CREATE_CONTRACT' => '',
    'LBL_LIST_NUM' => '',
    'LBL_PDF_NAME' => '',
    'LBL_EMAIL_NAME' => '',
    'LBL_QUOTE_DATE' => '',
    'LBL_NO_TEMPLATE' => '',
    'LBL_SUBTOTAL_TAX_AMOUNT' => '',//pre shipping
    'LBL_EMAIL_PDF' => '',
    'LBL_ADD_GROUP' => '',
    'LBL_DELETE_GROUP' => '',
    'LBL_GROUP_NAME' => '',
    'LBL_GROUP_DESCRIPTION' => '', // PR 3296
    'LBL_GROUP_TOTAL' => '',
    'LBL_SHIPPING_TAX' => '',
    'LBL_SHIPPING_TAX_AMT' => '',
    'LBL_IMPORT_LINE_ITEMS' => '',
    'LBL_CREATE_OPPORTUNITY' => '',
    'LBL_SUBTOTAL_AMOUNT_USDOLLAR' => '',
    'LBL_DISCOUNT_AMOUNT_USDOLLAR' => '',
    'LBL_TAX_AMOUNT_USDOLLAR' => '',
    'LBL_SHIPPING_AMOUNT_USDOLLAR' => '',
    'LBL_TOTAL_AMT_USDOLLAR' => '',
    'LBL_SHIPPING_TAX_AMT_USDOLLAR' => '',
    'LBL_GRAND_TOTAL_USDOLLAR' => '',
    'LBL_QUOTE_TO' => '',

    'LBL_SUBTOTAL_TAX_AMOUNT_USDOLLAR' => '',
    'LBL_AOS_QUOTES_AOS_CONTRACTS' => '',
    'LBL_AOS_QUOTES_AOS_INVOICES' => '',
    'LBL_AOS_LINE_ITEM_GROUPS' => '',
    'LBL_AOS_PRODUCT_QUOTES' => '',
    'LBL_AOS_QUOTES_PROJECT' => '',
);
