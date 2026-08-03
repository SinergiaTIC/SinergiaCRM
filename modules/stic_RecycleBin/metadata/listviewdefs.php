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

$viewdefs['stic_RecycleBin']['ListView'] = array(
    'templateMeta' => array(
        'form' => array(
            'actions' => array(
                array(
                    'customCode' => '<a href=\'javascript:void(0)\' class="parent-dropdown-handler" id="actions_listview_{$location}" onclick="return false;"><label class="selected-actions-label hidden-mobile">{$APP.LBL_BULK_ACTION_BUTTON_LABEL_MOBILE}<span class=\'suitepicon suitepicon-action-caret\'></span></label><label class="selected-actions-label hidden-desktop">{$APP.LBL_BULK_ACTION_BUTTON_LABEL}<span class=\'suitepicon suitepicon-action-caret\'></span></label></a>',
                ),
                array(
                    'customCode' => '<a href=\'javascript:void(0)\' class="parent-dropdown-action-handler" id="mass_restore_listview_{$location}" onclick="return sticRecycleBinMassRestore();">{$MOD.LBL_MASS_RESTORE}</a>',
                ),
            ),
        ),
    ),
);

$listViewDefs['stic_RecycleBin'] = array(
    'RECYCLE_RECORD_NAME' => array(
        'width' => '25%',
        'label' => 'LBL_RECYCLE_RECORD_NAME',
        'default' => true,
        'link' => true,
    ),
    'RECYCLE_MODULE' => array(
        'width' => '15%',
        'label' => 'LBL_RECYCLE_MODULE',
        'default' => true,
    ),
    'RECYCLE_DATE_DELETED' => array(
        'width' => '15%',
        'label' => 'LBL_RECYCLE_DATE_DELETED',
        'default' => true,
    ),
    'RECYCLE_USER_DELETED_NAME' => array(
        'width' => '12%',
        'label' => 'LBL_RECYCLE_USER_DELETED',
        'default' => true,
    ),
    'RELATIONSHIP_COUNT' => array(
        'width' => '8%',
        'label' => 'LBL_RELATIONSHIP_COUNT',
        'default' => true,
    ),
    'RECYCLE_RESTORED' => array(
        'width' => '10%',
        'label' => 'LBL_RECYCLE_RESTORED',
        'default' => true,
        'type' => 'bool',
    ),
    'RECYCLE_DATE_RESTORED' => array(
        'width' => '10%',
        'label' => 'LBL_RECYCLE_DATE_RESTORED',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false,
    ),
);
