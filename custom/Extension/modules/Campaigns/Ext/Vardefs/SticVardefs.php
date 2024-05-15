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

$dictionary["Campaign"]["fields"]["stic_payment_commitments_campaigns"] = array(
    'name' => 'stic_payment_commitments_campaigns',
    'type' => 'link',
    'relationship' => 'stic_payment_commitments_campaigns',
    'source' => 'non-db',
    'module' => 'stic_Payment_Commitments',
    'bean_name' => 'stic_Payment_Commitments',
    'side' => 'right',
    'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
);
$dictionary['Campaign']['fields']['parent_name'] = array (
    'id' => 'Campaignsparent_name',
    'name' => 'parent_name',
    'vname' => 'LBL_FLEX_RELATE',
    'comments' => '',
    'help' => '',
    'custom_module' => 'Campaigns',
    'source' => 'non-db',
    'type' => 'parent',
    'len' => 100,
    'size' => 20,
    'duplicate_merge' => 0,
    'duplicate_merge_dom_value' => 0,
    'merge_filter' => 'disabled',
    'unified_search' => false,
    'massupdate' => 0,
    'default' => NULL,
    'no_default' => 0,
    'importable' => 1,
    'audited' => false,
    'required' => false,
    'inline_edit' => 0,
    'reportable' => true,
    'studio' => 'visible',
    'dependency' => 0,
    'type_name' => 'parent_type',
    'id_name' => 'parent_id',
    'parent_type' => 'record_type_display',
);
$dictionary['Campaign']['fields']['parent_type'] = array (
    'id' => 'Campaignsparent_type',
    'required' => false,
    'name' => 'parent_type',
    'vname' => 'LBL_PARENT_TYPE',
    'type' => 'parent_type',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'custom_module' => 'Campaigns',
    'importable' => 1,
    'duplicate_merge' => 0,
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 255,
    'size' => '20',
    'dbType' => 'varchar',
    'studio' => 'hidden',
);
$dictionary['Campaign']['fields']['parent_id'] = array(
    'id' => 'Campaignsparent_id',
    'required' => false,
    'name' => 'parent_id',
    'vname' => 'LBL_PARENT_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'custom_module' => 'Campaigns',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'enabled',
    'len' => 36,
    'size' => '20',
);

$dictionary['Campaign']['fields']['end_date']['required'] = false;

$dictionary['Campaign']['fields']['budget']['massupdate'] = 0;
$dictionary['Campaign']['fields']['actual_cost']['massupdate'] = 0;
$dictionary['Campaign']['fields']['actual_revenue']['massupdate'] = 0;
$dictionary['Campaign']['fields']['expected_revenue']['massupdate'] = 0;
$dictionary['Campaign']['fields']['expected_cost']['massupdate'] = 0;
$dictionary['Campaign']['fields']['refer_url']['massupdate'] = 0;
$dictionary['Campaign']['fields']['tracker_text']['massupdate'] = 0;
$dictionary['Campaign']['fields']['objective']['massupdate'] = 0;
$dictionary['Campaign']['fields']['content']['massupdate'] = 0;

// Enabling massupdate for core fields
// STIC#981
$dictionary['Campaign']['fields']['start_date']['massupdate'] = 1;
$dictionary['Campaign']['fields']['end_date']['massupdate'] = 1;
$dictionary['Campaign']['fields']['status']['massupdate'] = 1;
$dictionary['Campaign']['fields']['campaign_type']['massupdate'] = 1;
$dictionary['Campaign']['fields']['frequency']['massupdate'] = 1;

$dictionary['Campaign']['unified_search_default_enabled'] = true;
