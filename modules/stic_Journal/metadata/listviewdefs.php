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

$module_name = 'stic_Journal';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'JOURNAL_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_JOURNAL_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'TURN' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TURN',
    'width' => '10%',
    'default' => true,
  ),
  'STIC_JOURNAL_STIC_CENTERS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_JOURNAL_STIC_CENTERS_FROM_STIC_CENTERS_TITLE',
    'id' => 'STIC_JOURNAL_STIC_CENTERSSTIC_CENTERS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'TASK' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_TASK',
    'width' => '10%',
    'default' => false,
  ),
  'TASK_SCOPE' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_TASK_SCOPE',
    'width' => '10%',
    'default' => false,
  ),
  'TASK_START_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_TASK_START_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'TASK_END_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_TASK_END_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'TASK_FULFILLMENT' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TASK_FULFILLMENT',
    'width' => '10%',
    'default' => false,
  ),
  'TASK_DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_TASK_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'INFRINGEMENT_SERIOUSNESS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_INFRINGEMENT_SERIOUSNESS',
    'width' => '10%',
    'default' => false,
  ),
  'INFRINGEMENT_DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_INFRINGEMENT_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
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
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
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
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
