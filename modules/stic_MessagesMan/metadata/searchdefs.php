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

  $searchdefs['stic_MessagesMan'] = array(
    'templateMeta' => array(
            'maxColumns' => '3', 'maxColumnsBasic' => '4',
            'widths' => array('label' => '10', 'field' => '30'),
           ),
    'layout' => array(
        'basic_search' => array(
            array('name'=>'campaign_name', 'label'=>'LBL_LIST_CAMPAIGN'),
            array('name'=>'to_name', 'label'=>'LBL_LIST_RECIPIENT_NAME'),
            array('name'=>'to_phone', 'label'=>'LBL_LIST_RECIPIENT_PHONE'),
            array('name'=>'message_name', 'label'=>'LBL_LIST_MESSAGE_NAME'),
            array('name'=>'send_date_time', 'label'=>'LBL_SEND_DATE_TIME'),                        
            array('name'=>'in_queue', 'label'=>'LBL_IN_QUEUE'),
            array('name'=>'current_user_only', 'label'=>'LBL_CURRENT_USER_FILTER', 'type'=>'bool'),
        ),
        'advanced_search' => array(
            array('name'=>'campaign_name', 'label'=>'LBL_LIST_CAMPAIGN'),
            array('name'=>'to_name', 'label'=>'LBL_LIST_RECIPIENT_NAME'),
            array('name'=>'to_phone', 'label'=>'LBL_LIST_RECIPIENT_PHONE'),
            array('name'=>'message_name', 'label'=>'LBL_LIST_MESSAGE_NAME'),
            array('name'=>'send_date_time', 'label'=>'LBL_SEND_DATE_TIME'),
            array('name'=>'in_queue', 'label'=>'LBL_IN_QUEUE'),
            array('name'=>'current_user_only', 'label'=>'LBL_CURRENT_USER_FILTER', 'type'=>'bool'),
            array('name' => 'date_entered', 'label' => 'LBL_DATE_ENTERED' ),
            array('name' => 'date_modified', 'label' => 'LBL_DATE_MODIFIED' ),
            array('name' => 'modified_user_id', 'label' => 'LBL_MODIFIED_USER'),
            array('name' => 'user_id', 'label' => 'LBL_USER_ID' ),
        ),
    ),
);
