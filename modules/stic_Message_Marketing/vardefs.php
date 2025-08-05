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

$dictionary['stic_Message_Marketing'] = array(
    'table' => 'stic_message_marketing',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
  // 'campaign_id' => array(
  //   'name' => 'campaign_id',
  //   'vname' => 'LBL_CAMPAIGN_ID',
  //   'type' => 'id',
  //   'isnull' => true,
  //   'required'=>false,
  //   ),
    'sender' =>
    array(
      'required' => false,
      'name' => 'sender',
      'vname' => 'LBL_SENDER',
      'type' => 'varchar',
      'massupdate' => 1,
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'inline_edit' => true,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => '150',
      'size' => '20',
    ),
  'start_date_time' => array (
    'required' => true,
    'name' => 'start_date_time',
    'vname' => 'LBL_START_DATE_TIME',
    'type' => 'datetimecombo',
    'massupdate' => '1',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'date_range_search_dom',
    'enable_range_search' => 1,
  ),
  'type' => array(
    'required' => true,
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'massupdate' => '1',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'required',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'stic_messages_type_list',
    'studio' => false,
    'dependency' => false,
  ),
  'status' => array (
    'required' => true,
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'massupdate' => '1',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'email_marketing_status_dom',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'template_id' => array (
    'required' => false,
    'name' => 'template_id',
    'vname' => 'LBL_TEMPLATE_ID',
    'type' => 'id',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => false,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'template' => array (
    'required' => true,
    'source' => 'non-db',
    'name' => 'template',
    'vname' => 'LBL_TEMPLATE',
    'type' => 'relate',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'id_name' => 'template_id',
    'ext2' => 'EmailTemplates',
    'module' => 'EmailTemplates',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'select_all' => array(
    'name' => 'select_all',
    'vname' => 'LBL_SELECT_ALL',
    'type' => 'bool',
    'default'=> 0,
  ),
  'prospect_lists' => array(
    'required' => false,
    'name' => 'prospect_lists',
    'vname' => 'LBL_PROSPECT_LISTs',
    'type' => 'multienum',
    'massupdate' => '1',
    'default' => '^^',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'required',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'options' => 'stic_prospectlists_campaign',
    'studio' => 'visible',
    'isMultiSelect' => true,
  ),

'campaigns_stic_message_marketing' =>
   array (
    'name' => 'campaigns_stic_message_marketing',
    'type' => 'link',
    'relationship' => 'campaigns_stic_message_marketing',
    'source' => 'non-db',
    'module' => 'Campaigns',
    'bean_name' => 'Campaign',
    'vname' => 'LBL_CAMPAIGNS_TITLE',
    'id_name' => 'campaigns_stic_message_marketingcampaign_ida',
   ),
  'campaigns_stic_message_marketing_name' =>
   array (
    'name' => 'campaigns_stic_message_marketing_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_CAMPAIGNS_TITLE',
    'save' => true,
    'id_name' => 'campaigns_stic_message_marketingcampaign_ida',
    'link' => 'campaigns_stic_message_marketing',
    'table' => 'campaigns',
    'module' => 'Campaigns',
    'massupdate' => 0,
    'required' => true,
    'rname' => 'name',
   ),
  'campaigns_stic_message_marketingcampaign_ida' =>
   array (
    'name' => 'campaigns_stic_message_marketingcampaign_ida',
    'type' => 'link',
    'relationship' => 'campaigns_stic_message_marketing',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_CAMPAIGNS_TITLE',
   ),  



),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => false,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('stic_Message_Marketing', 'stic_Message_Marketing', array('basic','assignable','security_groups'));

// Set special values for SuiteCRM base fields
$dictionary['stic_Message_Marketing']['fields']['name']['required'] = '0'; // Name is not required in this module
$dictionary['stic_Message_Marketing']['fields']['name']['importable'] = true; // Name is importable but not required in this module
$dictionary['stic_Message_Marketing']['fields']['assigned_user_name']['required'] = true; // Assigned user is required in this module
$dictionary['stic_Message_Marketing']['fields']['description']['rows'] = '2'; // Make textarea fields shorter