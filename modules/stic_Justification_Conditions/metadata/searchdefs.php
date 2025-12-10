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
$module_name = 'stic_Justification_Conditions';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'opportunities_stic_justification_conditions_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE',
        'id' => 'OPPORTUNIT378FUNITIES_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'opportunities_stic_justification_conditions_name',
      ),
      'ledger_group' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_GROUP',
        'width' => '10%',
        'default' => true,
        'name' => 'ledger_group',
      ),
      'subgroup' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_SUBGROUP',
        'width' => '10%',
        'default' => true,
        'name' => 'subgroup',
      ),
      'account' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_ACCOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'account',
      ),
      'active' => 
      array (
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'active',
      ),
      'allocation_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'width' => '10%',
        'name' => 'allocation_type',
      ),
      'max_allocable_percentage' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_MAX_ALLOCABLE_PERCENTAGE',
        'width' => '10%',
        'default' => true,
        'name' => 'max_allocable_percentage',
      ),
      'max_allocable_percentage_grant' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_MAX_ALLOCABLE_PERCENTAGE_GRANT',
        'width' => '10%',
        'default' => true,
        'name' => 'max_allocable_percentage_grant',
      ),
      'justified_percentage' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_JUSTIFIED_PERCENTAGE',
        'width' => '10%',
        'default' => true,
        'name' => 'justified_percentage',
      ),
      'justified_amount' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_JUSTIFIED_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'justified_amount',
      ),
      'assigned_user_id' => array(
          'name' => 'assigned_user_id',
          'label' => 'LBL_ASSIGNED_TO',
          'type' => 'enum',
          'function' => array(
              'name' => 'get_user_array',
              'params' => array(
                  0 => false,
              ),
          ),
          'width' => '10%',
          'default' => true,
      ),
      'current_user_only' => array(
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
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'opportunities_stic_justification_conditions_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE',
        'id' => 'OPPORTUNIT378FUNITIES_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'opportunities_stic_justification_conditions_name',
      ),
      'ledger_group' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_GROUP',
        'width' => '10%',
        'default' => true,
        'name' => 'ledger_group',
      ),
      'subgroup' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_SUBGROUP',
        'width' => '10%',
        'default' => true,
        'name' => 'subgroup',
      ),
      'account' =>
      array (
        'type' => 'enum',
        'label' => 'LBL_ACCOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'account',
      ),
      'active' => 
      array (
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'active',
      ),
      'allocation_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'width' => '10%',
        'name' => 'allocation_type',
      ),
      'max_allocable_percentage' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_MAX_ALLOCABLE_PERCENTAGE',
        'width' => '10%',
        'default' => true,
        'name' => 'max_allocable_percentage',
      ),
      'max_allocable_percentage_grant' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_MAX_ALLOCABLE_PERCENTAGE_GRANT',
        'width' => '10%',
        'default' => true,
        'name' => 'max_allocable_percentage_grant',
      ),
      'justified_percentage' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_JUSTIFIED_PERCENTAGE',
        'width' => '10%',
        'default' => true,
        'name' => 'justified_percentage',
      ),
      'justified_amount' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_JUSTIFIED_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'justified_amount',
      ),
      'blocked' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_BLOCKED',
        'width' => '10%',
        'default' => true,
        'name' => 'blocked',
      ),
      'max_allocable_amount_grant' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_MAX_ALLOCABLE_AMOUNT_GRANT',
        'width' => '10%',
        'default' => true,
        'name' => 'max_allocable_amount_grant',
      ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'LBL_DESCRIPTION',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'description',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'modified_user_id' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'created_by' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),
            'current_user_only' => array(
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