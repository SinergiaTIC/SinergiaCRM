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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
if ($_REQUEST['manageMode'] ?? false) {
    switch ($_REQUEST['manageMode']) {
        case 'save':
            // Decode HTML entities in the JSON string
            $decodedJson = html_entity_decode($_POST['menuJson']);
            
            // Convert the decoded JSON to a PHP array
            $GLOBALS["SticTabStructure"] = json_decode($decodedJson, true);

            // Write the tab structure to a custom file
            $fileContents = "<?php \n" . '$GLOBALS["SticTabStructure"] =' . var_export($GLOBALS['SticTabStructure'], true) . ';';
            
            sugar_file_put_contents('custom/include/AdvancedTabConfig.php', $fileContents);
            ob_clean();
            SugarApplication::appendSuccessMessage("<div id='saved-notice' class='alert alert-success' role='alert'>{$app_strings['LBL_SAVED']}</div>");

            // Save options in sugar_config
            require_once 'modules/Configurator/Configurator.php';
            $configurator = new Configurator();

            // Update configuration with new menu settings
            $configurator->config['stic_advanced_menu_icons'] = $_POST['sticAdvancedMenuIcons'];
            $configurator->config['stic_advanced_menu_all'] = $_POST['sticAdvancedMenuAll'];
            $configurator->saveConfig();

            die('ok');
            break;
        case 'legacy_mode':

            // Disable advanced menu
            require_once 'modules/Configurator/Configurator.php';
            $configurator = new Configurator();
            $configurator->config['stic_advanced_menu_enabled'] = false;
            $configurator->saveConfig();

            die('ok');
            break;
        case 'advanced_mode':

            // Enable advanced menu
            require_once 'modules/Configurator/Configurator.php';
            $configurator = new Configurator();
            $configurator->config['stic_advanced_menu_enabled'] = true;
            $configurator->saveConfig();
            SticAdvancedMenu::ConvertSuiteCRMMenuToAdvancedMenu();

            die('ok');
            break;
        case 'restore':
            // Remove custom tab configuration and reset global variable
            unlink('custom/include/AdvancedTabConfig.php');
            unset($GLOBALS["SticTabStructure"]);
            die('ok');
            break;

        default:
            die('no action!');
            break;
    }
}

/**
 * Class SticAdvancedMenu
 *
 * This class provides functionality to convert and manage advanced menu structures.
 */
class SticAdvancedMenu
{
    /**
     * Converts the existing SuiteCRM menu structure to a new advanced format.
     *
     * This function reads the current tab configuration, transforms it using convertFromSuiteCRMMenu(),
     * and saves the new structure to custom/include/AdvancedTabConfig.php.
     * The conversion reorganizes the menu groups into a numerically indexed array
     * with 'id' and 'children' properties for each group.
     *
     * @return bool Returns true after completion, regardless of the outcome.
     */
    public static function ConvertSuiteCRMMenuToAdvancedMenu()
    {
        if (file_exists('custom/include/tabConfig.php')) {
            require_once 'custom/include/tabConfig.php';

            $newTabStructure = self::convertFromSuiteCRMMenu($GLOBALS['tabStructure']);

            $fileContent = "<?php\n\n";
            $fileContent .= "\$GLOBALS[\"SticTabStructure\"] = " . var_export($newTabStructure, true) . ";\n";

            $filePath = 'custom/include/AdvancedTabConfig.php';

            if (file_put_contents($filePath, $fileContent)) {
                echo "File successfully saved to $filePath";
            } else {
                echo "An error occurred while saving the file";
            }
        }
        $GLOBALS['log']->info(__METHOD__ . '(' . __LINE__ . ') Successfully switched to advanced menu');
        
        return true;
    }

    /**
     * Converts the SuiteCRM menu structure to the new advanced tab structure.
     *
     * This function transforms the original SuiteCRM tab structure into a new format
     * where each group is represented as an array with 'id' and 'children' properties.
     * The 'children' property contains an array of modules, each represented by its ID.
     *
     * @param array $tabStructure The original SuiteCRM tab structure
     * @return array The new advanced tab structure
     */
    public static function convertFromSuiteCRMMenu($tabStructure)
    {
        $newStructure = array();
        $index = 0;

        foreach ($tabStructure as $groupId => $group) {
            $newGroup = array(
                'id' => $groupId,
                'children' => array(),
            );

            foreach ($group['modules'] as $moduleId) {
                $newGroup['children'][] = array('id' => $moduleId);
            }

            $newStructure[$index] = $newGroup;
            $index++;
        }

        return $newStructure;
    }
}
