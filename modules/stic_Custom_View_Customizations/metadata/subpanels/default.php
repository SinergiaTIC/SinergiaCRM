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

$module_name='stic_Custom_View_Customizations';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'customization_name' => 
    array (
      'type' => 'text',
      'vname' => 'LBL_CUSTOMIZATION_NAME',
      //'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
    ),
    'is_initial' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_IS_INITIAL',
      'width' => '10%',
    ),
    'summary' => 
    array (
      'type' => 'text',
      'vname' => 'LBL_SUMMARY',
      'width' => '10%',
      'default' => true,
    ),
    'description' => 
    array (
      'type' => 'text',
      'vname' => 'LBL_DESCRIPTION',
      'sortable' => false,
      'width' => '10%',
      'default' => true,
    ),
    'date_modified' => 
    array (
      'vname' => 'LBL_DATE_MODIFIED',
      'width' => '45%',
      'default' => true,
    ),
    'quick_edit_button' => array(
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelSticQuickEditButton',
      'module' => 'stic_Custom_View_Customizations',
      'width' => '5%',
      'default' => true,
    ),
    // 'edit_button' =>
    // array (
    //   'vname' => 'LBL_EDIT_BUTTON',
    //   'widget_class' => 'SubPanelEditButton',
    //   'module' => 'stic_Custom_View_Customizations',
    //   'width' => '5%',
    //   'default' => true,
    // ),
    'remove_button' => 
    array (
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'stic_Custom_View_Customizations',
      'width' => '5%',
      'default' => true,
    ),
  ),
);