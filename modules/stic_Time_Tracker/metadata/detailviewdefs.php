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

$module_name = 'stic_Time_Tracker';
$viewdefs[$module_name]['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE',
                'FIND_DUPLICATES',
            )
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
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
                    'name',
                    'assigned_user_name',
            ),
            1 => array(
                0 => array(
                    'name' => 'start_date',
                    'label' => 'LBL_START_DATE',
                ),
                1 => array(
                    'name' => 'end_date',
                    'label' => 'LBL_END_DATE',
                ),
            ),
            2 => array(
                0 => array(
                    'name' => 'application_date',
                    'label' => 'LBL_APPLICATION_DATE',
                ),                
                1 => array(
                    'name' => 'duration',
                    'label' => 'LBL_DURATION',
                ),
            ),          
            3 => array (
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
    )
);
