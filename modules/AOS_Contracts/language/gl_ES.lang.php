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

    'LBL_CONTRACT_ACCOUNT' => '',
    'LBL_OPPORTUNITY' => '',
    'LBL_LIST_FORM_TITLE' => '',
    'LBL_MODULE_NAME' => '',
    'LBL_MODULE_TITLE' => '',
    'LBL_HOMEPAGE_TITLE' => '',
    'LNK_NEW_RECORD' => '',
    'LNK_LIST' => '',
    'LBL_SEARCH_FORM_TITLE' => '',
    'LBL_HISTORY_SUBPANEL_TITLE' => '',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '',
    'LBL_NEW_FORM_TITLE' => '',
    'LBL_CONTRACT_NAME' => '',
    'LBL_REFERENCE_CODE ' => '',
    'LBL_START_DATE' => '',
    'LBL_END_DATE' => '',
    'LBL_TOTAL_CONTRACT_VALUE' => '',
    'LBL_STATUS' => '',
    'LBL_CUSTOMER_SIGNED_DATE' => '',
    'LBL_COMPANY_SIGNED_DATE' => '',
    'LBL_RENEWAL_REMINDER' => '',
    'LBL_RENEWAL_REMINDER_DATE' => '',
    'LBL_CONTRACT_TYPE' => '',
    'LBL_CONTACT' => '',
    'LBL_ADD_GROUP' => '',
    'LBL_DELETE_GROUP' => '',
    'LBL_GROUP_NAME' => '',
    'LBL_GROUP_TOTAL' => '',
    'LBL_PRODUCT_QUANITY' => '',
    'LBL_PRODUCT_NAME' => '',
    'LBL_PART_NUMBER' => '',
    'LBL_PRODUCT_NOTE' => '',
    'LBL_PRODUCT_DESCRIPTION' => '',
    'LBL_LIST_PRICE' => '',
    'LBL_DISCOUNT_AMT' => '',
    'LBL_UNIT_PRICE' => '',
    'LBL_TOTAL_PRICE' => '',
    'LBL_VAT' => '',
    'LBL_VAT_AMT' => '',
    'LBL_SERVICE_NAME' => '',
    'LBL_SERVICE_LIST_PRICE' => '',
    'LBL_SERVICE_PRICE' => '',
    'LBL_SERVICE_DISCOUNT' => '',
    'LBL_LINE_ITEMS' => '',
    'LBL_SUBTOTAL_AMOUNT' => '',
    'LBL_DISCOUNT_AMOUNT' => '',
    'LBL_TAX_AMOUNT' => '',
    'LBL_SHIPPING_AMOUNT' => '',
    'LBL_TOTAL_AMT' => '',
    'LBL_GRAND_TOTAL' => '',
    'LBL_SHIPPING_TAX' => '',
    'LBL_SHIPPING_TAX_AMT' => '',
    'LBL_ADD_PRODUCT_LINE' => '',
    'LBL_ADD_SERVICE_LINE' => '',
    'LBL_PRINT_AS_PDF' => '',
    'LBL_EMAIL_PDF' => '',
    'LBL_PDF_NAME' => '',
    'LBL_EMAIL_NAME' => '',
    'LBL_NO_TEMPLATE' => '',
    'LBL_TOTAL_CONTRACT_VALUE_USDOLLAR' => '',
    'LBL_SUBTOTAL_AMOUNT_USDOLLAR' => '',
    'LBL_DISCOUNT_AMOUNT_USDOLLAR' => '',
    'LBL_TAX_AMOUNT_USDOLLAR' => '',
    'LBL_SHIPPING_AMOUNT_USDOLLAR' => '',
    'LBL_TOTAL_AMT_USDOLLAR' => '',
    'LBL_SHIPPING_TAX_AMT_USDOLLAR' => '',
    'LBL_GRAND_TOTAL_USDOLLAR' => '',

    'LBL_CALL_ID' => '',
    'LBL_AOS_LINE_ITEM_GROUPS' => '',
    'LBL_AOS_PRODUCT_QUOTES' => '',
    'LBL_AOS_QUOTES_AOS_CONTRACTS' => '',
);