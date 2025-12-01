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

$viewdefs['stic_Justification_Conditions']['EditView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
        ),
        'useTabs' => true,
        'tabDefs' => array(
            'LBL_DEFAULT_PANEL' => array(
                'newTab' => true,
                'panelDefault' => 'expanded',
            ),
        ),
        'syncDetailEditViews' => true,
    ),
    'panels' => array(
        'lbl_default_panel' => array(
            array(
                'name',
                'assigned_user_name',
            ),
            array(
                'opportunities_stic_justification_conditions_name',
                '',
            ),
            array(
                'active',
                'blocked',
            ),
            array(
                'allocation_type',
                '',
            ),
            array(
                'max_allocable_percentage',
                'max_allocable_percentage_grant',
            ),
            array(
                'max_allocable_amount_grant',
                '',
            ),
            array(
                'description',
            ),
        ),
    ),
);