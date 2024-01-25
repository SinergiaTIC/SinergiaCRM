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

function displayConditionLines($focus, $field, $value, $view) {
    global $mod_strings;

    $html = 
"<table cellpadding='0' cellspacing='0' border='0' width='100%' id='stic_custom_view_conditionLines'></table>".
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
    global $locale, $app_list_strings, $mod_strings;

    $html = '';

    if (!is_file('cache/jsLanguage/AOW_Actions/' . $GLOBALS['current_language'] . '.js')) {
        require_once('include/language/jsLanguage.php');
        jsLanguage::createModuleStringsCache('AOW_Actions', $GLOBALS['current_language']);
    }
    $html .= '<script src="cache/jsLanguage/AOW_Actions/'. $GLOBALS['current_language'] . '.js"></script>';

    if ($view == 'EditView' || $view == 'QuickCreate') {

        $aow_actions_list = array();

        include_once('modules/AOW_Actions/actions.php');

        $app_list_actions[''] = '';
        foreach ($aow_actions_list as $action_value) {
            $action_name = 'action'.$action_value;

            if (file_exists('custom/modules/AOW_Actions/actions/'.$action_name.'.php')) {
                require_once('custom/modules/AOW_Actions/actions/'.$action_name.'.php');
            } elseif (file_exists('modules/AOW_Actions/actions/'.$action_name.'.php')) {
                require_once('modules/AOW_Actions/actions/'.$action_name.'.php');
            } else {
                continue;
            }

            $action = new $action_name();
            foreach ($action->loadJS() as $js_file) {
                $html .= '<script src="'.$js_file.'"></script>';
            }

            $app_list_actions[$action_value] = translate('LBL_'.strtoupper($action_value), 'AOW_Actions');
        }

        $html .= '<input type="hidden" name="app_list_actions" id="app_list_actions" value="'.get_select_options_with_id($app_list_actions, '').'">';

        $html .= "<table style='padding-top: 10px; padding-bottom:10px;' id='actionLines'></table>";

        $html .= "<div style='padding-top: 10px; padding-bottom:10px;'>";
        $html .= "<input type=\"button\" tabindex=\"116\" class=\"button\" value=\"".$mod_strings['LBL_ADD_ACTION']."\" id=\"btn_ActionLine\" onclick=\"insertActionLine()\"/>";
        $html .= "</div>";

        if (isset($focus->flow_module) && $focus->flow_module != '') {
            $html .= "<script>document.getElementById('btn_ActionLine').disabled = '';</script>";
            if ($focus->id !== '') {
                $idQuoted = $focus->db->quoted($focus->id);
                $sql = 'SELECT id FROM aow_actions WHERE aow_workflow_id = ' . $idQuoted . ' AND deleted = 0 ORDER BY action_order ASC';
                $result = $focus->db->query($sql);

                while ($row = $focus->db->fetchByAssoc($result)) {
                    $action_name = BeanFactory::newBean('AOW_Actions');
                    $action_name->retrieve($row['id']);
                    $action_item = json_encode($action_name->toArray());

                    $html .= "<script>
                            loadActionLine(".$action_item.");
                        </script>";
                }
            }
        }
    } elseif ($view == 'DetailView') {
        $html .= "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
        $idQuoted = $focus->db->quoted($focus->id);
        $sql = 'SELECT id FROM aow_actions WHERE aow_workflow_id = ' . $idQuoted . ' AND deleted = 0 ORDER BY action_order ASC';
        $result = $focus->db->query($sql);

        while ($row = $focus->db->fetchByAssoc($result)) {
            $action_name = BeanFactory::newBean('AOW_Actions');
            $action_name->retrieve($row['id']);

            $html .= "<tr><td>". $action_name->action_order ."</td><td>".$action_name->name."</td><td>". translate('LBL_'.strtoupper($action_name->action), 'AOW_Actions')."</td></tr>";
        }
        $html .= "</table>";
    }
    return $html;
}

