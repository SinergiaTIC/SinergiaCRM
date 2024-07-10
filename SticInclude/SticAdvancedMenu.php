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
 * Generates an HTML menu from a list of items, filtering out invalid nodes.
 *
 * This function builds an HTML list for a navigation menu using a recursive
 * structure. It filters out menu items that don't correspond to valid modules
 * and don't have valid children. It also handles custom URLs embedded in menu items.
 * The function relies on global variables to obtain the corresponding texts for 
 * the menu items and respects configuration for displaying icons and the "All" menu.
 *
 * @param array $items The menu items to process, each item can contain subitems and custom URLs.
 * @param bool $isFirstLevel Indicates if it's the top level of the menu.
 * @param array|null $validTabs Array of valid tabs/modules for the current user.
 * @return string The generated HTML code for the menu.
 */
function generateMenu($items, $isFirstLevel = true, $validTabs = null)
{
    global $app_list_strings, $app_strings, $current_user, $sugar_config;

    // Initialize valid tabs if not provided
    if ($validTabs === null) {
        require_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $validTabs = $controller->get_tabs($current_user)[0];
        foreach ($validTabs as $key => $value) {
            $validTabs[$key] = $app_list_strings['moduleList'][$key];
        }
        asort($validTabs);
    }

    $html = '';
    $validItemsCount = 0;

    foreach ($items as $item) {
        // Get the display text for the menu item
        $text = ($app_list_strings['moduleList'][$item['id']] ?? $app_strings[$item['id']] ?? str_replace('_', ' ', $item['id']));

        // If item contains URL, extract it and cut text
        $itemURL = extractUrl($text);
        if ($itemURL) {
            $text = str_replace("|{$itemURL}", '', $text);
        }

        $hasChildren = isset($item['children']) && is_array($item['children']) && !empty($item['children']);
        $isValidModule = array_key_exists($item['id'], $validTabs);

        // Recursively generate HTML for child items
        $childrenHtml = '';
        if ($hasChildren) {
            $childrenHtml = generateMenu($item['children'], false, $validTabs);
        }

        // Only include valid modules, items with valid children, or items with custom URLs
        if ($isValidModule || !empty($childrenHtml) || $itemURL) {
            $validItemsCount++;
            $itemHtml = '<li' . ($hasChildren ? ' class="dropdown"' : '') . '>';

            if ($isValidModule) {
                // Generate link for valid modules
                $lowerModule = str_replace('_', '-', strtolower($item['id']));
                // Include icon if enabled in configuration
                $iconString = $sugar_config['stic-advanced-menu-icons'] ? "<span class='suitepicon suitepicon-module-{$lowerModule}'></span>" : '';
                $itemHtml .= "<a href='index.php?module={$item['id']}&action=index&return_module=Accounts&return_action=DetailView'>$iconString $text </a>";
            } elseif ($hasChildren) {
                // Generate dropdown toggle for items with children
                $itemHtml .= "<a href='#' class='no-link'>" . $text . '</a>';
            } elseif($itemURL){
                // Generate external link for items with custom URLs
                $itemHtml .= "<a title='$itemURL' target='_blank' href='$itemURL'><span class='glyphicon glyphicon-link'></span> $text </a>";
            }
            
            $itemHtml .= $childrenHtml;
            $itemHtml .= '</li>';
            $html .= $itemHtml;
        }
    }

    // Wrap the menu items in a <ul> if there are valid items or it's the first level
    if ($validItemsCount > 0 || $isFirstLevel) {
        $menuHtml = $isFirstLevel ? '<ul id="stic-menu" class="sm sm-stic">' : '<ul>';
        if ($isFirstLevel) {
            // Add home link at the first level
            $menuHtml .= '<li><a href="index.php?module=Home&action=index"><i class="glyphicon glyphicon-home"></i></a></li>';
        }
        $menuHtml .= $html;

        // Add the "All" menu if it's the first level and enabled in configuration
        if ($isFirstLevel && $sugar_config['stic-advanced-menu-all']) {
            $menuHtml .= '<li class="dropdown">';
            $menuHtml .= "<a href='#' class='no-link'>{$app_strings['LBL_TABGROUP_ALL']} </a>";
            $menuHtml .= '<ul>';
            $menuHtml .= '<li><input type="text" id="search-all" placeholder="' . $app_strings['LBL_SEARCH'] . '"></input></li>';
            foreach ($validTabs as $key => $value) {
                $menuHtml .= "<li><a href='index.php?module={$key}&action=index&return_module=Accounts&return_action=DetailView'>" . $value . '</a></li>';
            }
            $menuHtml .= '</ul>';
            $menuHtml .= '</li>';
        }

        $menuHtml .= '</ul>';
        return $menuHtml;
    }

    return '';
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


/**
 * Extracts a URL from the end of a string if preceded by a pipe character ('|').
 *
 * Matches URLs with or without 'http(s)://' prefix, including domain and optional path.
 *
 * @param string $string Input string to search for a URL.
 * @return string|null Extracted URL if found, null otherwise.
 */
function extractUrl($string)
{
    $pattern = '/\|((https?:\/\/)?([a-z0-9\.-]+)(:\d+)?(\/[^?#]*)?(\?[^#]*)?(#.*)?$)/i';

    if (preg_match($pattern, $string, $matches)) {
        return $matches[1];
    }

    return null;
}