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
// created: 2021-12-10 11:41:54
$searchFields['stic_Attendances'] = array(
    'name' => array(
        'query_type' => 'default',
    ),
    'current_user_only' => array(
        'query_type' => 'default',
        'db_field' => array(
            0 => 'assigned_user_id',
        ),
        'my_items' => true,
        'vname' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
    ),
    'assigned_user_id' => array(
        'query_type' => 'default',
    ),
    'range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_start_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_start_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_start_date' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_duration' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_duration' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_duration' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'favorites_only' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'checked_only' => true,
        'subquery' => 'SELECT favorites.parent_id FROM favorites
			                    WHERE favorites.deleted = 0
			                        and favorites.parent_type = \'stic_Attendances\'
			                        and favorites.assigned_user_id = \'{1}\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
    'range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'stic_attendances_stic_registrations_stic_events_name' => array (
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery' => 'SELECT sasrc.stic_attendances_stic_registrationsstic_attendances_idb FROM stic_attendances_stic_registrations_c sasrc 
            INNER JOIN stic_registrations_stic_events_c srsec ON srsec.stic_registrations_stic_eventsstic_registrations_idb = sasrc.stic_attendances_stic_registrationsstic_registrations_ida AND srsec.deleted = 0
            INNER JOIN stic_events se ON se.id = srsec.stic_registrations_stic_eventsstic_events_ida AND se.deleted = 0
            WHERE sasrc.deleted = 0 AND se.name LIKE \'{0}\'',
        'db_field' => array (
            0 => 'id',
        ),
    ),
);
