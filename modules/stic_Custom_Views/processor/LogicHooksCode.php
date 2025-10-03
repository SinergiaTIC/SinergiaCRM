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
class stic_Custom_Views_ProcessorLogicHooks
{
    public function after_ui_frame($event, $arguments)
    {
        require_once 'modules/stic_Custom_Views/processor/stic_Custom_Views_Processor.php';

        $action = strtolower($GLOBALS['action']);
        $view = $action;
        $module = $GLOBALS['module'];
        if ($action == "subpanelcreates") {
            $view = "quickcreate";
            $module = $_POST["target_module"];
        } else if ($action == "popup") {
            $view = "quickcreate";
        }
        
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'after_ui_frame; Module:'.$module.'; View:'.$view);
        $processor = new stic_Custom_Views_Processor($module, $view);
        echo $processor->getHtmlScriptForCustomViews();

        return "";
    }
}
