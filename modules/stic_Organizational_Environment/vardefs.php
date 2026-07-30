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

$dictionary['stic_Organizational_Environment'] = array(
    'table' => 'stic_organizational_environment',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
    'optimistic_locking' => true,
    'team_security' => true,
    "assignable" => true,
    "plugin_use_workflow" => false,
    'fields' => array(
     
        'relationship_type' => array(
            'name' => 'relationship_type',
            'type' => 'enum',
            'required' => true,
            'options' => 'stic_organizational_environment_relationships_list',
            'vname' => 'LBL_RELATIONSHIP_TYPE',
            'massupdate' => true,
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'base_organization_id_c' => array(
            'name' => 'base_organization_id_c',
            'type' => 'id',
        ),
        'base_organization' => array(
            'name' => 'base_organization',
            'type' => 'relate',
            'module' => 'Accounts',
            'rname' => 'name',
            'id_name' => 'base_organization_id_c',
            'vname' => 'LBL_BASE_ORGANIZATION',
            'required' => true,
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'start_date' => array(
            'name' => 'start_date',
            'type' => 'date',
            'vname' => 'LBL_START_DATE',
            'massupdate' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'reference_organization' => array(
            'name' => 'reference_organization',
            'type' => 'bool',
            'default' => 1,
            'vname' => 'LBL_REFERENCE_ORGANIZATION',
            'massupdate' => true,
            'importable' => true,
        ),
        'network_person_id_c' => array(
            'name' => 'network_person_id_c',
            'type' => 'id',
        ),
        'network_person' => array(
            'name' => 'network_person',
            'type' => 'relate',
            'module' => 'Contacts',
            'rname' => 'full_name',
            'id_name' => 'network_person_id_c',
            'vname' => 'LBL_NETWORK_PERSON',
            'massupdate' => true,
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'network_organization_id_c' => array(
            'name' => 'network_organization_id_c',
            'type' => 'id',
        ),
        'network_organization' => array(
            'name' => 'network_organization',
            'type' => 'relate',
            'module' => 'Accounts',
            'rname' => 'name',
            'id_name' => 'network_organization_id_c',
            'vname' => 'LBL_NETWORK_ORGANIZATION',
            'massupdate' => true,
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'end_date' => array(
            'name' => 'end_date',
            'type' => 'date',
            'vname' => 'LBL_END_DATE',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'massupdate' => true,
            'importable' => true,
            'duplicate_merge' => 'enabled',
        ),
        'active' => array(
            'name' => 'active',
            'type' => 'bool',
            'default' => 1,
            'vname' => 'LBL_ACTIVE',
            'massupdate' => true,
            'importable' => true,
        ),
    ),
    'relationships' => array(
        'stic_org_env_base_accounts' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Organizational_Environment',
            'rhs_table' => 'stic_organizational_environment',
            'rhs_key' => 'base_organization_id_c',
            'relationship_type' => 'one-to-many',
        ),
        'stic_org_env_network_accounts' => array(
            'lhs_module' => 'Accounts',
            'lhs_table' => 'accounts',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Organizational_Environment',
            'rhs_table' => 'stic_organizational_environment',
            'rhs_key' => 'network_organization_id_c',
            'relationship_type' => 'one-to-many',
        ),
        'stic_org_env_network_contacts' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'stic_Organizational_Environment',
            'rhs_table' => 'stic_organizational_environment',
            'rhs_key' => 'network_person_id_c',
            'relationship_type' => 'one-to-many',
        ),
    ),
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('stic_Organizational_Environment', 'stic_Organizational_Environment', array('basic', 'assignable', 'security_groups'));

$dictionary['stic_Organizational_Environment']['fields']['name']['required'] = '0';
$dictionary['stic_Organizational_Environment']['fields']['name']['importable'] = true;

$dictionary['stic_Organizational_Environment']['fields']['description']['rows'] = '2';