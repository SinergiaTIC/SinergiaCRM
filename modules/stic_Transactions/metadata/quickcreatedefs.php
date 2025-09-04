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

$module_name = 'stic_Transactions';

$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
        'hidden' => 
        array (
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	{sugar_getscript file="modules/Documents/documents.js"}',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_default_panel' => 
      array (
        0 => 
        array (
          0 => array(
            'name' => 'document_name',
            'type' => 'readonly',
        ),
          1 => 
          array (
            'name' => 'uploadfile',
            'customCode' => '{if $fields.id.value!=""}
            				{assign var="type" value="hidden"}
            		 		{else}
            		 		{assign var="type" value="file"}
            		  		{/if}
            		  		<input name="uploadfile" type = {$type} size="30" maxlength="" onchange="setvalue(this);" value="{$fields.filename.value}">{$fields.filename.value}',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'transaction_date',
            'label' => 'LBL_TRANSACTION_DATE',
          ),
          1 => 'assigned_user_name',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_transactions_stic_financial_products_name',
            'label' => 'LBL_STIC_TRANSACTIONS_STIC_FINANCIAL_PRODUCTS_FROM_STIC_FINANCIAL_PRODUCTS_TITLE',
          ),
          1 => 
          array (
            'name' => 'transaction_type',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'category',
            'studio' => 'visible',
            'label' => 'LBL_CATEGORY',
          ),
          1 => 
          array (
            'name' => 'subcategory',
            'studio' => 'visible',
            'label' => 'LBL_SUBCATEGORY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'payment_method',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_METHOD',
          ),
          1 => 
          array (
            'name' => 'amount',
            'label' => 'LBL_AMOUNT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'destination_account',
            'label' => 'LBL_DESTINATION_ACCOUNT',
          ),
          1 => 
          array (
            'name' => 'accounting_account',
            'label' => 'LBL_ACCOUNTING_ACCOUNT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'displayParams' => 
            array (
              'rows' => 10,
              'cols' => 120,
            ),
          ),
        ),
      ),
    ),
  ),
);
