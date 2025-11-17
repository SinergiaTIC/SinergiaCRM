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

$viewdefs['stic_Allocation_Proposals']['EditView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                'name',
                'assigned_user_name',
            ),
            array(
                'project_stic_allocation_proposals_name',
                'opportunities_stic_allocation_proposals_name',
            ),
            array(
                'stic_payment_commitments_name',
                'stic_ledger_accounts_name',
            ),
            array(
                'active',
                'type',
            ),
            array(
                'payment_amount_field',
                'percentage',
            ),
            array(
                'hours',
                '',
            ),
            array(
                array(
                    'name' => 'description',
                    'span' => 12,
                ),
            ),
        ),
    ),
);