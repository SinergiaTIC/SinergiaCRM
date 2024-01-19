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
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'action_order',
            'label' => 'LBL_ACTION_ORDER',
          ),
          1 => 
          array (
            'name' => 'action_type',
            'studio' => 'visible',
            'label' => 'LBL_ACTION_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'field',
            'studio' => 'visible',
            'label' => 'LBL_FIELD',
          ),
          1 => 
          array (
            'name' => 'field_change_type',
            'studio' => 'visible',
            'label' => 'LBL_FIELD_CHANGE_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'panel',
            'studio' => 'visible',
            'label' => 'LBL_PANEL',
          ),
          1 => 
          array (
            'name' => 'panel_change_type',
            'studio' => 'visible',
            'label' => 'LBL_PANEL_CHANGE_TYPE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'value',
            'label' => 'LBL_VALUE',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
