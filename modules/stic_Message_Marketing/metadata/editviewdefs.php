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
$module_name = 'stic_Message_Marketing';
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_default_panel' => 
      array (
        0 => array(
          0 => array('name' => 'campaigns_stic_message_marketing_name'),
          1 => 'type',
        ),
        1 => 
        array (
          0 => 'name',
          1 => 'status',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'sender',
          ),
          1 => 
          array (
            'name' => 'template_id',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'start_date_time',
          ),
          1 => 
          array (
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'select_all',
          ),
          1 => 
          array (
            'name' => 'prospect_lists',
            'label' => 'LBL_PROSPECT_LISTS_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
