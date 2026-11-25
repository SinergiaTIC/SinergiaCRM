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
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'stic_Justifications'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '20%',
            'default' => true,
        ),
        'status' => array(
            'type' => 'enum',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
            'default' => true,
        ),
        'opportunities_stic_justifications_name' => array(
            'type' => 'relate',
            'link' => true,
            'id' => 'OPPORTUNIT01EUNITIES_IDA',
            'vname' => 'LBL_OPPORTUNITIES_STIC_JUSTIFICATIONS_FROM_OPPORTUNITIES_TITLE',
            'widget_class' => 'SubPanelDetailViewLink',
            'module' => 'Opportunities',
            'target_module' => 'Opportunities',
            'target_record_key' => 'opportunit01eunities_ida',
            'width' => '20%',
            'default' => true,
        ),
        'ledger_group' => array(
            'vname' => 'LBL_GROUP',
            'width' => '10%',
            'default' => true,
        ),
        'subgroup' => array(
            'vname' => 'LBL_SUBGROUP',
            'width' => '10%',
            'default' => true,
        ),
        'account' => array(
            'vname' => 'LBL_ACCOUNT',
            'width' => '10%',
            'default' => true,
        ),
        'allocation_type' => array(
            'type' => 'enum',
            'vname' => 'LBL_ALLOCATION_TYPE',
            'width' => '10%',
            'default' => true,
        ),
        'amount' => array(
            'vname' => 'LBL_AMOUNT',
            'width' => '10%',
            'default' => true,
        ),
        'justified_amount' => array(
            'vname' => 'LBL_JUSTIFIED_AMOUNT',
            'width' => '10%',
            'default' => true,
        ),
        'date_entered' => array(
            'vname' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => true,
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'stic_Justifications',
            'width' => '5%',
            'default' => true,
        ),
    ),
);