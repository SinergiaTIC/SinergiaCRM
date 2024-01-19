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

$module_name = 'stic_Custom_View_Conditions';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CONDITION_ORDER' => 
  array (
    'type' => 'int',
    'label' => 'LBL_CONDITION_ORDER',
    'width' => '10%',
    'default' => true,
  ),
  'CONDITION_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CONDITION_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'FIELD' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_FIELD',
    'width' => '10%',
    'default' => true,
  ),
  'OPERATOR' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_OPERATOR',
    'width' => '10%',
    'default' => true,
  ),
  'VALUE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_VALUE',
    'width' => '10%',
    'default' => true,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEW_CONDITIONS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_STIC_CUSTOM_VIEW_CONDITIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
    'id' => 'STIC_CUSTO233DZATIONS_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
