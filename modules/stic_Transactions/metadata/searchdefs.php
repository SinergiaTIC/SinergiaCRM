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

$module_name = 'stic_Transactions';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'document_name' => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      'transaction_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_TRANSACTION_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'transaction_date',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'category' => 
      array (
        'type' => 'dynamicenum',
        'studio' => 'visible',
        'label' => 'LBL_CATEGORY',
        'width' => '10%',
        'default' => true,
        'name' => 'category',
      ),
      'transaction_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TRANSACTION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'transaction_type',
      ),
      'stic_transactions_stic_financial_products_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
        'id' => 'STIC_TRANS4A5BRODUCTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'stic_transactions_stic_financial_products_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => array(
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'document_name' => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      'transaction_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_TRANSACTION_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'transaction_date',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'category' => 
      array (
        'type' => 'dynamicenum',
        'studio' => 'visible',
        'label' => 'LBL_CATEGORY',
        'width' => '10%',
        'default' => true,
        'name' => 'category',
      ),
      'subcategory' => 
      array (
        'type' => 'dynamicenum',
        'studio' => 'visible',
        'label' => 'LBL_SUBCATEGORY',
        'width' => '10%',
        'default' => true,
        'name' => 'subcategory',
      ),
      'transaction_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TRANSACTION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'transaction_type',
      ),
      'stic_transactions_stic_financial_products_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'STIC_TRANS4A5BRODUCTS_IDA',
        'name' => 'stic_transactions_stic_financial_products_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'payment_method' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_METHOD',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_method',
      ),
      'amount' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'amount',
      ),
      'destination_account' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DESTINATION_ACCOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'destination_account',
      ),
      'accounting_account' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ACCOUNTING_ACCOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'accounting_account',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'current_user_only',
      ),
      'favorites_only' => array(
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
