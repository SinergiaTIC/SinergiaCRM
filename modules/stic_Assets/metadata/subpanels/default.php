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
        'width' => '45%',
        'default' => true,
    ),
    'code' => array(
        'type' => 'varchar',
        'vname' => 'LBL_CODE',
        'width' => '10%',
        'default' => true,
    ),
    'type' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'start_date' => array(
        'type' => 'date',
        'vname' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'address_street' => array(
        'type' => 'varchar',
        'vname' => 'LBL_ADDRESS_STREET',
        'width' => '10%',
        'default' => true,
    ),
    'address_city' => array(
        'type' => 'varchar',
        'vname' => 'LBL_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
    ),
    'date_modified' => array(
        'vname' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' => array(
        'link' => true,
        'type' => 'relate',
        'vname' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Users',
        'target_record_key' => 'assigned_user_id',
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'module' => 'stic_Skills',
        'width' => '4%',
        'default' => true,
    ),
    'quickedit_button' => array(
        'width' => '4%',
        'vname' => 'LBL_QUICKEDIT_BUTTON',
        'default' => true,
        'widget_class' => 'SubPanelQuickEditButton',
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'stic_Skills',
        'width' => '4%',
        'default' => true,
    ),
);
