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

$dictionary['Tracker']['fields']['assigned_user_link'] = array (
    'name' => 'assigned_user_link',
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'table' => 'users',
    'id_name' => 'user_id',
    'link' => true,
    'rname' => 'user_name',
    'inline_edit' => 0,
);

$dictionary['Tracker']['fields']['user_id']['module'] = 'Users';
$dictionary['Tracker']['fields']['user_id']['inline_edit'] = 0;

$dictionary['Tracker']['fields']['module_name']['type'] = 'enum';
$dictionary['Tracker']['fields']['module_name']['options'] = 'moduleList';
$dictionary['Tracker']['fields']['module_name']['inline_edit'] = 0;

$dictionary['Tracker']['fields']['action']['type'] = 'enum';
$dictionary['Tracker']['fields']['action']['options'] = 'trackers_actions_list';
$dictionary['Tracker']['fields']['action']['inline_edit'] = 0;

$dictionary['Tracker']['fields']['date_modified']['options'] = 'date_range_search_dom';
$dictionary['Tracker']['fields']['date_modified']['enable_range_search'] = true;
$dictionary['Tracker']['fields']['date_modified']['dbType'] = 'datetimecombo';
$dictionary['Tracker']['fields']['date_modified']['inline_edit'] = 0;

$dictionary['Tracker']['fields']['item_id']['inline_edit'] = 0;
$dictionary['Tracker']['fields']['item_summary']['inline_edit'] = 0;
$dictionary['Tracker']['fields']['session_id']['inline_edit'] = 0;
$dictionary['Tracker']['fields']['monitor_id']['inline_edit'] = 0;