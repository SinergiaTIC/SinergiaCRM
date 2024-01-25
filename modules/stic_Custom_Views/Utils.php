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

function fillDynamicGenericLists() {
    fillDynamicRoleList();
    fillDynamicSecurityGroupList();
}

function fillDynamicRoleList() {
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

function fillDynamicSecurityGroupList() {
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

function fillDynamicViewModuleLists($view_module){
    fillDynamicViewModuleFieldList($view_module);
    fillDynamicViewModulePanelList($view_module);
}

function fillDynamicViewModuleFieldList($view_module) {
    $dynamic_field_list = getViewModuleFields($view_module);
    $GLOBALS ['app_list_strings']['dynamic_field_list'] = $dynamic_field_list;
}

function fillDynamicViewModulePanelList($view_module) {
    $dynamic_panel_list = array (
        'EditView_panel1' => 'Vista edició: Dades generals',
        'EditView_panel2' => 'Vista edició: Adreça',
    );
    $GLOBALS ['app_list_strings']['dynamic_panel_list'] = $dynamic_panel_list;
}

/**
 * Gets an array with all fields of the module
 * Adapted from modules/AOW_WorkFlow/aow_utils.php function getModuleFields
 * stic_Custom_Views is an admin module: Ommit checkAccess
 */
function getViewModuleFields($view_module) {
    global $app_strings, $beanList;

    $fields = array('' => $app_strings['LBL_NONE']);
    $unset = array();
    if ($view_module == '' || !isset($beanList[$view_module]) || !$beanList[$view_module]) {
        return $fields;
    }

    $mod = new $beanList[$view_module]();
    foreach ($mod->field_defs as $name => $arr) {
        if ($arr['type'] === 'link') {
            continue;
        }
        if ($name === 'currency_name' || $name === 'currency_symbol') {
            continue;
        }
        if (isset($arr['source']) && $arr['source'] === 'non-db' && 
            ($arr['type'] !== 'relate' || !isset($arr['id_name']))) {
            continue;
        }

        if (isset($arr['vname']) && $arr['vname'] !== '') {
            $fields[$name] = rtrim(translate($arr['vname'], $mod->module_dir), ':');
        } else {
            $fields[$name] = $name;
        }
        if ($arr['type'] === 'relate' && isset($arr['id_name']) && $arr['id_name'] !== '') {
            $unset[] = $arr['id_name'];
        }
    }

    foreach ($unset as $name) {
        if (isset($fields[$name])) {
            unset($fields[$name]);
        }
    }
    asort($fields);

    return $fields;
}


/**
 * Function to filter Customization panel
 * The function name must be the same as the relationship name in order to create linked records
 */
function stic_custom_views_stic_custom_view_customizations($params) {
    $args = func_get_args();
    $isInitial = $args[0]['is_initial'];

    global $app;
    $controller = $app->controller;
    $bean = $controller->bean;
    $customViewId = $bean->id;

    $query ="
        SELECT stic_custom_view_customizations.*
        FROM stic_custom_view_customizations 
        INNER JOIN stic_custom_views_stic_custom_view_customizations_c
            ON stic_custom_views_stic_custom_view_customizations_c.stic_custobdd5zations_idb = stic_custom_view_customizations.id
            AND stic_custom_views_stic_custom_view_customizations_c.deleted = '0'
            AND stic_custom_views_stic_custom_view_customizations_c.stic_custo45d1m_views_ida = '". $customViewId . "'
        INNER JOIN stic_custom_views
            ON stic_custom_views.id = stic_custom_views_stic_custom_view_customizations_c.stic_custo45d1m_views_ida
            AND stic_custom_views.deleted = '0'
        WHERE stic_custom_view_customizations.deleted = '0'
            AND stic_custom_view_customizations.is_initial = '" . $isInitial . "'
    ";
    return $query;
}

function getJsVars($view_module) {
    fillDynamicViewModuleLists($view_module);

    $fieldList = $GLOBALS ['app_list_strings']['dynamic_field_list'];
    $fieldListOptions = get_select_options_with_id($fieldList, "");
    
    $html = 
"<script>".
    "var view_module = \"".$view_module."\";".
    "var view_module_fields_option_list = \"".trim(preg_replace('/\s+/', ' ', $fieldListOptions))."\";".
"</script>";
    return $html;
}