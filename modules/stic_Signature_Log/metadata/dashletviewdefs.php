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

global $current_user;

$dashletData['stic_Signature_LogDashlet']['searchFields'] = array(
    'date_entered' => array('default' => ''),
    'date_modified' => array('default' => ''),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => $current_user->name
    )
);
$dashletData['stic_Signature_LogDashlet']['columns'] = array(
    'name' => array(
        'width' => '40',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true
    ),
    'date_entered' => array(
        'width' => '15',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true
    ),
    'date_modified' => array(
        'width' => '15',
        'label' => 'LBL_DATE_MODIFIED'
    ),
    'created_by' => array(
        'width' => '8',
        'label' => 'LBL_CREATED'
    ),
    'assigned_user_name' => array(
        'width' => '8',
        'label' => 'LBL_LIST_ASSIGNED_USER'
    ),
    'action' => array(
        'width' => '10',
        'label' => 'LBL_ACTION',
        'default' => true
    ),
    'date' => array(
        'width' => '15',
        'label' => 'LBL_DATE',
        'default' => true
    ),
    'ip_address' => array(
        'width' => '15',
        'label' => 'LBL_IP_ADDRESS',
        'default' => true
    ),
    'user_agent' => array(
        'width' => '20',
        'label' => 'LBL_USER_AGENT',
        'default' => true
    ),
    'stic_signers_stic_signature_log_name' => array(
        'width' => '20',
        'label' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNERS_TITLE',
        'default' => true
    ),
    'stic_signatures_stic_signature_log_name' => array(
        'width' => '20',
        'label' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURES_TITLE',
        'default' => true
    ),       
);
