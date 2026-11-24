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

$module_name = 'stic_AWF_Incoming_Events';
$viewdefs [$module_name] = array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'DELETE',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_REQUEST_DATA' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_RECORD_DETAILS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'date_received',
                        'label' => 'LBL_DATE_RECEIVED',
                    ),
                    1 => array(
                        'name' => 'date_processed',
                        'label' => 'LBL_DATE_PROCESSED',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'source',
                        'label' => 'LBL_SOURCE',
                    ),
                    1 => array(
                        'name' => 'external_transaction_id',
                        'label' => 'LBL_EXTERNAL_TRANSACTION_ID',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'response_name',
                        'label' => 'LBL_RESPONSE',
                    ),
                    1 => array(
                        'name' => 'form_name',
                        'label' => 'LBL_FORM',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'processing_attempts',
                        'label' => 'LBL_PROCESSING_ATTEMPTS',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'last_error_message',
                        'label' => 'LBL_LAST_ERROR_MESSAGE',
                    ),
                ),
            ),
            'lbl_panel_request_data' => array(
                0 => array(
                    0 => array(
                        'name' => 'token',
                        'label' => 'LBL_TOKEN',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'raw_payload',
                        'label' => 'LBL_RAW_PAYLOAD',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'headers',
                        'label' => 'LBL_HEADERS',
                    ),
                ),
            ),
            'lbl_panel_record_details' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_NAME',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
