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

$module_name = 'stic_Recycle_Bin_Relationships';
$subpanel_layout = array(
    'top_buttons' => array(),
    'where' => '',
    'list_fields' => array(
        'stic_recycle_bin_name' => array(
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_STIC_RECYCLE_BIN',
            'id' => 'STIC_RECYCLE_BIN_ID',
            'width' => '20%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'stic_Recycle_Bin',
            'target_record_key' => 'stic_recycle_bin_id',
        ),
        'recycle_related_module' => array(
            'type' => 'varchar',
            'vname' => 'LBL_RECYCLE_RELATED_MODULE',
            'width' => '12%',
            'default' => true,
        ),
        'recycle_related_record_name' => array(
            'type' => 'varchar',
            'vname' => 'LBL_RECYCLE_RELATED_RECORD_NAME',
            'width' => '20%',
            'default' => true,
        ),
        'recycle_relationship_name' => array(
            'type' => 'varchar',
            'vname' => 'LBL_RECYCLE_RELATIONSHIP_NAME',
            'width' => '16%',
            'default' => true,
        ),
        'recycle_join_table' => array(
            'type' => 'varchar',
            'vname' => 'LBL_RECYCLE_JOIN_TABLE',
            'width' => '12%',
            'default' => false,
        ),
        'recycle_restored' => array(
            'type' => 'bool',
            'vname' => 'LBL_RECYCLE_RESTORED',
            'width' => '8%',
            'default' => true,
        ),
        'date_entered' => array(
            'type' => 'datetime',
            'vname' => 'LBL_DATE_ENTERED',
            'width' => '12%',
            'default' => true,
        ),
    ),
);
