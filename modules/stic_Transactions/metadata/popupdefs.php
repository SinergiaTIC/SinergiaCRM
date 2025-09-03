<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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

$popupMeta = array(
    'moduleMain' => 'stic_Transactions',
    'varName' => 'stic_Transactions',
    'orderBy' => 'stic_transactions.name',
    'whereClauses' => array(
        'document_name' => 'stic_transactions.document_name',
        'transaction_date' => 'stic_transactions.transaction_date',
        'status' => 'stic_transactions.status',
        'category' => 'stic_transactions.category',
        'transaction_type' => 'stic_transactions.transaction_type',
        'subcategory' => 'stic_transactions.subcategory',
        'stic_transactions_stic_financial_products_name' => 'stic_transactions.stic_transactions_stic_financial_products_name',
        'payment_method' => 'stic_transactions.payment_method',
        'assigned_user_name' => 'stic_transactions.assigned_user_name',
    ),
    'searchInputs' => array(
        3 => 'status',
        4 => 'document_name',
        5 => 'transaction_date',
        6 => 'category',
        7 => 'transaction_type',
        8 => 'subcategory',
        9 => 'stic_transactions_stic_financial_products_name',
        10 => 'payment_method',
        14 => 'assigned_user_name',
    ),
    'searchdefs' => array(
        'document_name' =>
        array(
            'name' => 'document_name',
            'width' => '10%',
        ),
        'transaction_date' =>
        array(
            'type' => 'date',
            'label' => 'LBL_TRANSACTION_DATE',
            'width' => '10%',
            'name' => 'transaction_date',
        ),
        'status' =>
        array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
        ),
        'category' =>
        array(
            'type' => 'dynamicenum',
            'studio' => 'visible',
            'label' => 'LBL_CATEGORY',
            'width' => '10%',
            'name' => 'category',
        ),
        'transaction_type' =>
        array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_TYPE',
            'width' => '10%',
            'name' => 'transaction_type',
        ),
        'subcategory' =>
        array(
            'type' => 'dynamicenum',
            'studio' => 'visible',
            'label' => 'LBL_SUBCATEGORY',
            'width' => '10%',
            'name' => 'subcategory',
        ),
        'stic_transactions_stic_financial_products_name' =>
        array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
            'id' => 'STIC_TRANS4A5BRODUCTS_IDA',
            'width' => '10%',
            'name' => 'stic_transactions_stic_financial_products_name',
        ),
        'payment_method' =>
        array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_METHOD',
            'width' => '10%',
            'name' => 'payment_method',
        ),
        'assigned_user_name' =>
        array(
            'link' => true,
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'name' => 'assigned_user_name',
        ),
    ),
);
