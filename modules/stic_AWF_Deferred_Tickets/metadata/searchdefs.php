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
$searchdefs[$module_name] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
            ),
            'response' => array(
                'name' => 'response',
                'type' => 'relate',
                'label' => 'LBL_RESPONSE',
                'id' => 'STIC_AWF_RESPONSES_ID_C',
                'link' => true,
            ),
            'form_name' => array(
                'name' => 'form_name',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id_name' => 'form_id',
                'rname' => 'name',
                'module' => 'stic_AWF_Forms',
                'quicksearch' => 'enabled',
            ),
            'status' => array(
                'name' => 'status',
            ),
            'external_transaction_id' => array(
                'name' => 'external_transaction_id',
                'type' => 'varchar',
            ),
            'expiration_date' => array(
                'name' => 'expiration_date',
                'type' => 'datetimecombo',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
            ),
            'response' => array(
                'name' => 'response',
                'type' => 'relate',
                'label' => 'LBL_RESPONSE',
                'id' => 'STIC_AWF_RESPONSES_ID_C',
                'link' => true,
            ),
            'form_name' => array(
                'name' => 'form_name',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id_name' => 'form_id',
                'rname' => 'name',
                'module' => 'stic_AWF_Forms',
                'quicksearch' => 'enabled',
            ),
            'status' => array(
                'name' => 'status',
            ),
            'external_transaction_id' => array(
                'name' => 'external_transaction_id',
                'type' => 'varchar',
            ),
            'token_hash' => array(
                'name' => 'token_hash',
                'type' => 'varchar',
            ),
            'handler_action_id' => array(
                'name' => 'handler_action_id',
                'type' => 'varchar',
            ),
            'retry_count' => array(
                'name' => 'retry_count',
            ),
            'last_error_message' => array(
                'name' => 'last_error_message',
                'type' => 'varchar',
            ),
            'expiration_date' => array(
                'name' => 'expiration_date',
                'type' => 'datetimecombo',
            ),
            'date_entered' => array(
                'name' => 'date_entered',
                'label' => 'LBL_DATE_ENTERED',
                'type' => 'datetime',
            ),
            'date_modified' => array(
                'name' => 'date_modified',
                'label' => 'LBL_DATE_MODIFIED',
                'type' => 'datetime',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
            ),
        ),
    ),
);
