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

$listViewDefs['stic_MessagesMan'] = array(
    'CAMPAIGN_NAME' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_CAMPAIGN',
        'link' => true,
        'customCode' => '<a href="index.php?module=Campaigns&action=DetailView&record={$CAMPAIGN_ID}">{$CAMPAIGN_NAME}</a>',
        'default' => true),
    'RECIPIENT_NAME' => array(
        'sortable' => false,
        'width' => '25%',
        'label' => 'LBL_LIST_RECIPIENT_NAME',
        'customCode' => '<a href="index.php?module={$RELATED_TYPE}&action=DetailView&record={$RELATED_ID}">{$RECIPIENT_NAME}</a>',
        'default' => true),
    'RECIPIENT_PHONE' => array(
        'sortable' => false,
        'width' => '10%',
        'label' => 'LBL_LIST_RECIPIENT_PHONE',
        'customCode' => '{$RECIPIENT_PHONE}</a>',
        'default' => true),
    'MESSAGE_NAME' => array(
        'sortable' => false,
        'width' => '20%',
        'label' => 'LBL_LIST_MESSAGE_NAME',
        'customCode' => '<a href="index.php?module=EmailMarketing&action=DetailView&record={$MARKETING_ID}">{$MESSAGE_NAME}</a>',
        'default' => true),
    'SEND_DATE_TIME' => array(
        'width' => '10%',
        'label' => 'LBL_LIST_SEND_DATE_TIME',
        'default' => true),
    'SEND_ATTEMPTS' => array(
        'width' => '5%',
        'label' => 'LBL_LIST_SEND_ATTEMPTS',
        'default' => true),
    'IN_QUEUE' => array(
        'width' => '5%',
        'label' => 'LBL_LIST_IN_QUEUE',
        'default' => true),
    'date_entered' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false),  
    'date_modified' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false),  
    'modified_user_id' => array(
        'width' => '10%',
        'label' => 'LBL_MODIFIED_USER',
        'default' => false),                  
    'user_id' => array(
        'width' => '10%',
        'label' => 'LBL_USER_ID',
        'default' => false),             
);
