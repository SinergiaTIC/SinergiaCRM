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
$searchdefs[$module_name] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'form' => array(
                'name' => 'form',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id' => 'STIC_AWF_FORMS_ID_C',
                'link' => true,
            ),
            'response' => array(
                'name' => 'response',
                'type' => 'relate',
                'label' => 'LBL_RESPONSE',
                'id' => 'STIC_AWF_RESPONSES_ID_C',
                'link' => true,
            ),
            'question_section' => array(
                'name' => 'question_section',
            ),
            'question_label' => array(
                'name' => 'question_label',
            ),
            'answer_text' => array(
                'name' => 'answer_text',
                'type' => 'varchar',
            ),
            'date_entered' => array(
                'name' => 'date_entered',
                'type' => 'datetime',
            ),
            array(
                'name' => 'assigned_user_id',
                'type' => 'enum',
                'function' => array('name' => 'get_user_array', 'params' => array(false))
            ),
            'current_user_only' => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
            ),
        ),
        'advanced_search' => array(
            'form' => array(
                'name' => 'form',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id' => 'STIC_AWF_FORMS_ID_C',
                'link' => true,
            ),
            'response' => array(
                'name' => 'response',
                'type' => 'relate',
                'label' => 'LBL_RESPONSE',
                'id' => 'STIC_AWF_RESPONSES_ID_C',
                'link' => true,
            ),
            'question_sort_order' => array(
                'name' => 'question_sort_order',
            ),
            'question_key' => array(
                'name' => 'question_key',
            ),
            'question_section' => array(
                'name' => 'question_section',
            ),
            'question_label' => array(
                'name' => 'question_label',
            ),
            'question_help_text' => array(
                'name' => 'question_help_text',
                'type' => 'varchar',
            ),
            'answer_text' => array(
                'name' => 'answer_text',
                'type' => 'varchar',
            ),
            'answer_value' => array(
                'name' => 'answer_value',
            ),
            'answer_integer' => array(
                'name' => 'answer_integer',
            ),
            'answer_form_type' => array(
                'name' => 'answer_form_type',
            ),
            'date_entered' => array(
                'name' => 'date_entered',
                'label' => 'LBL_DATE_ENTERED',
                'type' => 'datetime',
            ),
            array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array('name' => 'get_user_array', 'params' => array(false))
            ),
            'current_user_only' => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
            ),
        ),
    ),
);
