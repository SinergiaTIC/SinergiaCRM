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

$module_name = 'stic_AWF_Response_Details';
$listViewDefs[$module_name] = array(
    'FORM' => array(
        'width' => '15',
        'label' => 'LBL_FORM',
        'module' => 'stic_AWF_Forms',
        'id' => 'STIC_AWF_FORMS_ID_C',
        'link' => true,
        'default' => true
    ),
    'RESPONSE' => array(
        'width' => '15',
        'label' => 'LBL_RESPONSE',
        'module' => 'stic_AWF_Responses',
        'id' => 'STIC_AWF_RESPONSES_ID_C',
        'link' => true,
        'default' => true
    ),
    'QUESTION_SORT_ORDER' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_SORT_ORDER',
        'default' => false,
    ),
    'QUESTION_KEY' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_KEY',
        'default' => false,
    ),
    'QUESTION_SECTION' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_SECTION',
        'default' => true,
    ),
    'QUESTION_LABEL' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_LABEL',
        'default' => true,
    ),
    'QUESTION_HELP_TEXT' => array(
        'width' => '10',
        'label' => 'LBL_QUESTION_HELP_TEXT',
        'default' => false,
    ),
    'ANSWER_VALUE' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_VALUE',
        'default' => false,
    ), 
    'ANSWER_TEXT' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_TEXT',
        'default' => true,
    ),
    'ANSWER_INTEGER' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_INTEGER',
        'default' => false,
    ),
    'ANSWER_FORM_TYPE' => array(
        'width' => '10',
        'label' => 'LBL_ANSWER_FORM_TYPE',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
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
