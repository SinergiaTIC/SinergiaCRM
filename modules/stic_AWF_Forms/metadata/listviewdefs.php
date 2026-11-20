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

//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'stic_AWF_Forms';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '32',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
    'FORM_TYPE' => array(
        'width' => '10',
        'label' => 'LBL_FORM_TYPE',
        'default' => true,
    ),
    'STATUS' => array(
        'width' => '10',
        'label' => 'LBL_STATUS',
        'default' => true,
    ),
    'START_DATE' => array(
        'width' => '10',
        'label' => 'LBL_START_DATE',
        'default' => true,
    ),
    'END_DATE' => array(
        'width' => '10',
        'label' => 'LBL_END_DATE',
        'default' => true,
    ),    
    'PUBLIC_URL' => array(
        'width' => '30',
        'label' => 'LBL_PUBLIC_URL',
        'default' => false,
    ),
    // 'PROCESSING_MODE' => array(
    //     'width' => '10',
    //     'label' => 'LBL_PROCESSING_MODE',
    //     'default' => true,
    // ),
    'ANALYTICS_REFERRERS' => array(
        'width' => '32',
        'label' => 'LBL_ANALYTICS_REFERRERS',
        'default' => true,
    ),
    'ANALYTICS_VIEWS' => array(
        'width' => '10',
        'label' => 'LBL_ANALYTICS_VIEWS',
        'default' => true,
    ),
    'ANALYTICS_SUBMISSIONS' => array(
        'width' => '10',
        'label' => 'LBL_ANALYTICS_SUBMISSIONS',
        'default' => true,
    ),
    'ANALYTICS_BLOCKED' => array(
        'width' => '10',
        'label' => 'LBL_ANALYTICS_BLOCKED',
        'default' => true,
    ),
    'ANALYTICS_SPAM' => array(
        'width' => '10',
        'label' => 'LBL_ANALYTICS_SPAM',
        'default' => true,
    ),
    'DESCRIPTION' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),    
    'CREATED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '9%',
        'default' => true,
    ),
);
