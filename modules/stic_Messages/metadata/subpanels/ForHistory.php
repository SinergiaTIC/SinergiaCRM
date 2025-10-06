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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = [
    'where' => '',
    'list_fields' => [
        'object_image' => [
            'vname' => 'LBL_OBJECT_IMAGE',
            'widget_class' => 'SubPanelIcon',
            'width' => '2%',
        ],
        'name' => [
            'vname' => 'LBL_LIST_SUBJECT',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '30%',
        ],
        'status' => [
            'widget_class' => 'SubPanelActivitiesStatusField',
            'vname' => 'LBL_LIST_STATUS',
            'width' => '15%',
            'force_exists' => true
        ],
        'reply_to_status' => [
            'usage' => 'query_only',
            'force_exists' => true,
            'force_default' => 0,
        ],
        'contact_name' => [
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'contact_id',
            'target_module' => 'Contacts',
            'module' => 'Contacts',
            'vname' => 'LBL_LIST_CONTACT',
            'width' => '11%',
            'sortable' => false,
        ],
        'parent_id' => [
            'usage' => 'query_only',
            'force_exists' => true
        ],
        'parent_type' => [
            'usage' => 'query_only',
            'force_exists' => true
        ],
        'date_modified' => [
            'vname' => 'LBL_LIST_DATE_MODIFIED',
            'width' => '10%',
        ],
        'date_entered' => [
            'vname' => 'LBL_LIST_DATE_ENTERED',
            'width' => '10%',
        ],

        'assigned_user_name' => [
            'name' => 'assigned_user_name',
            'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Employees',
            'width' => '10%',
        ],
        'assigned_user_owner' => [
            'force_exists' => true,
            'usage' => 'query_only'
        ],
        'assigned_user_mod' => [
            'force_exists' => true,
            'usage' => 'query_only'
        ],
        'edit_button' => [
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'width' => '2%',
        ],
        // 'remove_button' => [
        //     'vname' => 'LBL_REMOVE',
        //     'widget_class' => 'SubPanelRemoveButton',
        //     'width' => '2%',
        // ],
        'file_url' => [
            'usage' => 'query_only'
        ],
        'filename' => [
            'usage' => 'query_only',
            'force_exists' => true
        ],

    ],
];
