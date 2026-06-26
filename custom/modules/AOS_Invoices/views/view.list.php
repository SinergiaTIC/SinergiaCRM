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
        $this->sticSeriesMessage = AOS_InvoicesUtils::checkAndDisplaySeriesBanner();
        if ($this->sticSeriesMessage) {
            global $mod_strings;
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            $title = $mod_strings['LBL_STIC_SERIES_CREATED_TITLE'];
            echo '<div id="sticSeriesAlert" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;background:#fff;border:3px solid #5bc0de;border-radius:12px;padding:30px;max-width:520px;box-shadow:0 8px 40px rgba(0,0,0,0.25);font-family:Arial,sans-serif;">
                <div style="font-size:48px;color:#5bc0de;text-align:center;margin-bottom:15px;">ⓘ</div>
                <p style="margin:0 0 20px 0;font-size:18px;font-weight:bold;text-align:center;color:#333;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</p>
                <div style="font-size:14px;line-height:1.7;color:#555;margin-bottom:20px;">' . $this->sticSeriesMessage . '</div>
                <div style="text-align:center;">
                    <button onclick="document.getElementById(\'sticSeriesAlert\').style.display=\'none\';window.location.reload();" style="background:#5bc0de;color:#fff;border:none;padding:12px 40px;font-size:16px;border-radius:6px;cursor:pointer;font-weight:bold;">Aceptar</button>
                </div>
            </div>';
        }
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
                <br><a href="index.php?module=stic_Settings&action=index">' . $mod_strings['LBL_VERIFACTU_ACTIVATED_LINK'] . '</a>
            </div>';
        }
        // === End Verifactu Activation Banner ===

        parent::display();

        SticViews::display($this);

        echo '<script>var verifactuActivated = ' . (!empty($verifactuStatus['activated']) ? 'true' : 'false') . ';
var verifactuInlineEditRestricted = "' . addslashes(translate('LBL_VERIFACTU_INLINE_EDIT_RESTRICTED', 'AOS_Invoices')) . '";</script>';
        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }
}
