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
    require_once("modules/ModuleBuilder/Module/StudioModuleFactory.php");
    require_once('modules/ModuleBuilder/parsers/ParserFactory.php') ;

    $studioModule = StudioModuleFactory::getStudioModule($view_module);

    $availableViews = array("detailview", "editview");
    $views = $studioModule->getViews();
    $dynamic_view_list = array();
    $dynamic_view_panel_map = array();
    foreach($views as $view) {
        $viewKey = !empty($view['view']) ? $view['view'] : $view['type'];
        if (in_array($viewKey, $availableViews)) {
            $dynamic_view_list[$viewKey] = $view['name'];
            $dynamic_view_panel_map[$viewKey] = array();
        }
    }
    $GLOBALS ['app_list_strings']['dynamic_view_list'] = $dynamic_view_list;

    foreach ($dynamic_view_list as $viewKey => $viewName) {
        $parser = ParserFactory::getParser($viewKey, $view_module, null);
        $parsedPanels = $parser->getTabDefs();
        foreach ($parsedPanels as $panelKey => $panelDef) {
            $dynamic_view_panel_map[$viewKey][$panelKey] = translate($panelKey, $view_module);
        }
    }
    $GLOBALS ['app_list_strings']['dynamic_view_panel_map'] = $dynamic_view_panel_map;

    $dynamic_panel_list = array();
    foreach ($dynamic_view_panel_map as $viewKey => $panels) {
        $viewName = $dynamic_view_list[$viewKey];
        foreach ($panels as $panelKey => $panelName) {
            $dynamic_panel_list[$viewKey.".".$panelKey] = $viewName." - ".$panelName;
        }
    }
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

    $dynamic_field_list = $GLOBALS ['app_list_strings']['dynamic_field_list'];
    $fieldListOptions = get_select_options_with_id($dynamic_field_list, "");
    $dynamic_view_list = $GLOBALS ['app_list_strings']['dynamic_view_list'];
    $viewListOptions = get_select_options_with_id($dynamic_view_list, "");
    $dynamic_panel_list = $GLOBALS ['app_list_strings']['dynamic_panel_list'];
    $panelListOptions = get_select_options_with_id($dynamic_panel_list, "");

    $dynamic_view_panel_map = $GLOBALS ['app_list_strings']['dynamic_view_panel_map'];
    $viewPanelMapOptions = array();
    foreach($dynamic_view_panel_map as $viewKey => $panel_list) {
        $viewPanelMapOptions[$viewKey] = get_select_options_with_id($panel_list, "");
    }

    $html = 
"<script>".
    "var view_module = \"".$view_module."\";".
    "var view_module_fields_option_list = \"".trim(preg_replace('/\s+/', ' ', $fieldListOptions))."\";".
    "var view_module_views_option_list = \"".trim(preg_replace('/\s+/', ' ', $viewListOptions))."\";".
    "var view_module_panels_option_list = \"".trim(preg_replace('/\s+/', ' ', $panelListOptions))."\";".
    "var view_module_views_panels_option_map = {};";
    foreach ($viewPanelMapOptions as $viewKey => $panelOptions) {
        $html .= "view_module_views_panels_option_map['".$viewKey."'] = \"".trim(preg_replace('/\s+/', ' ', $panelOptions))."\";";
    }
    $html .= 
"</script>";
    return $html;
}