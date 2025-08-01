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

$dictionary['stic_Payment_Commitments'] = array(
    'table' => 'stic_payment_commitments',
    'audited' => 1,
    'inline_edit' => 1,
    'duplicate_merge' => 1,
    'fields' => array(
        'payment_type' => array(
            'required' => 1,
            'name' => 'payment_type',
            'vname' => 'LBL_PAYMENT_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_types_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'bank_account' => array(
            'required' => 0,
            'name' => 'bank_account',
            'vname' => 'LBL_BANK_ACCOUNT',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 1,
            'len' => '255',
            'size' => '20',
            'inline_edit' => 1,
            'validationType' => true,
        ),
        'channel' => array(
            'required' => 0,
            'name' => 'channel',
            'vname' => 'LBL_CHANNEL',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_campaign_channels_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'periodicity' => array(
            'required' => 1,
            'name' => 'periodicity',
            'vname' => 'LBL_PERIODICITY',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_periodicities_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'amount' => array(
            'required' => 1,
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'inline_edit' => 1,
        ),
        'first_payment_date' => array(
            'required' => 1,
            'name' => 'first_payment_date',
            'vname' => 'LBL_FIRST_PAYMENT_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => 0,
            'validation' => array('type' => 'isbefore', 'compareto' => 'end_date', 'blank' => 0),
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
            'inline_edit' => 1,
            'display_default' => 'now',
        ),
        'end_date' => array(
            'required' => 0,
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
            'inline_edit' => 1,
        ),
        'payment_method' => array(
            'required' => 1,
            'name' => 'payment_method',
            'vname' => 'LBL_PAYMENT_METHOD',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'required',
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_methods_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'transaction_type' => array(
            'required' => 0,
            'name' => 'transaction_type',
            'vname' => 'LBL_TRANSACTION_TYPE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_transaction_types_list',
            'studio' => 'visible',
            'dependency' => 0,
            'inline_edit' => 1,
        ),
        'signature_date' => array(
            'required' => 0,
            'name' => 'signature_date',
            'vname' => 'LBL_SIGNATURE_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'date',
            'massupdate' => 0, // dangerous
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'size' => '20',
            'options' => 'date_range_search_dom',
            'enable_range_search' => 1,
            'inline_edit' => 1,
        ),
        'mandate' => array(
            'required' => 0,
            'name' => 'mandate',
            'vname' => 'LBL_MANDATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 1,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
        ),
        'annualized_fee' => array(
            'required' => 0,
            'name' => 'annualized_fee',
            'vname' => 'LBL_ANNUALIZED_FEE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0, // autocalc
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_ANNUALIZED_FEE_INFO',
        ),
        'pending_annualized_fee' => array(
            'required' => 0,
            'name' => 'pending_annualized_fee',
            'vname' => 'LBL_PENDING_ANNUALIZED_FEE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'audited' => 0,
            'inline_edit' => 0, // autocalc
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_PENDING_ANNUALIZED_FEE_INFO',
        ),
        'paid_annualized_fee' => array(
            'required' => 0,
            'name' => 'paid_annualized_fee',
            'vname' => 'LBL_PAID_ANNUALIZED_FEE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'decimal',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'audited' => 0,
            'inline_edit' => 0, // autocalc
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 26,
            'size' => '20',
            'options' => 'numeric_range_search_dom',
            'enable_range_search' => 1,
            'precision' => 2,
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_PAID_ANNUALIZED_FEE_INFO',
        ),
        'expected_payments_detail' => array(
            'required' => 0,
            'name' => 'expected_payments_detail',
            'vname' => 'LBL_EXPECTED_PAYMENTS_DETAIL',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
        ),
        'segmentation' => array(
            'required' => 0,
            'name' => 'segmentation',
            'vname' => 'LBL_SEGMENTATION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_custom_sorting_list',
            'studio' => 'visible',
            'dependency' => 0,
        ),
        'in_kind_donation' => array(
            'required' => 0,
            'name' => 'in_kind_donation',
            'vname' => 'LBL_IN_KIND_DONATION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
        ),
        'banking_concept' => array(
            'required' => 0,
            'name' => 'banking_concept',
            'vname' => 'LBL_BANKING_CONCEPT',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
        ),
        'destination' => array(
            'required' => 0,
            'name' => 'destination',
            'vname' => 'LBL_DESTINATION',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'enum',
            'massupdate' => 1,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 1,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => 100,
            'size' => '20',
            'options' => 'stic_payments_targets_list',
            'studio' => 'visible',
            'dependency' => 0,
        ),
        'active' => array(
            'required' => false,
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'no_default' => false,
            'massupdate' => 0,
            'inline_edit' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '2',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'enabled',
            'popupHelp' => 'LBL_ACTIVE_HELP',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
        ),
        'redsys_ds_merchant_identifier' => array(
            'required' => 0,
            'name' => 'redsys_ds_merchant_identifier',
            'vname' => 'LBL_REDSYS_DS_MERCHANT_IDENTIFIER',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_REDSYS_DS_MERCHANT_IDENTIFIER_INFO',
        ),
        'redsys_ds_merchant_cof_txnid' => array(
            'required' => 0,
            'name' => 'redsys_ds_merchant_cof_txnid',
            'vname' => 'LBL_REDSYS_DS_MERCHANT_COF_TXNID',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '255',
            'size' => '20',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_REDSYS_DS_MERCHANT_COF_TXNID_INFO',
        ),
        'card_expiry_date' => array(
            'required' => 0,
            'name' => 'card_expiry_date',
            'vname' => 'LBL_CARD_EXPIRY_DATE',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '4',
            'size' => '4',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
        ),
        'paypal_subscr_id' => array(
            'required' => 0,
            'name' => 'paypal_subscr_id',
            'vname' => 'LBL_PAYPAL_SUBSCR_ID',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '30',
            'size' => '30',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_PAYPAL_SUBSCR_ID_INFO',
        ),
        'stripe_subscr_id' => array(
            'required' => 0,
            'name' => 'stripe_subscr_id',
            'vname' => 'LBL_STRIPE_SUBSCR_ID',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 1,
            'audited' => 0,
            'inline_edit' => 0,
            'reportable' => 1,
            'unified_search' => 0,
            'len' => '30',
            'size' => '30',
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_STRIPE_SUBSCR_ID_INFO',
        ),
        'gateway_log' => array(
            'required' => 0,
            'name' => 'gateway_log',
            'vname' => 'LBL_GATEWAY_LOG',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'enabled',
            'type' => 'text',
            'massupdate' => 0,
            'no_default' => 0,
            'comment' => '',
            'help' => '',
            'importable' => 0,
            'audited' => 0,
            'inline_edit' => 0,
            'rows' => '2',
            'cols' => 80,
            'unified_search' => 0,
            'studio' => array(
                'quickcreate' => false,
                'editview' => false,
            ),
            'popupHelp' => 'LBL_GATEWAY_LOG_INFO',
        ),
        'stic_payment_commitments_accounts' => array(
            'name' => 'stic_payment_commitments_accounts',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_accounts',
            'source' => 'non-db',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'id_name' => 'stic_payment_commitments_accountsaccounts_ida',

        ),
        'stic_payment_commitments_accounts_name' => array(
            'name' => 'stic_payment_commitments_accounts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_accountsaccounts_ida',
            'link' => 'stic_payment_commitments_accounts',
            'table' => 'accounts',
            'module' => 'Accounts',
            'rname' => 'name',
            'inline_edit' => 1,
            'massupdate' => 0, // unusual
        ),
        'stic_payment_commitments_accountsaccounts_ida' => array(
            'name' => 'stic_payment_commitments_accountsaccounts_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_accounts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payment_commitments_accounts_1' => array(
            'name' => 'stic_payment_commitments_accounts_1',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_accounts_1',
            'source' => 'non-db',
            'module' => 'Accounts',
            'bean_name' => 'Account',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
            'id_name' => 'stic_payment_commitments_accounts_1accounts_ida',

        ),
        'stic_payment_commitments_accounts_1_name' => array(
            'name' => 'stic_payment_commitments_accounts_1_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_accounts_1accounts_ida',
            'link' => 'stic_payment_commitments_accounts_1',
            'table' => 'accounts',
            'module' => 'Accounts',
            'rname' => 'name',
            'inline_edit' => 1,
            'massupdate' => 0, // unusual
        ),
        'stic_payment_commitments_accounts_1accounts_ida' => array(
            'name' => 'stic_payment_commitments_accounts_1accounts_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_accounts_1',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payment_commitments_campaigns' => array(
            'name' => 'stic_payment_commitments_campaigns',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_campaigns',
            'source' => 'non-db',
            'module' => 'Campaigns',
            'bean_name' => 'Campaign',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
            'id_name' => 'stic_payment_commitments_campaignscampaigns_ida',
        ),
        'stic_payment_commitments_campaigns_name' => array(
            'name' => 'stic_payment_commitments_campaigns_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_campaignscampaigns_ida',
            'link' => 'stic_payment_commitments_campaigns',
            'table' => 'campaigns',
            'module' => 'Campaigns',
            'rname' => 'name',
            'massupdate' => 1,
            'inline_edit' => 1,
        ),
        'stic_payment_commitments_campaignscampaigns_ida' => array(
            'name' => 'stic_payment_commitments_campaignscampaigns_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_campaigns',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payment_commitments_contacts' => array(
            'name' => 'stic_payment_commitments_contacts',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_contacts',
            'source' => 'non-db',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_FROM_CONTACTS_TITLE',
            'id_name' => 'stic_payment_commitments_contactscontacts_ida',
        ),
        'stic_payment_commitments_contacts_name' => array(
            'name' => 'stic_payment_commitments_contacts_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_FROM_CONTACTS_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_contactscontacts_ida',
            'link' => 'stic_payment_commitments_contacts',
            'table' => 'contacts',
            'module' => 'Contacts',
            'inline_edit' => 1,
            'massupdate' => 0, // unusual
            'rname' => 'name',
            'db_concat_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
            ),
        ),
        'stic_payment_commitments_contactscontacts_ida' => array(
            'name' => 'stic_payment_commitments_contactscontacts_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_contacts',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payment_commitments_contacts_1' => array(
            'name' => 'stic_payment_commitments_contacts_1',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_contacts_1',
            'source' => 'non-db',
            'module' => 'Contacts',
            'bean_name' => 'Contact',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_1_FROM_CONTACTS_TITLE',
            'id_name' => 'stic_payment_commitments_contacts_1contacts_ida',
        ),
        'stic_payment_commitments_contacts_1_name' => array(
            'name' => 'stic_payment_commitments_contacts_1_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_1_FROM_CONTACTS_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_contacts_1contacts_ida',
            'link' => 'stic_payment_commitments_contacts_1',
            'table' => 'contacts',
            'module' => 'Contacts',
            'inline_edit' => 1,
            'massupdate' => 0, // unusual
            'rname' => 'name',
            'db_concat_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
            ),
        ),
        'stic_payment_commitments_contacts_1contacts_ida' => array(
            'name' => 'stic_payment_commitments_contacts_1contacts_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_contacts_1',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_1_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payment_commitments_project' => array(
            'name' => 'stic_payment_commitments_project',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_project',
            'source' => 'non-db',
            'module' => 'Project',
            'bean_name' => 'Project',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_PROJECT_FROM_PROJECT_TITLE',
            'id_name' => 'stic_payment_commitments_projectproject_ida',
        ),
        'stic_payment_commitments_project_name' => array(
            'name' => 'stic_payment_commitments_project_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_PROJECT_FROM_PROJECT_TITLE',
            'save' => true,
            'id_name' => 'stic_payment_commitments_projectproject_ida',
            'link' => 'stic_payment_commitments_project',
            'table' => 'project',
            'module' => 'Project',
            'rname' => 'name',
            'massupdate' => 1,
            'inline_edit' => 1,
        ),
        'stic_payment_commitments_projectproject_ida' => array(
            'name' => 'stic_payment_commitments_projectproject_ida',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_project',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_PROJECT_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
        ),
        'stic_payments_stic_payment_commitments' => array(
            'name' => 'stic_payments_stic_payment_commitments',
            'type' => 'link',
            'relationship' => 'stic_payments_stic_payment_commitments',
            'source' => 'non-db',
            'module' => 'stic_Payments',
            'bean_name' => 'stic_Payments',
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENTS_STIC_PAYMENT_COMMITMENTS_FROM_STIC_PAYMENTS_TITLE',
        ),
        'stic_payment_commitments_stic_registrations' => array(
            'name' => 'stic_payment_commitments_stic_registrations',
            'type' => 'link',
            'relationship' => 'stic_payment_commitments_stic_registrations',
            'source' => 'non-db',
            'module' => 'stic_Registrations',
            'bean_name' => 'stic_Registrations',
            'side' => 'right',
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_REGISTRATIONS_FROM_STIC_REGISTRATIONS_TITLE',
        ),
        'stic_bookings_stic_payment_commitments' => array(
            'name' => 'stic_bookings_stic_payment_commitments',
            'type' => 'link',
            'relationship' => 'stic_bookings_stic_payment_commitments',
            'source' => 'non-db',
            'module' => 'stic_Bookings',
            'bean_name' => 'stic_Bookings',
            'vname' => 'LBL_STIC_BOOKINGS_STIC_PAYMENT_COMMITMENTS_FROM_STIC_BOOKINGS_TITLE',
            'id_name' => 'stic_bookings_stic_payment_commitmentsstic_bookings_ida',
        ),
        'stic_bookings_stic_payment_commitments_name' => array(
            'name' => 'stic_bookings_stic_payment_commitments_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_STIC_BOOKINGS_STIC_PAYMENT_COMMITMENTS_FROM_STIC_BOOKINGS_TITLE',
            'save' => true,
            'id_name' => 'stic_bookings_stic_payment_commitmentsstic_bookings_ida',
            'link' => 'stic_bookings_stic_payment_commitments',
            'table' => 'stic_bookings',
            'module' => 'stic_Bookings',
            'rname' => 'name',
        ),
        'stic_bookings_stic_payment_commitmentsstic_bookings_ida' => array(
            'name' => 'stic_bookings_stic_payment_commitmentsstic_bookings_ida',
            'type' => 'link',
            'relationship' => 'stic_bookings_stic_payment_commitments',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'left',
            'vname' => 'LBL_STIC_BOOKINGS_STIC_PAYMENT_COMMITMENTS_FROM_STIC_BOOKINGS_TITLE',
        ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => 1,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
);
if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Payment_Commitments', 'stic_Payment_Commitments', array('basic', 'assignable', 'security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Payment_Commitments']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Payment_Commitments']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Payment_Commitments']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

// Kreporter fields are added to display the payment forecast for each of the next 12 months in an itemized manner,
// using the information contained in the expected_payments_detail field.
$i = 1;

while ($i <= 12) {
    // fix length for first position
    $positions = $i == 1 ? 1 : 2;

    $mainQuery = "CAST(SUBSTRING(SUBSTRING_INDEX(expected_payments_detail, '|', {$i}), LENGTH(SUBSTRING_INDEX(expected_payments_detail, '|', {$i} - 1)) + {$positions}) AS DECIMAL(10,2))";

    $dictionary['stic_Payment_Commitments']['fields']['expected_payments_month_' . $i] = array(
        'name' => 'expected_payments_month_' . $i,
        'vname' => 'LBL_EXPECTED_PAYMENTS_MONTH_' . $i,
        'type' => 'kreporter',
        'source' => 'non-db',
        'kreporttype' => 'decimal',
        'evalSQLFunction' => 'X', // This strange parameter is required by https://github.com/SinergiaTIC/SinergiaCRM/blob/master/modules/KReports/KReportQuery.php#L1230 so that, on this field of type 'kreporter', the aggregations are applied in case they are used in the report.
        'eval' => array(
            'presentation' => array(
                'eval' => $mainQuery,
            ),
            'selection' => array(
                'contains' => '(' . $mainQuery . ') LIKE \'%{p1}%\'',
                'notcontains' => '(' . $mainQuery . ') NOT LIKE \'%{p1}%\'',
                'equals' => '(' . $mainQuery . ') = \'{p1}\'',
                'notequal' => '(' . $mainQuery . ') <> \'{p1}\'',
                'starts' => '(' . $mainQuery . ') LIKE \'{p1}%\'',
                'notstarts' => '(' . $mainQuery . ') NOT LIKE \'{p1}%\'',
                'isnull' => '(' . $mainQuery . ') IS NULL',
                'isempty' => '(' . $mainQuery . ') = \'\'',
                'isemptyornull' => '(' . $mainQuery . ') = \'\' OR (' . $mainQuery . ') IS NULL',
                'isnotempty' => '(' . $mainQuery . ') <> \'\' AND (' . $mainQuery . ') IS NOT NULL',
            ),
        ),
    );
    $i++;
}
