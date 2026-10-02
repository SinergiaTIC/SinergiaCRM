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

$dashletData['stic_Organizational_EnvironmentDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'stic_organizational_environment_accounts_name' => 
  array (
    'default' => '',
  ),
  'stic_organizational_environment_contacts_name' => 
  array (
    'default' => '',
  ),
  'relationship_type' => 
  array (
    'default' => '',
  ),
  'stic_organizational_environment_accounts_1_name' => 
  array (
    'default' => '',
  ),
  'reference_account' => 
  array (
    'default' => '',
  ),
  'active' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
);
$dashletData['stic_Organizational_EnvironmentDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'stic_organizational_environment_accounts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTSACCOUNTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_organizational_environment_accounts_name',
  ),
  'stic_organizational_environment_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_organizational_environment_contacts_name',
  ),
  'relationship_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RELATIONSHIP_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'relationship_type',
  ),
  'stic_organizational_environment_accounts_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
    'id' => 'STIC_ORGANIZATIONAL_ENVIRONMENT_ACCOUNTS_1ACCOUNTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_organizational_environment_accounts_1_name',
  ),
  'reference_account' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_REFERENCE_ACCOUNT',
    'width' => '10%',
    'name' => 'reference_account',
  ),
  'active' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_ACTIVE',
    'width' => '10%',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'created_by' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
    'name' => 'date_entered',
  ),
  'assigned_user_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
