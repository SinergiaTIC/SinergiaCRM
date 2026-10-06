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

$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopButtonQuickCreate'),
        array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
        ),
        'stic_payment_commitments_name' => array(
            'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_ALLOCATION_PROPOSALS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
            'widget_class' => 'SubPanelDetailViewLink',
            'module' => 'stic_Payment_Commitments',
            'width' => '20%',
        ),
        'active' => array(
            'vname' => 'LBL_ACTIVE',
            'width' => '10%',
        ),
        'opportunities_stic_allocation_proposals_name' => array(
            'type' => 'relate',
            'link' => true,
            'id' => 'OPPORTUNITIES_STIC_ALLOCATION_PROPOSALSOPPORTUNITIES_IDA',
            'vname' => 'LBL_OPPORTUNITIES_STIC_ALLOCATION_PROPOSALS_FROM_OPPORTUNITIES_TITLE',
            'widget_class' => 'SubPanelDetailViewLink',
            'module' => 'Opportunities',
            'target_module' => 'Opportunities',
            'target_record_key' => 'opportunities_stic_allocation_proposalsopportunities_ida',
            'width' => '20%',
        ),
        'project_stic_allocation_proposals_name' => array(            
            'type' => 'relate',
            'link' => true,
            'id' => 'PROJECT_STIC_ALLOCATION_PROPOSALSPROJECT_IDA',
            'vname' => 'LBL_PROJECT_STIC_ALLOCATION_PROPOSALS_FROM_PROJECT_TITLE',
            'widget_class' => 'SubPanelDetailViewLink',
            'module' => 'Project',
            'target_module' => 'Project',
            'target_record_key' => 'project_stic_allocation_proposalsproject_ida',
            'width' => '20%',
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
            'width' => '20%',
        ),
        'type' => array(
            'vname' => 'LBL_TYPE',
            'width' => '15%',
        ),
        'payment_amount_field' => array(
            'vname' => 'LBL_PAYMENT_AMOUNT_FIELD',
            'width' => '15%',
        ),
        'percentage' => array(
            'vname' => 'LBL_PERCENTAGE',
            'width' => '10%',
        ),
        'hours' => array(
            'vname' => 'LBL_HOURS',
            'width' => '10%',
        ),
        'assigned_user_name' => array(
            'vname' => 'LBL_ASSIGNED_TO',
            'width' => '10%',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Users',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Allocation_Proposals',
            'width' => '5%',
        ),
    ),
);