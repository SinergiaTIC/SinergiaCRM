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

$module_name = 'stic_AWF_Incoming_Events';
$searchFields[$module_name] = array(
    'name' => array('query_type' => 'default'),

    // Filter by the Response and the Form related through the matched Deferred Ticket (by token or external transaction id). 
    // There is no direct relationship between Incoming Events and Responses, so the value is resolved with a subquery
    'response_name' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery' => 'SELECT e2.id FROM stic_awf_incoming_events e2
                        INNER JOIN stic_awf_deferred_tickets k ON k.deleted = 0 AND (k.token_hash = e2.token OR k.external_transaction_id = e2.external_transaction_id)
                        INNER JOIN stic_awf_responses r ON r.id = k.stic_awf_responses_id_c AND r.deleted = 0
                       WHERE e2.deleted = 0 AND r.name LIKE \'{0}%\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),
    'form_name' => array(
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery' => 'SELECT e2.id FROM stic_awf_incoming_events e2
                        INNER JOIN stic_awf_deferred_tickets k ON k.deleted = 0 AND (k.token_hash = e2.token OR k.external_transaction_id = e2.external_transaction_id)
                        INNER JOIN stic_awf_responses r ON r.id = k.stic_awf_responses_id_c AND r.deleted = 0
                        INNER JOIN stic_awf_forms_stic_awf_responses_c fr ON fr.stic_awf_forms_stic_awf_responsesresponses_idb = r.id AND fr.deleted = 0
                        INNER JOIN stic_awf_forms f ON f.id = fr.stic_awf_forms_stic_awf_responsesforms_ida AND f.deleted = 0
                       WHERE e2.deleted = 0 AND f.name LIKE \'{0}%\'',
        'db_field' => array(
            0 => 'id',
        ),
    ),

    //Range Search Support
    'range_date_entered' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
    'start_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true
    ),
    'end_range_date_entered' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true
    ),
    'range_date_modified' => array('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
    'start_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true
    ),
    'end_range_date_modified' => array(
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true
    ),
    //Range Search Support
);
