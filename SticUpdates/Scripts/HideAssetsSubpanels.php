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

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * It is important that all the code included in this file can be executed in different updates, 
 * incorporating new operations, such as making new modules available. 
 **/

 $GLOBALS['log']->debug(__METHOD__.'('.__LINE__.') hide stic_Assets subpanel starting');

// HIDE MODULES FROM SUBPANELS IF MODULE IS NOT ENABLED ON THE MENU

// Set modules to hide
$modulesToHide = array(
    0 => 'stic_Assets',
);

require_once 'modules/MySettings/TabController.php';
$controller = new TabController();
$currentTabs = $controller->get_system_tabs();

$GLOBALS['log']->debug(__METHOD__.'('.__LINE__.') hide stic_Assets subpanel', $currentTabs);

$modulesToHideNotEnabled = array_diff($modulesToHide, $currentTabs);
$GLOBALS['log']->debug(__METHOD__.'('.__LINE__.') hide stic_Assets subpanel', $modulesToHideNotEnabled);
if (count($modulesToHideNotEnabled)> 0){
    // This function needs to be customized if the file is copied.
    function sticAssetsPrepareElementToHide(&$item, $key)
    {
        $item = strtolower($item);
    }
    
    array_walk($modulesToHideNotEnabled, 'sticAssetsPrepareElementToHide');
    
    $GLOBALS['log']->debug(__METHOD__.'('.__LINE__.') hide stic_Assets subpanel', $modulesToHideNotEnabled);
    
    $administration = new Administration();
    $currentSettings = $administration->retrieveSettings('MySettings');
    $unserialized = unserialize(base64_decode($currentSettings->settings['MySettings_hide_subpanels']));
    
    $GLOBALS['log']->debug(__METHOD__.'('.__LINE__.') hide stic_Assets subpanel', $unserialized);
    
    foreach($modulesToHideNotEnabled as $module) {
        $unserialized[$module] = $module;
    }
    
    $serialized = base64_encode(serialize($unserialized));
    $administration->saveSetting('MySettings', 'hide_subpanels', $serialized);

}



// Repairing and rebuilding
global $current_user;
$current_user = new User();
$current_user->getSystemUser();

// Reparación de roles para garantizar que los usuarios no administradores pueden acceder a los módulos
echo '<h3>Repairing roles</h3>';
require_once 'modules/ACL/install_actions.php';
$GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Repairing roles');

// Reparamos también relaciones e índices para evitar incidencias con los nuevos módulos
echo '<h3>Rebuilding relationships</h3>';
require_once 'modules/Administration/RebuildRelationship.php';
$GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Rebuilding relationships');

echo '<h3>Repairing indexes</h3>';
require_once "modules/Administration/RepairIndex.php";
$GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Repairing indexes');

