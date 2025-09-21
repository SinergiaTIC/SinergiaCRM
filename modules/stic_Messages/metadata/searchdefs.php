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
$module_name = 'stic_Messages';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'parent_name' => array(
                'type' => 'parent',
                'label' => 'LBL_LIST_RELATED_TO',
                'width' => '10%',
                'default' => true,
                'name' => 'parent_name',
            ),
            'sent_date' => array(
                'type' => 'datetime',
                'label' => 'LBL_SENT_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'sent_date',
            ),
            'type' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'type',
            ),
            'direction' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_DIRECTION',
                'width' => '10%',
                'default' => true,
                'name' => 'direction',
            ),
            'STATUS' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
                'width' => '10%',
                'default' => true,
                'name' => 'status',
            ),
            'phone' =>array(
                'name' => 'phone',
                'label' => 'LBL_PHONE',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            // 'template' => array(
            //     'type' => 'relate',
            //     'studio' => 'visible',
            //     'label' => 'LBL_TEMPLATE',
            //     'id' => 'template_id',
            //     'link' => true,
            //     'width' => '10%',
            //     'default' => true,
            //     'name' => 'template',
            // ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'default' => true,
                'width' => '10%',
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'parent_name' => array(
                'type' => 'parent',
                'label' => 'LBL_LIST_RELATED_TO',
                'width' => '10%',
                'default' => true,
                'name' => 'parent_name',
            ),
            'sent_date' => array(
                'type' => 'datetime',
                'label' => 'LBL_SENT_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'sent_date',
            ),
            'type' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_TYPE',
                'width' => '10%',
                'default' => true,
                'name' => 'level',
            ),
            'direction' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_DIRECTION',
                'width' => '10%',
                'default' => true,
                'name' => 'level',
            ),
            'status' => array(
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
                'width' => '10%',
                'default' => true,
                'name' => 'status',
            ),
            'phone' =>array(
                'name' => 'phone',
                'label' => 'LBL_PHONE',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            // 'template' => array(
            //     'type' => 'relate',
            //     'studio' => 'visible',
            //     'label' => 'LBL_TEMPLATE',
            //     'id' => 'template_id',
            //     'link' => true,
            //     'width' => '10%',
            //     'default' => true,
            //     'name' => 'template',
            // ),
            'date_entered' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),            
            'sender' =>array(
                'name' => 'sender',
                'label' => 'LBL_SENDER',
                'default' => true,
                'width' => '10%',
            ),
            'date_modified' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'modified_user_id' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'created_by' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
    )
);
