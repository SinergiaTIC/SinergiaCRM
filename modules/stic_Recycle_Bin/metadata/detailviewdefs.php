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

$viewdefs['stic_Recycle_Bin']['DetailView'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        'LBL_DEFAULT_PANEL' => array(
            array(
                array(
                    'name' => 'record_name',
                    'label' => 'LBL_RECORD_NAME',
                    'customCode' => '{if $fields.restored.value == 1}<a href="index.php?module={$fields.record_module.value}&action=DetailView&record={$fields.record_id.value}">{$fields.record_name.value}</a>{else}{$fields.record_name.value}{/if}',
                ),
                'record_module',
            ),
            array('deletion_source', 'original_assigned_to'),
            array('date_deleted', 'user_deleted_name'),
            array('restored', ''),
            array('date_restored', 'user_restored_name'),
        ),
    ),
);
