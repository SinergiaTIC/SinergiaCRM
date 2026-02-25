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



$subpanel_layout = array(
    'top_buttons' => array(
    ),

    'where' => '',


    'list_fields' => array(
        'recipient_name'=>array(
            'vname' => 'LBL_LIST_RECIPIENT_NAME',
            'width' => '10%',
            'sortable'=>false,
        ),
        'recipient_phone'=>array(
            'vname' => 'LBL_LIST_RECIPIENT_phone',
            'width' => '10%',
            'sortable'=>false,
        ),
        'message_name' => array(
            'vname' => 'LBL_MARKETING_ID',
            'width' => '10%',
            'sortable'=>false,
        ),
        'send_date_time' => array(
            'vname' => 'LBL_LIST_SEND_DATE_TIME',
            'width' => '10%',
            'sortable'=>false,
        ),
        'related_id'=>array(
            'usage'=>'query_only',
        ),
        'related_type'=>array(
            'usage'=>'query_only',
        ),
        'marketing_id' => array(
            'usage'=>'query_only',
        ),
    ),
);
