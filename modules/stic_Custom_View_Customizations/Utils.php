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

function getCustomView($customizationBean) {
    return SticUtils::getRelatedBeanObject($customizationBean, "stic_custom_views_stic_custom_view_customizations");
}

function getLangStrings() {
    $html = "";
    // Load related lang strings
    $moduleNames = array('stic_Custom_View_Customizations', 'stic_Custom_View_Conditions', 'stic_Custom_View_Actions');
    foreach($moduleNames as $moduleName) {
        if (!is_file("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js")) {
            require_once('include/language/jsLanguage.php');
            jsLanguage::createModuleStringsCache($moduleName, $GLOBALS['current_language']);
        }
        $html.= getVersionedScript("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);
    }
    return $html;
}

function displayConditionLines($focus, $field, $value, $view) {
    global $mod_strings;

    $html = 
"<table cellpadding='0' cellspacing='0' border='0' style='table-layout:fixed; width:100%;'".
    "id='sticCustomView_ConditionLines' class='sticCustomView_Lines'></table>".
"<div style='padding-top: 10px; padding-bottom:10px;'>".
    "<input type='button' class='button' tabindex='116' ".
           "value=\"".$mod_strings['LBL_ADD_CONDITION']."\" ".
           "id='btn_ConditionLine' onclick='insertConditionLine()'/>".
"</div>";

    $conditionBeanArray = SticUtils::getRelatedBeanObjectArray($focus, "stic_custom_view_customizations_stic_custom_view_conditions");
    if(!empty($conditionBeanArray)) {
        $html .= "<script>";
        foreach ($conditionBeanArray as $conditionBean) {
            $html .= "loadConditionLine(".json_encode($conditionBean->toArray()).");";
        }
        $html .= "</script>";
    }
    return $html;
}


function displayActionLines(SugarBean $focus, $field, $value, $view) {
    global $mod_strings;

    $html = 
"<table cellpadding='0' cellspacing='0' border='0' style='table-layout:fixed; width:100%;'".
       "id='sticCustomView_ActionLines' class='sticCustomView_Lines'></table>".
"<div style='padding-top: 10px; padding-bottom:10px;'>".
    "<input type='button' class='button' tabindex='116' ".
           "value=\"".$mod_strings['LBL_ADD_ACTION']."\" ".
           "id='btn_ActionLine' onclick='insertActionLine()'/>".
"</div>";

    $actionBeanArray = SticUtils::getRelatedBeanObjectArray($focus, "stic_custom_view_customizations_stic_custom_view_actions");
    if(!empty($actionBeanArray)) {
        $html .= "<script>";
        foreach ($actionBeanArray as $actionBean) {
            $html .= "loadActionLine(".json_encode($actionBean->toArray()).");";
        }
        $html .= "</script>";
    }
    return $html;
}

