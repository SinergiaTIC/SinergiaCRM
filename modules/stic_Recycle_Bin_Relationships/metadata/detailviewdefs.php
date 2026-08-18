<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$viewdefs['stic_Recycle_Bin_Relationships']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(),
        ),
        'maxColumns' => 2,
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        array(
            'LBL_RECYCLE_RELATIONSHIP_INFO' => array(
                'stic_recycle_bin_name',
                'recycle_relationship_name',
                'recycle_join_table',
                'recycle_join_lhs_key',
                'recycle_join_rhs_key',
            ),
        ),
        array(
            'LBL_RECYCLE_RELATED_RECORD' => array(
                'recycle_related_module',
                'recycle_related_record_name',
                'recycle_related_record_id',
                'recycle_record_id',
            ),
        ),
        array(
            'LBL_RECYCLE_STATUS' => array(
                'recycle_restored',
                'date_entered',
                'date_modified',
            ),
        ),
    ),
);
