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
$module_name='stic_Medication_Log';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'stic_Medication_Log',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
    ),
    'intake_date' => 
    array (
      'type' => 'date',
      'vname' => 'LBL_INTAKE_DATE',
      'width' => '10%',
      'default' => true,
    ),
    'administered' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_ADMINISTERED',
      'width' => '10%',
    ),
    'time' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_TIME',
      'width' => '10%',
      'default' => true,
    ),
    'date_modified' => 
    array (
      'vname' => 'LBL_DATE_MODIFIED',
      'width' => '45%',
      'default' => true,
    ),
    'quickedit_button' => 
    array (
      'vname' => 'LBL_QUICKEDIT_BUTTON',
      'widget_class' => 'SubPanelQuickEditButton',
      'module' => 'stic_Medication_Log',
      'width' => '4%',
      'default' => true,
    ),
    'edit_button' => 
    array (
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelEditButton',
      'module' => 'stic_Medication_Log',
      'width' => '4%',
      'default' => false,
    ),
    // 'remove_button' => 
    // array (
    //   'vname' => 'LBL_REMOVE',
    //   'widget_class' => 'SubPanelRemoveButton',
    //   'module' => 'stic_Medication_Log',
    //   'width' => '5%',
    //   'default' => true,
    // ),
  ),
);