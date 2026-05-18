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

require_once 'modules/AOS_Invoices/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewDetail extends AOS_InvoicesViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        $this->bean->field_defs['name']['inline_edit'] = false;

        // === Ensure default series exist ===
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::checkAndDisplaySeriesBanner();

        // === Verifactu Activation Banner ===
        // Show warning if Verifactu is not activated but certificate is configured
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

        // Add banner for accepted/emitted invoices
        $bean = $this->bean;
        if (!empty($bean->verifactu_aeat_status_c) && 
            in_array($bean->verifactu_aeat_status_c, array('accepted', 'emitted'))) {

            // Hide edit and delete buttons
            $this->dv->defs['form']['hideButtons'] = true;
            
            // Add warning banner
            global $mod_strings;
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            
            $bannerMessage = $mod_strings['LBL_VERIFACTU_ACCEPTED_BANNER'] ?? 
                'Esta factura ha sido enviada a la AEAT y no puede ser modificada. Para realizar cambios debe crear una factura rectificativa.';
            
            echo '<div class="alert alert-warning" style="margin: 10px 0; padding: 12px; border-left: 4px solid #f0ad4e; background-color: #fcf8e3;">
                <strong><span class="suitepicon suitepicon-action-warning"></span> ' . $bannerMessage . '</strong>
            </div>';
        }
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        $bean = $this->bean;
        if (!empty($bean->verifactu_aeat_status_c) && 
            in_array($bean->verifactu_aeat_status_c, array('accepted', 'emitted'))) {
            
            // Disable inline editing
            echo '<script>
            $(document).ready(function() {
                $(".inlineEditIcon").hide();
                $(".inlineEdit").unbind("dblclick");
            });
            </script>';
        }
        
        // Write here the SinergiaCRM code that must be executed for this module and view
        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }
}
