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
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'stic_Justification_Conditions'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '20%',
            'default' => true,
        ),
        'active' => array(
            'type' => 'bool',
            'vname' => 'LBL_ACTIVE',
            'width' => '10%',
            'default' => true,
        ),
        'opportunities_stic_justification_conditions_name' => array(
            'type' => 'relate',
            'vname' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATION_CONDITIONS_FROM_OPPORTUNITIES_TITLE',
            'width' => '15%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Opportunities',
            'target_record_key' => 'opportunities_stic_justification_conditionsopportunities_ida',
        ),
        'allocation_type' => array(
            'type' => 'enum',
            'vname' => 'LBL_ALLOCATION_TYPE',
            'width' => '10%',
            'default' => true,
        ),
        'max_allocable_percentage' => array(
            'vname' => 'LBL_MAX_ALLOCABLE_PERCENTAGE',
            'width' => '10%',
            'default' => true,
        ),
        'max_allocable_percentage_grant' => array(
            'vname' => 'LBL_MAX_ALLOCABLE_PERCENTAGE_GRANT',
            'width' => '10%',
            'default' => true,
        ),
        'justified_percentage' => array(
            'vname' => 'LBL_JUSTIFIED_PERCENTAGE',
            'width' => '10%',
            'default' => true,
        ),
        'justified_amount' => array(
            'vname' => 'LBL_JUSTIFIED_AMOUNT',
            'width' => '10%',
            'default' => true,
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Justification_Conditions',
            'width' => '5%',
            'default' => true,
        ),
    ),
);