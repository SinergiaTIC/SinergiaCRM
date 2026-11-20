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

$module_name = 'stic_AWF_Deferred_Tickets';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '32',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
    'RESPONSE' => array(
        'width' => '15',
        'label' => 'LBL_RESPONSE',
        'module' => 'stic_AWF_Responses',
        'id' => 'STIC_AWF_RESPONSES_ID_C',
        'link' => true,
        'default' => true
    ),

    'FORM_NAME' => array(
        'width' => '15',
        'label' => 'LBL_FORM',
        'module' => 'stic_AWF_Forms',
        'id' => 'FORM_ID',
        'link' => true,
        'default' => false,
        'sortable' => false,
    ),
    'STATUS' => array(
        'width' => '10',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'EXTERNAL_TRANSACTION_ID' => array(
        'width' => '15',
        'label' => 'LBL_EXTERNAL_TRANSACTION_ID',
        'default' => false,
    ),
    'EXPIRATION_DATE' => array(
        'type' => 'datetime',
        'width' => '10',
        'label' => 'LBL_EXPIRATION_DATE',
        'default' => true,
    ),
    'RETRY_COUNT' => array(
        'width' => '10',
        'label' => 'LBL_RETRY_COUNT',
        'default' => false,
    ),
    'TOKEN_HASH' => array(
        'width' => '15',
        'label' => 'LBL_TOKEN_HASH',
        'default' => false,
    ),
    'HANDLER_ACTION_ID' => array(
        'width' => '15',
        'label' => 'LBL_HANDLER_ACTION_ID',
        'default' => false,
    ),
    'LAST_ERROR_MESSAGE' => array(
        'width' => '30',
        'label' => 'LBL_LAST_ERROR_MESSAGE',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);
