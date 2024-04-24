<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

global $app_list_strings, $app_strings, $mod_strings;
// var_dump($app_strings);die();
require_once 'modules/Studio/TabGroups/TabGroupHelper.php';
require_once 'modules/Studio/parsers/StudioParser.php';

$tabGroupSelected_lang = (!empty($_GET['lang']) ? $_GET['lang'] : $_SESSION['authenticated_user_language']);
$tg = new TabGroupHelper();
$smarty = new Sugar_Smarty();
if (empty($GLOBALS['tabStructure'])) {
    require 'include/tabConfig.php';
}
$title = getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_CONFIGURE_GROUP_TABS']), false);

#30205
$selectedAppLanguages = return_application_language($tabGroupSelected_lang);
require_once 'include/GroupedTabs/GroupedTabStructure.php';
$availableModules = $tg->getAvailableModules($tabGroupSelected_lang);
$smarty->assign('availableModuleList', $availableModules);
$modList = array_keys($availableModules);
$modList = array_combine($modList, $modList); // Bug #48693 We need full list of modules here instead of displayed modules

include_once 'custom/include/SticTabConfig.php';

if (isset($GLOBALS["SticTabStructure"])) {
    $menu = $GLOBALS["SticTabStructure"];
    addTextProperty($menu);

} else {

    $groupedTabsClass = new GroupedTabStructure();
    $groupedTabStructure = $groupedTabsClass->get_tab_structure($modList, '', true, true);
    $menu = [];
    foreach ($groupedTabStructure as $mainTab => $subModules) {
        $children = [];
        foreach (array_keys($subModules['modules']) as $key) {
            $children[] = ['id' => $key, 'text' => $app_list_strings['moduleList'][$key]];
        }

        $menu[] = [
            'id' => $mainTab,
            'text' => $app_strings[$mainTab],
            'children' => $children,
        ];
    }
}

$jsonMenu = json_encode($menu, JSON_UNESCAPED_UNICODE);
$smarty->assign('jsonMenu', $jsonMenu);

$allModules = [];
foreach ($availableModules as $key => $value) {
    if (!findIdInArray($menu, $key)) {
        $allModules[] = ['id' => $key, 'text' => $value['label']];
    }

}
usort($allModules, function ($a, $b) {return strcmp($a['text'], $b['text']);});

$jsonAll = json_encode($allModules, JSON_UNESCAPED_UNICODE);
$smarty->assign('jsonAll', $jsonAll);

$smarty->assign('renderedMenu', generateMenu($menu));

$smarty->assign('tabs', $groupedTabStructure);
#end of 30205
$selectedLanguageModStrings = return_module_language($tabGroupSelected_lang, 'Studio');
$smarty->assign('TGMOD', $selectedLanguageModStrings);
$smarty->assign('MOD', $GLOBALS['mod_strings']);
$smarty->assign('otherLabel', 'LBL_TABGROUP_OTHER');
$selected_lang = (!empty($_REQUEST['dropdown_lang']) ? $_REQUEST['dropdown_lang'] : $_SESSION['authenticated_user_language']);
if (empty($selected_lang)) {
    $selected_lang = $GLOBALS['sugar_config']['default_language'];
}

$smarty->assign('dropdown_languages', get_languages());

$imageSave = SugarThemeRegistry::current()->getImage('studio_save', '', null, null, '.gif', $mod_strings['LBL_SAVE']);

$buttons = array();
$buttons[] = array('text' => $GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'], 'actionScript' => "onclick='studiotabs.generateForm(\"edittabs\");document.edittabs.submit()'");
$html = "";
foreach ($buttons as $button) {
    $html .= "<td><input type='button' valign='center' class='button' style='cursor:pointer' onmousedown='this.className=\"buttonOn\";return false;' onmouseup='this.className=\"button\"' onmouseout='this.className=\"button\"' {$button['actionScript']} value = '{$button['text']}' ></td>";
}
$smarty->assign('buttons', $html);
$smarty->assign('title', $title);
$smarty->assign('dropdown_lang', $selected_lang);

$editImage = SugarThemeRegistry::current()->getImage('edit_inline', '', null, null, '.gif', $mod_strings['LBL_EDIT']);
$smarty->assign('editImage', $editImage);
$deleteImage = SugarThemeRegistry::current()->getImage('delete_inline', '', null, null, '.gif', $mod_strings['LBL_MB_DELETE']);
$recycleImage = SugarThemeRegistry::current()->getImage('icon_Delete', '', 48, 48, '.gif', $mod_strings['LBL_MB_DELETE']);
$smarty->assign('deleteImage', $deleteImage);
// $smarty->assign('recycleImage', $recycleImage);

//#30205
global $sugar_config;
if (isset($sugar_config['other_group_tab_displayed'])) {
    if ($sugar_config['other_group_tab_displayed']) {
        $value = 'checked';
    } else {
        $value = null;
    }
    $smarty->assign('other_group_tab_displayed', $value);
} else {
    $smarty->assign('other_group_tab_displayed', 'checked');
}
$smarty->assign('tabGroupSelected_lang', $tabGroupSelected_lang);

$smarty->assign('available_languages', get_languages());
$smarty->display("custom/modules/Studio/TabGroups/EditViewTabsNew.tpl");

function findIdInArray($array, $idToFind)
{
    foreach ($array as $element) {
        if (is_array($element)) {
            if (array_key_exists('id', $element) && $element['id'] === $idToFind) {
                return true;
            }
            if (isset($element['children']) && findIdInArray($element['children'], $idToFind)) {
                return true;
            }
        }
    }
    return false;
}

function addTextProperty(&$array)
{
    global $app_list_strings, $app_strings;
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            if (isset($value['id'])) {
                $value['text'] = ($app_list_strings['moduleList'][$value['id']] ?? '');
                if (empty($value['text'])) {
                    $value['text'] = $app_strings[$value['id']];
                }
                if (empty($value['text'])) {
                    $value['text'] = str_replace('_', ' ', $value['id']);
                }
            }
            addTextProperty($value); // Recursivamente procesar sub-arrays
        }
    }
}

function generateMenu($items)
{
    global $app_list_strings, $app_strings;
    $html = '<ul id="main-menu" class="sm sm-blue">';
    foreach ($items as $item) {

        $text = ($app_list_strings['moduleList'][$item['id']] ?? '');
        if (empty($text)) {
            $text = $app_strings[$item['id']];
        }
        if (empty($text)) {
            $text = str_replace('_', ' ', $item['id']);
        }

        $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
        $html .= '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>';
        $html .= '<a href="#">' . $text . '</a>';
        if ($hasChildren) {
            $html .= generateMenu($item['children']);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
