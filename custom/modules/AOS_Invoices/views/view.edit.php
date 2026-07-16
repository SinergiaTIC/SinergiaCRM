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
        global $sugar_config, $app_list_strings;

        // === Legacy mode: restore original status list (remove draft, emitted) ===
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        if (!AOS_InvoicesUtils::isVerifactuActivated()) {
            unset($app_list_strings['invoice_status_dom']['draft']);
            unset($app_list_strings['invoice_status_dom']['emitted']);
            $this->bean->field_defs['status']['default'] = '';
            if (empty($this->bean->id) && $this->bean->status === 'draft') {
                $this->bean->status = '';
            }
            // Remove invoice type field from edit panels
            foreach ($this->ev->defs['panels'] as $panelName => &$panel) {
                foreach ($panel as $rowNum => &$row) {
                    $row = array_values(array_filter($row, function($field) {
                        return !(is_array($field) && isset($field['name']) && $field['name'] === 'verifactu_invoice_type_c');
                    }));
                }
            }
        }
        // === End Legacy mode ===

        // === Sort series dropdown by usage count (desc), then alphabetically ===
        if (AOS_InvoicesUtils::isVerifactuActivated()) {
            $usageQuery = "SELECT c.verifactu_invoice_type_c, COUNT(*) AS cnt FROM aos_invoices_cstm c INNER JOIN aos_invoices i ON c.id_c = i.id WHERE i.deleted = 0 AND c.verifactu_invoice_type_c IS NOT NULL AND c.verifactu_invoice_type_c != '' GROUP BY c.verifactu_invoice_type_c";
            $usageResult = $GLOBALS['db']->query($usageQuery);
            $usageCounts = [];
            while ($row = $GLOBALS['db']->fetchByAssoc($usageResult)) {
                $usageCounts[$row['verifactu_invoice_type_c']] = (int)$row['cnt'];
            }
            uksort($app_list_strings['stic_invoices_types_list'], function($a, $b) use ($usageCounts) {
                $countA = $usageCounts[$a] ?? 0;
                $countB = $usageCounts[$b] ?? 0;
                if ($countA !== $countB) {
                    return $countB - $countA;
                }
                return strcasecmp($a, $b);
            });
        }
        // === End Sort ===

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

        $seriesConfig = $sugar_config['aos']['invoices']['series'] ?? [];
        $seriesForJs = [];
        foreach ($seriesConfig as $name => $config) {
            $seriesForJs[] = [
                'name' => $name,
                'isRectified' => !empty($config['isRectified']),
            ];
        }
        // === Filter status dropdown based on current invoice status ===
        if (AOS_InvoicesUtils::isVerifactuActivated() && !empty($this->bean->id)) {
            AOS_InvoicesUtils::filterStatusDropdown($app_list_strings, $this->bean->status);
        }
        // === End Filter ===

        parent::display();

        $customerIdNumber = $this->bean->customer_id_number ?? '';
        echo '<style>
        .stic-disabled { opacity: 0.5; cursor: not-allowed !important; pointer-events: none; }
        input.stic-disabled { background-color: #eee; }
        </style>';
        echo '<input type="hidden" name="customer_id_number" id="customer_id_number" value="' . htmlspecialchars($customerIdNumber, ENT_QUOTES, 'UTF-8') . '">';

        $verifactuStatus = AOS_InvoicesUtils::getVerifactuStatus();
        echo '<script>
var sticSeriesConfig = ' . json_encode($seriesForJs) . ';
var isNewRecord = ' . (empty($this->bean->id) ? 'true' : 'false') . ';
var customerIdentificationNumber = ' . json_encode($customerIdNumber) . ';
var verifactuActivated = ' . (!empty($verifactuStatus['activated']) ? 'true' : 'false') . ';
</script>';

        SticViews::display($this);

        echo '<script src="custom/modules/AOS_Invoices/SticUtils.js?v=' . filemtime('custom/modules/AOS_Invoices/SticUtils.js') . '"></script>';
    }

}