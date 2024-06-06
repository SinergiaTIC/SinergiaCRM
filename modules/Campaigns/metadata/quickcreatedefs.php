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

// STIC-Custom - JBL - 20240515 - Notify new Opportunities: Campaign new type (Notification)
// https://github.com/SinergiaTIC/SinergiaCRM/pull/44
$viewdefs['Campaigns']['QuickCreate'] = array (
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
    'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
                     {sugar_getscript file="modules/Campaigns/SticEditView.js"}',
    'useTabs' => false,
    'tabDefs' => 
    array (
      'LBL_CAMPAIGN_INFORMATION' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'lbl_campaign_information' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'name',
        ),
        1 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'campaign_type',
          'displayParams' => 
          array (
            'javascript' => 'onchange="type_change();"',
          ),
        ),
        1 => 
        array (
          'name' => 'status',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'start_date',
          'displayParams' => 
          array (
            'required' => false,
            'showFormats' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'end_date',
          'displayParams' => 
          array (
            'showFormats' => true,
          ),
        ),
      ),
      3 => array(
        0 => array(
          // 'name' => 'prospectlists',
          'name' => 'leads',
          'label' => 'LBL_PROSPECT_LIST',
          //'customCode' => '<input type="text" name="select_prospect_list" id="select_prospect_list" value="">',
        ),
        1 => array(
          'name' => 'email_template',
          'label' => 'LBL_EMAIL_TEMPLATE',
          'customCode' => '<input type="text" name="select_email_template" id="select_email_template" value="">',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'content',
          'displayParams' => 
          array (
            'rows' => 2,
            'cols' => 80,
          ),
        ),
      ),
    ),
  ),
);
// END STIC-Custom
