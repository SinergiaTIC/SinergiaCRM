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
$dictionary["Campaign"]["fields"]["opportunities_campaigns_1"] = array(
    'name' => 'opportunities_campaigns_1',
    'type' => 'link',
    'relationship' => 'opportunities_campaigns_1',
    'source' => 'non-db',
    'module' => 'Opportunities',
    'bean_name' => 'Opportunity',
    'vname' => 'LBL_OPPORTUNITIES_CAMPAIGNS_1_FROM_OPPORTUNITIES_TITLE',
    'id_name' => 'opportunities_campaigns_1opportunities_ida',
);
$dictionary["Campaign"]["fields"]["opportunities_campaigns_1_name"] = array(
    'name' => 'opportunities_campaigns_1_name',
    'type' => 'relate',
    'source' => 'non-db',
    'vname' => 'LBL_OPPORTUNITIES_CAMPAIGNS_1_FROM_OPPORTUNITIES_TITLE',
    'save' => true,
    'id_name' => 'opportunities_campaigns_1opportunities_ida',
    'link' => 'opportunities_campaigns_1',
    'table' => 'opportunities',
    'module' => 'Opportunities',
    'rname' => 'name',
);
$dictionary["Campaign"]["fields"]["opportunities_campaigns_1opportunities_ida"] = array(
    'name' => 'opportunities_campaigns_1opportunities_ida',
    'type' => 'link',
    'relationship' => 'opportunities_campaigns_1',
    'source' => 'non-db',
    'reportable' => false,
    'side' => 'right',
    'vname' => 'LBL_OPPORTUNITIES_NOTIFICATIONS_TTITLE',
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
