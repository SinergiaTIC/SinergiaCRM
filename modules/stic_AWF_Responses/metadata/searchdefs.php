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

$module_name = 'stic_AWF_Responses';
$searchdefs[$module_name] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
            ),
            'form_name' => array(
                'name' => 'form_name',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id_name' => 'stic_awf_forms_stic_awf_responsesforms_ida',
                'rname' => 'name',
                'module' => 'stic_AWF_Forms',
                'quicksearch' => 'enabled',
            ),
            'clean_referrer' => array(
                'name' => 'clean_referrer',
                'type' => 'varchar',
            ),
            'status' => array(
                'name' => 'status',
            ),
            'remote_ip' => array(
                'name' => 'remote_ip',
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
            'name' => array(
                'name' => 'name',
            ),
            'form_name' => array(
                'name' => 'form_name',
                'type' => 'relate',
                'label' => 'LBL_FORM',
                'id_name' => 'stic_awf_forms_stic_awf_responsesforms_ida',
                'rname' => 'name',
                'module' => 'stic_AWF_Forms',
                'quicksearch' => 'enabled',
            ),
            'clean_referrer' => array(
                'name' => 'clean_referrer',
                'type' => 'varchar',
            ),
            'status' => array(
                'name' => 'status',
            ),
            'remote_ip' => array(
                'name' => 'remote_ip',
                'type' => 'varchar',
            ), 
            'form_url' => array(
                'name' => 'form_url',
                'type' => 'varchar',
            ),
            'user_agent' => array(
                'name' => 'user_agent',
                'type' => 'varchar',
            ),
            'response_hash' => array(
                'name' => 'response_hash',
                'type' => 'varchar',
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
