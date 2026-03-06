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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['stic_SignaturesDashlet']['searchFields'] = array(
    'date_entered' => array('default' => ''),
    'date_modified' => array('default' => ''),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => $current_user->name,
    ),
);
$dashletData['stic_SignaturesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
    ),

    'main_module' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_MAIN_MODULE',
        'width' => '10%',
        'default' => true,
    ),

    'status' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
    ),

    'type' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
    ),

    'signature_mode' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_SIGNATURE_MODE',
        'width' => '10%',
        'default' => true,
    ),

    'activation_date' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ACTIVATION_DATE',
        'width' => '10%',
        'default' => true,
    ),

    'auth_method' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_AUTH_METHOD',
        'width' => '10%',
        'default' => true,
    ),

    'email_template' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_EMAIL_TEMPLATE',
        'width' => '10%',
        'default' => true,
    ),

    'date_entered' => array(
        'width' => '15',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
    ),
    'date_modified' => array(
        'width' => '15',
        'label' => 'LBL_DATE_MODIFIED',
    ),
    'created_by' => array(
        'width' => '8',
        'label' => 'LBL_CREATED_BY',
    ),
    'assigned_user_name' => array(
        'width' => '8',
        'label' => 'LBL_LIST_ASSIGNED_USER',
    ),
);
