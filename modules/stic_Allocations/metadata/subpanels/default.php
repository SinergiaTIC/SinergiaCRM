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

$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '20%',
        'default' => true,
    ),
    'stic_payments_name' => array(
        'vname' => 'LBL_STIC_PAYMENTS_STIC_ALLOCATIONS_FROM_STIC_ALLOCATIONS_TITLE',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'stic_Payments',
        'width' => '15%',
        'default' => true,
    ),
    'stic_allocation_proposals_name' => array(
        'vname' => 'LBL_STIC_ALLOCATION_PROPOSALS_STIC_ALLOCATIONS_FROM_STIC_ALLOCATIONS_TITLE',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'stic_Allocation_Proposals',
        'width' => '15%',
        'default' => true,
    ),
    'date' => array(
        'vname' => 'LBL_DATE',
        'width' => '15%',
        'default' => true,
    ),
    'validated' => array(
        'vname' => 'LBL_VALIDATED',
        'width' => '10%',
        'default' => true,
    ),
    'blocked' => array(
        'vname' => 'LBL_BLOCKED',
        'width' => '10%',
        'default' => true,
    ),
    'opportunities_stic_allocations_name' => array(
        'type' => 'relate',
        'link' => true,
        'id' => 'OPPORTUNITIES_STIC_ALLOCATIONSPPORTUNITIES_IDA',
        'vname' => 'LBL_OPPORTUNITIES_STIC_ALLOCATIONS_FROM_OPPORTUNITIES_TITLE',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'Opportunities',
        'target_module' => 'Opportunities',
        'target_record_key' => 'opportunities_stic_allocationsopportunities_ida',
        'width' => '15%',
        'default' => true,
    ),
    'project_stic_allocations_name' => array(
        'type' => 'relate',
        'link' => true,
        'id' => 'PROJECT_STIC_ALLOCATIONSPROJECT_IDA',
        'vname' => 'LBL_PROJECT_STIC_ALLOCATIONS_FROM_PROJECT_TITLE',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'Project',
        'target_module' => 'Project',
        'target_record_key' => 'project_stic_allocationsproject_ida',
        'width' => '15%',
        'default' => true,
    ),
    'stic_ledger_accounts_name' => array(
        'type' => 'relate',
        'link' => true,
        'id' => 'STIC_LEDGER_ACCOUNTS_IDA',
        'vname' => 'LBL_STIC_LEDGER_ACCOUNTS',
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'stic_Ledger_Accounts',
        'target_module' => 'stic_Ledger_Accounts',
        'target_record_key' => 'stic_ledger_accounts_ida',
        'width' => '15%',
        'default' => true,
    ),
    'type' => array(
        'vname' => 'LBL_TYPE',
        'width' => '15%',
        'default' => true,
    ),
    'percentage' => array(
        'vname' => 'LBL_PERCENTAGE',
        'width' => '10%',
        'default' => true,
    ),
    'amount' => array(
        'vname' => 'LBL_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'hours' => array(
        'vname' => 'LBL_HOURS',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' => array(
        'vname' => 'LBL_ASSIGNED_TO',
        'width' => '10%',
        'default' => true,
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'module' => 'stic_Allocations',
        'width' => '5%',
        'default' => true,
    ),
);