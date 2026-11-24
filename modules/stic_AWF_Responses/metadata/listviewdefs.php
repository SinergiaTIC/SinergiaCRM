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

//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'stic_AWF_Responses';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '32',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
    'CLEAN_REFERRER' => array(
        'width' => '30',
        'label' => 'LBL_CLEAN_REFERRER',
        'default' => true,
    ),
    'STIC_1BB8PONSES_NAME' => array(
        'width' => '15',
        'label' => 'LBL_FORM',
        'module' => 'stic_AWF_Forms',
        'id' => 'STIC_AWF_FORMS_STIC_AWF_RESPONSESFORMS_IDA',
        'link' => true,
        'default' => true
    ),
    'FORM_URL' => array(
        'width' => '30',
        'label' => 'LBL_FORM_URL',
        'default' => false,
    ),
    'STATUS' => array(
        'width' => '30',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'REMOTE_IP' => array(
        'width' => '30',
        'label' => 'LBL_REMOTE_IP',
        'default' => true,
    ),
    'USER_AGENT' => array(
        'width' => '30',
        'label' => 'LBL_USER_AGENT',
        'default' => false,
    ),
    'RAW_PAYLOAD' => array(
        'width' => '30',
        'label' => 'LBL_RAW_PAYLOAD',
        'default' => false,
    ),
    'RESPONSE_HASH' => array(
        'width' => '30',
        'label' => 'LBL_RESPONSE_HASH',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '9%',
        'default' => true,
    ),
);
