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

/**
 * Generates an HTML menu from a list of items.
 *
 * This function builds an HTML list for a navigation menu using a recursive
 * structure if the elements have submenus. It relies on global variables
 * to obtain the corresponding texts for the menu items.
 *
 * @param array $items The menu items to process, each item can contain subitems.
 * @param bool $isFirstLevel Indicates if it's the top level of the menu.
 * @return string The generated HTML code for the menu.
 */
function generateMenu($items, $isFirstLevel = true)
{
    require_once 'modules/MySettings/TabController.php';

    global $app_list_strings, $app_strings, $current_user; // Access globally defined string lists.
    $controller = new TabController();
    // $tabs includes only the modules that the current user can see based on their roles and profile settings.
    $tabs = $controller->get_tabs($current_user)[0];
    foreach ($tabs as $key => $value) {
        $tabs[$key] = $app_list_strings['moduleList'][$key];
    }
    asort($tabs);

    // Start building the main menu.
    if ($isFirstLevel) {
        $html = '<ul id="stic-menu" class="sm sm-stic">';
        $html .= '<li><a href="index.php?module=Home&action=index"><i class="glyphicon glyphicon-home"></i></a></li>';
    } else {
        $html = '<ul>';
    }

    foreach ($items as $item) {
        // Try to get the item text from module strings or use the id as a last resort.
        $text = ($app_list_strings['moduleList'][$item['id']] ?? ''); // First attempt with moduleList.
        if (empty($text)) {
            $text = $app_strings[$item['id']]; // Second attempt with app_strings.
        }
        if (empty($text)) {
            $text = str_replace('_', ' ', $item['id']); // Use the item ID, replacing underscores with spaces.
        }

        // Determine if the current item has submenus.
        $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;

        // Only include links for nodes whose id is in the module list
        // This way we exclude links for nodes that don't point to valid modules
        if (array_key_exists($item['id'], $tabs)) {
            $html .= '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>'; // Add 'dropdown' class if there are submenus.
            $lowerModule = str_replace('_', '-', strtolower($item['id']));
            $html .= "<a href='index.php?module={$item['id']}&action=index&return_module=Accounts&return_action=DetailView'><span class='suitepicon suitepicon-module-{$lowerModule}'></span> $text </a>"; // Create a link for the menu item.
        } else {
            if ($hasChildren) {
                $html .= '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>'; // Add 'dropdown' class if there are submenus.
                $html .= "<a href='#' class='no-link'>" . $text . '</a>'; // Create a link for the menu item.
            }
        }

        if ($hasChildren) {
            $html .= generateMenu($item['children'], false); // Recursively generate menus for subitems.
        }
    }

    // Add the "ALL" menu including a search for modules
    if ($isFirstLevel) {
        $html .= '<li class="dropdown">';
        $html .= "<a href='#' class='no-link'>{$app_strings['LBL_TABGROUP_ALL']} </a>";
        $html .= '<ul>';
        $html .= '<li><input type="text" id="search-all" placeholder="' . $app_strings['LBL_SEARCH'] . '"></input></li>';
        foreach ($tabs as $key => $value) {
            $html .= "<li><a href='index.php?module={$key}&action=index&return_module=Accounts&return_action=DetailView'>" . $value . '</a></li>';
        }
        $html .= '</ul>';
    }

    $html .= '</ul>'; // Finalize the menu list.

    return $html; // Return the constructed HTML.
}

/**
 * Adds text properties to menu items based on their IDs.
 *
 * This function recursively processes an array of menu items, adding a 'text'
 * property to each item based on its 'id'. It uses global string lists to
 * find the appropriate text for each menu item.
 *
 * @param array &$array The array of menu items to process.
 */
function addMenuProperties(&$array)
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
            addMenuProperties($value); // Recursively process sub-arrays
        }
    }
}
