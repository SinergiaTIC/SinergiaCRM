<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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


$searchFields['stic_MessagesMan'] =
    array(
        'campaign_name' => array( 'query_type'=>'default','db_field'=>array('campaigns.name')),
        'to_name'=> array('query_type'=>'default','db_field'=>array('contacts.first_name','contacts.last_name','accounts.name','leads.first_name','leads.last_name','prospects.first_name','prospects.last_name', 'users.user_name')),
        'to_phone' => array(
            'query_type' => 'default',
            'db_field' => array(
                'contacts.phone_mobile',
                'accounts.phone_office',
                'leads.phone_mobile',
                'users.phone_mobile'
            )    
        ),
        'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true, 'vname' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
        'message_name' => array( 'query_type'=>'default','db_field'=>array('stic_message_marketing.name')),
        'in_queue' => array( 'query_type'=>'default','db_field'=>array('stic_messagesman.in_queue')),
        'range_send_date_time' => array(
            'query_type' => 'default',
            'enable_range_search' => true,
            'is_date_field' => true,
        ),
        'start_range_send_date_time' => array(
            'query_type' => 'default',
            'enable_range_search' => true,
            'is_date_field' => true,
        ),
        'end_range_send_date_time' => array(
            'query_type' => 'default',
            'enable_range_search' => true,
            'is_date_field' => true,
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
    );
// END STIC-Custom   