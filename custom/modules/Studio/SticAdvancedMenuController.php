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

switch ($_POST['manageMode']) {
    case 'save':
        // Decodificas los caracteres HTML
        $decodedJson = html_entity_decode($_POST['menuJson']);

        // Convirtiendo el JSON decodificado en un array de PHP
        $GLOBALS["SticTabStructure"] = json_decode($decodedJson, true);

        //Write the tabstructure to custom so that the grouping are not shown for the un-selected scenarios
        $fileContents = "<?php \n" . '$GLOBALS["SticTabStructure"] =' . var_export($GLOBALS['SticTabStructure'], true) . ';';
        sugar_file_put_contents('custom/include/AdvancedTabConfig.php', $fileContents);
        ob_clean();
        SugarApplication::appendSuccessMessage("<div id='saved-notice' class='alert alert-success' role='alert'>{$app_strings['LBL_SAVED']}</div>");

        // Save options in sugar_config
        require_once 'modules/Configurator/Configurator.php';
        $configurator = new Configurator();

        $configurator->config['stic-advanced-menu-icons'] = $_POST['sticAdvancedMenuIcons'];
        $configurator->config['stic-advanced-menu-all'] = $_POST['sticAdvancedMenuAll'];
        // $configurator->config['stic-advanced-menu-icons'] = false;
        // $configurator->config['stic-advanced-menu-all'] = $_POST['sticAdvancedMenuAll'] ? 'true' : 'false';
        $configurator->saveConfig();

        die('ok');

        break;

    case 'restore':
        unlink('custom/include/AdvancedTabConfig.php');
        unset($GLOBALS["SticTabStructure"]);
        die('ok');
        break;

    default:
        die('no action!');
        break;
}
