<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
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
        'type' => 'stic_transactions.type',
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
        7 => 'type',
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
        'type' =>
        array(
            'type' => 'enum',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
            'width' => '10%',
            'name' => 'type',
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
