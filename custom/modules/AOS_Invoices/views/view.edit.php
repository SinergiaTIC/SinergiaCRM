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
require_once 'modules/AOS_Invoices/views/view.edit.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewEdit extends AOS_InvoicesViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;

        // There is an issue if we enable the following line. The quickcreate will show a double panel duplicating fields.
        // This also happens in the latest non-customized version of SuiteCRM. 16/06/2020 TODO
        // $this->useModuleQuickCreateTemplate = true;

        // Since the suite base modules name the bean in the singular, we configure in the view the name of the module in the plural. This property will be used by the SticViews class to load the language files
        $this->moduleName = 'AOS_Invoices';
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        $bean = $this->bean;
        $isDuplicate = (!empty($_REQUEST['mass_duplicate']) && $_REQUEST['mass_duplicate'] == '1')
            || (!empty($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === 'true')
            || (!empty($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] === 'true');

        if (!$isDuplicate && !empty($bean->verifactu_aeat_status_c) &&
            in_array($bean->verifactu_aeat_status_c, array('accepted', 'emitted'))) {

            if (!empty($bean->id)) {
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $bean->id);
            }
        }
    }

    public function display()
    {
        global $sugar_config;

        // === Verifactu Activation Banner ===
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        $verifactuStatus = AOS_InvoicesUtils::getVerifactuStatus();

        if (!empty($verifactuStatus['warning'])) {
            global $mod_strings;
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }

            echo '<div class="alert alert-info" style="margin: 10px 0; padding: 12px; border-left: 4px solid #5bc0de; background-color: #d9edf7;">
                <strong><span class="suitepicon suitepicon-action-info"></span> ' . $verifactuStatus['warning'] . '</strong>
                <br><a href="index.php?module=stic_Settings&action=index">' . ($mod_strings['LBL_VERIFACTU_ACTIVATED_LINK'] ?? 'Configurar Verifactu') . '</a>
            </div>';
        }
        // === End Verifactu Activation Banner ===

        $seriesConfig = $sugar_config['aos']['invoices']['series'] ?? [];
        $seriesForJs = [];
        foreach ($seriesConfig as $name => $config) {
            $seriesForJs[] = [
                'name' => $name,
                'isRectified' => !empty($config['isRectified']),
            ];
        }
        echo '<script>var sticSeriesConfig = ' . json_encode($seriesForJs) . ';</script>';

        parent::display();

        SticViews::display($this);

        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }

}