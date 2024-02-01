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

require_once('include/EditView/SubpanelQuickCreate.php');

class stic_Custom_View_CustomizationsSubpanelQuickCreate extends SubpanelQuickCreate
{
    public function __construct($module, $view='QuickCreate', $proccessOverride = false)
    {
        parent::__construct($module, $view, $proccessOverride);
    }

    public function process($module)
    {
        // Remove 'SUBPANELFULLFORM' button
        if (($key = array_search('SUBPANELFULLFORM', $this->ev->defs['templateMeta']['form']['buttons'])) !== false) {
            unset($this->ev->defs['templateMeta']['form']['buttons'][$key]);
        }

        parent::process($module);

        // Load related lang strings
        $moduleNames = array('stic_Custom_View_Customizations', 'stic_Custom_View_Conditions', 'stic_Custom_View_Actions');
        foreach($moduleNames as $moduleName) {
            if (!is_file("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js")) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache($moduleName, $GLOBALS['current_language']);
            }
            echo getVersionedScript("cache/jsLanguage/{$moduleName}/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);
        }

        echo getVersionedScript("modules/stic_Custom_View_Customizations/Utils.js");
    }
}
