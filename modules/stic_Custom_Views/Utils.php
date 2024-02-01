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

/**
 * Function to filter Customization panel
 * The function name must be the same as the relationship name in order to create linked records
 */
function stic_custom_views_stic_custom_view_customizations($params) {
    $args = func_get_args();
    $is_default = $args[0]['is_default'];

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
            AND stic_custom_view_customizations.is_default = '" . $is_default . "'
    ";
    return $query;
}

function getJsVars($viewModule, $viewModuleView) {
    require_once('modules/stic_Custom_Views/stic_Custom_Views_ModuleView.php');
    global $app_list_strings;

    $moduleView = new stic_Custom_Views_ModuleView($viewModule, $viewModuleView);

    $fieldListOptions = $moduleView->getOnlyViewFields_as_select_options();
    $fieldOPeratorMapOptions = $moduleView->getOnlyViewFieldOperatorMap_as_select_options();

    $html = 
"<script>".
    "var view_module = \"".$viewModule."\";".
    "var view_module_view = \"".$viewModuleView."\";".
    "var view_module_fields_option_list = \"".$fieldListOptions."\";".
    "var view_module_fields_operators_option_map = {};";
    foreach ($fieldOPeratorMapOptions as $fieldKey => $operatorOptions) {
        $html .= "view_module_fields_operators_option_map['".$fieldKey."'] = \"".$operatorOptions."\";";
    }
    $html .=
    "var view_module_action_map = {".
        "actionTypes: {".
            "options: \"".$moduleView->getActionTypes_as_select_options()."\",";
        foreach($moduleView->getActionTypes() as $actionTypeKey=>$actionTypeName) {
            $html .=
            $actionTypeKey.": {".
                "elements: {".
                    "options: \"".$moduleView->getValidElements_as_select_options($actionTypeKey)."\",".
                "},".
                "actions: {".
                    "options: \"".$moduleView->getValidActions_as_select_options($actionTypeKey)."\",";
                    foreach($moduleView->getValidActions($actionTypeKey) as $actionKey => $actionName) {
                        $html .=
                        $actionKey.": {".
                            "sections: {".
                                "options: \"".$moduleView->getValidSections_as_select_options($actionTypeKey, $actionKey)."\",".
                            "},".
                        "},";
                    }
                    $html .=
                "},".
            "},";
        }
        $html .=
        "},".
    "};";
    $html .=     
"</script>";
    return $html;
}
