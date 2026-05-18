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
require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewList extends ViewList
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // === Ensure default series exist ===
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::checkAndDisplaySeriesBanner();
    }

    public function display()
    {
        // === Verifactu Activation Banner ===
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

        parent::display();

        SticViews::display($this);

        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }
}
