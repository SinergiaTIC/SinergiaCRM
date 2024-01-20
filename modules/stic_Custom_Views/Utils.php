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

require_once 'SticInclude/Utils.php';

function fill_dynamic_custom_views_lists($module = null) {
    fill_dynamic_role_list();
    fill_dynamic_security_group_list();

    /*if ($module!=null)*/ {
        fill_dynamic_field_list($module);
        fill_dynamic_panel_list($module);
    }
}

function fill_dynamic_role_list() {
    if(isset($GLOBALS ['app_list_strings']['dynamic_role_list'])) {
        return;
    }
    
    $rolFocus = BeanFactory::newBean('ACLRoles');
    $roles = $rolFocus->get_list("name", "", 0, -99, -99);
    
    $dynamic_role_list = array("" => "");
    foreach ($roles['list'] as $role) {
        $dynamic_role_list[$role->id] = $role->name;
    }

    $GLOBALS ['app_list_strings']['dynamic_role_list'] = $dynamic_role_list;
}

function fill_dynamic_security_group_list() {
    if(isset($GLOBALS ['app_list_strings']['dynamic_security_group_list'])) {
        return;
    }
    
    $groupFocus = BeanFactory::newBean('SecurityGroups');
    $groups = $groupFocus->get_list("name", "", 0, -99, -99);
    
    $dynamic_security_group_list = array("" => "");
    foreach ($groups['list'] as $group) {
        $dynamic_security_group_list[$group->id] = $group->name;
    }

    $GLOBALS ['app_list_strings']['dynamic_security_group_list'] = $dynamic_security_group_list;
}

function fill_dynamic_field_list($module) {
    $dynamic_field_list = array (
        'field1' => 'Field 1',
        'field2' => 'Field 2',
        'field3' => 'Field 3',
    );
    $GLOBALS ['app_list_strings']['dynamic_field_list'] = $dynamic_field_list;
}

function fill_dynamic_panel_list($module) {
    $dynamic_panel_list = array (
        'EditView_panel1' => 'Vista edició: Dades generals',
        'EditView_panel2' => 'Vista edició: Adreça',
    );
    $GLOBALS ['app_list_strings']['dynamic_panel_list'] = $dynamic_panel_list;
}