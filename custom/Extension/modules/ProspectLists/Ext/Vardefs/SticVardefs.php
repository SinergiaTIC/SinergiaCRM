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

$dictionary['ProspectList']['fields']['created_by_name']['massupdate'] = false;
$dictionary['ProspectList']['fields']['date_entered']['massupdate'] = false;
$dictionary['ProspectList']['fields']['date_modified']['massupdate'] = false;
$dictionary['ProspectList']['fields']['domain_name']['massupdate'] = false;
$dictionary['ProspectList']['fields']['list_type']['massupdate'] = 1;
$dictionary['ProspectList']['fields']['marketing_id']['massupdate'] = false;
$dictionary['ProspectList']['fields']['marketing_name']['massupdate'] = false;
$dictionary['ProspectList']['fields']['name']['massupdate'] = false;
$dictionary['ProspectList']['fields']['description']['rows'] = 2;

$dictionary['ProspectList']['unified_search_default_enabled'] = false;

// Many to Many filter fields
$dictionary['ProspectList']['fields']['stic_prospect_lists_contacts_name']= array(
    'name' => 'stic_prospect_lists_contacts_name',
    'vname' => 'LBL_STIC_PROSPECT_LISTS_CONTACTS_NAME',
    'query_type' => 'default',
    'source' => 'non-db',
    'type' => 'relate',
    'width' => '10%',
    'default' => true,
    'studio' => array(
        'searchview' => true, // To appear in the filter view layout editor
        'visible' => false // To avoid appear in the record view layout editor
    ),
    'id_name' => 'related_id',
    'module' => 'Contacts',
);