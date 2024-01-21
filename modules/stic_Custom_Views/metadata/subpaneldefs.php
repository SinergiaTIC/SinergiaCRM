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

$module_name = 'stic_Custom_Views';
// $layout_defs[$module_name]["subpanel_setup"]['stic_custom_views_stic_custom_view_customizations'] = array (
//     'order' => 10,
//     'module' => 'stic_Custom_View_Customizations',
//     'subpanel_name' => 'default',
//     'sort_order' => 'asc',
//     'sort_by' => 'customization_order',
//     'title_key' => 'LBL_STIC_CUSTOM_VIEWS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
//     'get_subpanel_data' => 'stic_custom_views_stic_custom_view_customizations',
//     'top_buttons' => 
//     array (
//       0 => 
//       array (
//         'widget_class' => 'SubPanelTopButtonQuickCreate',
//       ),
//     ),
// );
$layout_defs[$module_name]["subpanel_setup"]['stic_custom_view_customizations_initial'] = array (
  'order' => 45,
  'module' => 'stic_Custom_View_Customizations',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'customization_order',
  'title_key' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_INITIAL_TITLE',
  'get_subpanel_data' => 'function:stic_custom_views_stic_custom_view_customizations',
  'function_parameters' => array(
    'import_function_file' => 'modules/stic_Custom_Views/Utils.php',
    'is_initial' => 1
  ),
  'top_buttons' => array (
    0 => array (
      //'widget_class' => 'SubPanelTopButtonQuickCreateSticIsInitial',
      'widget_class' => 'SubPanelTopButtonQuickCreate',
      //'widget_class' => 'SubPanelTopButtonQuickCreate2',
    ),
  ),
);
$layout_defs[$module_name]["subpanel_setup"]['stic_custom_view_customizations_dynamic'] = array (
  'order' => 50,
  'module' => 'stic_Custom_View_Customizations',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'customization_order',
  'title_key' => 'LBL_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_DYNAMIC_TITLE',
  'get_subpanel_data' => 'function:stic_custom_views_stic_custom_view_customizations',
  'function_parameters' => array(
    'import_function_file' => 'modules/stic_Custom_Views/Utils.php',
    'is_initial' => 0
  ),
  'top_buttons' => array (
    0 => array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
  ),
);