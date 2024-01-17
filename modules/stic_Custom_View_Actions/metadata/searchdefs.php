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

$module_name = 'stic_Custom_View_Actions';
$searchdefs [$module_name] = 
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
      'action_order' => 
      array (
        'type' => 'int',
        'label' => 'LBL_ACTION_ORDER',
        'width' => '10%',
        'default' => true,
        'name' => 'action_order',
      ),
      'action_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ACTION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'action_type',
      ),
      'field' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_FIELD',
        'width' => '10%',
        'default' => true,
        'name' => 'field',
      ),
      'field_change_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_FIELD_CHANGE_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'field_change_type',
      ),
      'panel' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PANEL',
        'width' => '10%',
        'default' => true,
        'name' => 'panel',
      ),
      'panel_change_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PANEL_CHANGE_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'panel_change_type',
      ),
      'value' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_VALUE',
        'width' => '10%',
        'default' => true,
        'name' => 'value',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
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
      'action_order' => 
      array (
        'type' => 'int',
        'label' => 'LBL_ACTION_ORDER',
        'width' => '10%',
        'default' => true,
        'name' => 'action_order',
      ),
      'action_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ACTION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'action_type',
      ),
      'field_change_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_FIELD_CHANGE_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'field_change_type',
      ),
      'panel' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PANEL',
        'width' => '10%',
        'default' => true,
        'name' => 'panel',
      ),
      'panel_change_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PANEL_CHANGE_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'panel_change_type',
      ),
      'value' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_VALUE',
        'width' => '10%',
        'default' => true,
        'name' => 'value',
      ),
      'field' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_FIELD',
        'width' => '10%',
        'default' => true,
        'name' => 'field',
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
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'stic_custom_view_customizations_stic_custom_view_actions_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEW_ACTIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
        'id' => 'STIC_CUSTO077EZATIONS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'stic_custom_view_customizations_stic_custom_view_actions_name',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'current_user_only',
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
;
?>
