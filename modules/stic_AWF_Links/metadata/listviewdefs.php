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

$module_name = 'stic_AWF_Links';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
    'STIC_C271_LINKS_NAME' => array(
        'label' => 'LBL_RESPONSE',
        'module' => 'stic_AWF_Responses',
        'id' => 'STIC_AWF_RESPONSES_STIC_AWF_LINKSRESPONSES_IDA',
        'link' => true,
        'default' => true
    ),
    //  Related Form name (computed in the bean), linked to the Form record
    'FORM_NAME' => array(
        'width' => '15',
        'label' => 'LBL_FORM',
        'module' => 'stic_AWF_Forms',
        'id' => 'FORM_ID',
        'link' => true,
        'default' => true,
        'sortable' => false,
    ),
    'SEQUENCE_NUMBER' => array(
        'width' => '5',
        'label' => 'LBL_SEQUENCE_NUMBER',
        'default' => false,
    ),
    'RECORD_ACTION' => array(
        'label' => 'LBL_RECORD_ACTION',
        'default' => true,
    ),
    'PARENT_TYPE_TRANSLATED' => array(
        'width' => '15%',
        'label' => 'LBL_PARENT_TYPE',
        'default' => true,
        'sortable' => false,
        'related_fields' => array('parent_type'), 
    ),
    'PARENT_NAME' => array(
        'type' => 'parent',
        'studio' => 'visible',
        'label' => 'LBL_FLEX_RELATE',
        'link' => true,
        'sortable' => false,
        'ACLTag' => 'PARENT',
        'dynamic_module' => 'PARENT_TYPE',
        'id' => 'PARENT_ID',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'width' => '10%',
        'default' => true,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true
    ),

);
