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
$module_name = 'stic_Group_Opportunities';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
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
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_group_opportunities_accounts_name',
                        'label' => 'LBL_STIC_GROUP_OPPORTUNITIES_ACCOUNTS_NAME',
                    ),
                    1 => array(
                        'name' => 'stic_group_opportunities_opportunities_name',
                        'label' => 'LBL_STIC_GROUP_OPPORTUNITIES_OPPORTUNITIES_NAME',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'contact',
                        'studio' => 'visible',
                        'label' => 'LBL_CONTACT',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'document_status',
                        'studio' => 'visible',
                        'label' => 'LBL_DOCUMENT_STATUS',
                    ),
                    1 => array(
                        'name' => 'folder',
                        'label' => 'LBL_FOLDER',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'amount_requested',
                        'label' => 'LBL_AMOUNT_REQUESTED',
                    ),
                    1 => array(
                        'name' => 'amount_awarded',
                        'label' => 'LBL_AMOUNT_AWARDED',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'amount_received',
                        'label' => 'LBL_AMOUNT_RECEIVED',
                    ),
                    1 => '',
                ),
                6 => array(
                    0 => array(
                        'name' => 'presentation_date',
                        'label' => 'LBL_PRESENTATION_DATE',
                    ),
                    1 => array(
                        'name' => 'validation_date',
                        'label' => 'LBL_VALIDATION_DATE',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'resolution_date',
                        'label' => 'LBL_RESOLUTION_DATE',
                    ),
                    1 => array(
                        'name' => 'advance_date',
                        'label' => 'LBL_ADVANCE_DATE',
                    ),
                ),
                8 => array(
                    0 => array(
                        'name' => 'justification_date',
                        'label' => 'LBL_JUSTIFICATION_DATE',
                    ),
                    1 => array(
                        'name' => 'payment_date',
                        'label' => 'LBL_PAYMENT_DATE',
                    ),
                ),
                9 => array(
                    0 => 'description',
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
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
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
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
