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

$listViewDefs['stic_Recycle_Bin_Relationships'] = array(
    'STIC_RECYCLE_BIN_NAME' => array(
        'width' => '20%',
        'label' => 'LBL_STIC_RECYCLE_BIN',
        'default' => true,
    ),
    'RECYCLE_RELATED_MODULE' => array(
        'width' => '12%',
        'label' => 'LBL_RECYCLE_RELATED_MODULE',
        'default' => true,
    ),
    'RECYCLE_RELATED_RECORD_NAME' => array(
        'width' => '20%',
        'label' => 'LBL_RECYCLE_RELATED_RECORD_NAME',
        'default' => true,
    ),
    'RECYCLE_RELATIONSHIP_NAME' => array(
        'width' => '16%',
        'label' => 'LBL_RECYCLE_RELATIONSHIP_NAME',
        'default' => true,
    ),
    'RECYCLE_JOIN_TABLE' => array(
        'width' => '12%',
        'label' => 'LBL_RECYCLE_JOIN_TABLE',
        'default' => false,
    ),
    'RECYCLE_RESTORED' => array(
        'width' => '8%',
        'label' => 'LBL_RECYCLE_RESTORED',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'width' => '12%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
    ),
);
