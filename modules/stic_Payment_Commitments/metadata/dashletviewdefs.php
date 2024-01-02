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

global $current_user;

$dashletData['stic_Payment_CommitmentsDashlet']['searchFields'] = array(
    'name' => array('default' => ''),
    'payment_type' => array('default' => ''),
    'payment_method' => array('default' => ''),
    'amount' => array('default' => ''),
    'first_payment_date' => array('default' => ''),
    'end_date' => array('default' => ''),
    'periodicity' => array('default' => ''),
    // 'stic_payment_commitments_contacts_name' => array('default' => ''),
    // 'stic_payment_commitments_accounts_name' => array('default' => ''),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => $current_user->name,
    ),
);
$dashletData['stic_Payment_CommitmentsDashlet']['columns'] = array(
    'NAME' => array(
        'width' => '25%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'PAYMENT_TYPE' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'PAYMENT_METHOD' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_METHOD',
        'width' => '10%',
        'default' => true,
    ),
    'AMOUNT' => array(
        'type' => 'decimal',
        'align' => 'right',
        'label' => 'LBL_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'PERIODICITY' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PERIODICITY',
        'width' => '10%',
        'default' => false,
    ),
    'FIRST_PAYMENT_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_FIRST_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'STIC_PAYMENT_COMMITMENTS_CONTACTS_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_CONTACTSCONTACTS_IDA',
        'width' => '10%',
        'default' => false,
    ),
    'STIC_PAYMENT_COMMITMENTS_ACCOUNTS_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_ACCOUNTSACCOUNTS_IDA',
        'width' => '10%',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
    ),
    'TRANSACTION_TYPE' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TRANSACTION_TYPE',
        'width' => '10%',
        'default' => false,
    ),
    'STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
        'id' => 'STIC_PAYMENT_COMMITMENTS_CAMPAIGNSCAMPAIGNS_IDA',
        'width' => '10%',
        'default' => false,
    ),
    'BANKING_CONCEPT' => array(
        'type' => 'varchar',
        'label' => 'LBL_BANKING_CONCEPT',
        'width' => '10%',
        'default' => false,
    ),
    'IN_KIND_DONATION' => array(
        'type' => 'varchar',
        'label' => 'LBL_IN_KIND_DONATION',
        'width' => '10%',
        'default' => false,
    ),
    'SEGMENTATION' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'default' => false,
        'label' => 'LBL_SEGMENTATION',
        'width' => '10%',
    ),
    'DESCRIPTION' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'BANK_ACCOUNT' => array(
        'type' => 'varchar',
        'label' => 'LBL_BANK_ACCOUNT',
        'width' => '10%',
        'default' => false,
    ),
    'CHANNEL' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_CHANNEL',
        'width' => '10%',
        'default' => false,
    ),
    'END_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'SIGNATURE_DATE' => array(
        'type' => 'date',
        'label' => 'LBL_SIGNATURE_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'MANDATE' => array(
        'type' => 'varchar',
        'label' => 'LBL_MANDATE',
        'width' => '10%',
        'default' => false,
    ),
    'ANNUALIZED_FEE' => array(
        'type' => 'decimal',
        'align' => 'right',
        'label' => 'LBL_ANNUALIZED_FEE',

        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
);
