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

class ViewSticManageSdaIntegration extends SugarView
{
    /**
     * @see SugarView::_getModuleTitleParams()
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
           "<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>",
           $mod_strings['LBL_STIC_SINERGIADA_LINK_TITLE']
           );
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        
        global $mod_strings, $current_user, $sugar_config, $app_list_strings;
        
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('title', $this->getModuleTitle(false));
        $this->ss->assign('CURRENT_USER_ID', $current_user->id);
        $sdaConfig = $sugar_config['stic_sinergiada'] ?? [];
        $knownCacheKeys = ['cache_enabled', 'cache_units', 'cache_quantity', 'cache_hours', 'cache_minutes'];
        $defaultCache = [
            'cache_enabled' => false,
            'cache_units' => 'days',
            'cache_quantity' => 2,
            'cache_hours' => '04',
            'cache_minutes' => '30',
        ];
        if (!isset($sdaConfig['config']) || !is_array($sdaConfig['config'])) {
            $sdaConfig['config'] = $defaultCache;
        } else {
            $sdaConfig['config'] = array_merge($defaultCache, $sdaConfig['config']);
        }
        $extraConfig = [];
        foreach ($sdaConfig['config'] as $key => $value) {
            if (!in_array($key, $knownCacheKeys)) {
                $extraConfig[$key] = $value;
            }
        }
        $this->ss->assign('SDA_EXTRA_CONFIG', $extraConfig);
        $this->ss->assign('SDA_CONFIG', $sdaConfig);
        $this->ss->assign('SDA_PUBLIC_URL', $sugar_config['stic_sinergiada_public']['url'] ?? '');

        // Load active theme color from stic_settings
        require_once 'modules/stic_Settings/Utils.php';
        $primaryColor = stic_SettingsUtils::getSetting('GENERAL_CUSTOM_THEME_COLOR');
        if (empty($primaryColor)) {
            $primaryColor = '#b5bc31';
        }

        list($h, $s, $l) = $this->sda_hexToHsl($primaryColor);
        $darkColor = $this->sda_hslToHex($h, $s, max(0, $l - 7));
        $darkerColor = $this->sda_hslToHex($h, $s, max(0, $l - 14));
        $lightColor = $this->sda_hslToHex($h, $s, min(100, $l + 35));

        $primaryHex = ltrim($primaryColor, '#');
        $r = hexdec(substr($primaryHex, 0, 2));
        $g = hexdec(substr($primaryHex, 2, 2));
        $b = hexdec(substr($primaryHex, 4, 2));

        $styleBlock = "<style>\n.sda-page-wrapper {\n";
        $styleBlock .= "\t--sda-primary: {$primaryColor};\n";
        $styleBlock .= "\t--sda-primary-dark: {$darkColor};\n";
        $styleBlock .= "\t--sda-primary-darker: {$darkerColor};\n";
        $styleBlock .= "\t--sda-primary-light: {$lightColor};\n";
        $styleBlock .= "\t--sda-primary-r: {$r};\n";
        $styleBlock .= "\t--sda-primary-g: {$g};\n";
        $styleBlock .= "\t--sda-primary-b: {$b};\n";
        $styleBlock .= "}\n</style>";
        $this->ss->assign('SDA_THEME_STYLE', $styleBlock);

        // Build module list for publish_as_table selector, replicating SinergiaDA module filtering
        include_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $visibleModules = $controller->get_system_tabs();
        $evenExcludedModules = [
            'Administration', 'AM_ProjectTemplates', 'AOBH_BusinessHours', 'AOK_Knowledge_Base_Categories',
            'AOK_KnowledgeBase', 'AOR_Reports', 'AOR_Scheduled_Reports', 'AOS_PDF_Templates', 'AOW_WorkFlow',
            'Bugs', 'Calendar', 'DHA_PlantillasDocumentos', 'Documents', 'EmailTemplates', 'FP_events',
            'Home', 'jjwg_Address_Cache', 'jjwg_Areas', 'jjwg_Maps', 'jjwg_Markers', 'KReports',
            'ProspectLists', 'Prospects', 'ResourceCalendar', 'SecurityGroups', 'Spots',
            'stic_Bookings_Calendar', 'stic_Sepe_Files', 'stic_Settings', 'stic_Validation_Actions',
            'stic_Web_Forms', 'stic_Incorpora_Locations', 'stic_Validation_Results', 'stic_Custom_Views',
        ];
        $modulesList = array_diff($visibleModules, $evenExcludedModules);
        $modulesList['Users'] = 'Users';
        natsort($modulesList);
        $moduleOptions = [];
        foreach ($modulesList as $moduleName) {
            $txModuleName = $app_list_strings['moduleList'][$moduleName] ?? $moduleName;
            $moduleOptions[] = ['name' => $moduleName, 'label' => $txModuleName];
        }
        usort($moduleOptions, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        $this->ss->assign('SDA_MODULES', $moduleOptions);

        $output = $this->ss->fetch('custom/modules/Administration/templates/SticManageSdaIntegration.tpl');
        if (!empty($output)) {
            echo $output;
        }
    }

    /**
     * Helper: convert hex color to HSL array.
     */
    private function sda_hexToHsl($hex)
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max == $min) {
            return [0, 0, round($l * 100)];
        }

        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

        switch ($max) {
            case $r:
                $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                break;
            case $g:
                $h = ($b - $r) / $d + 2;
                break;
            case $b:
                $h = ($r - $g) / $d + 4;
                break;
        }
        $h /= 6;

        return [round($h * 360), round($s * 100), round($l * 100)];
    }

    /**
     * Helper: convert HSL to hex color.
     */
    private function sda_hslToHex($h, $s, $l)
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $huetoRgb = function ($p, $q, $t) {
                if ($t < 0) {$t += 1;}
                if ($t > 1) {$t -= 1;}
                if ($t < 1 / 6) {return $p + ($q - $p) * 6 * $t;}
                if ($t < 1 / 2) {return $q;}
                if ($t < 2 / 3) {return $p + ($q - $p) * (2 / 3 - $t) * 6;}
                return $p;
            };
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = $huetoRgb($p, $q, $h + 1 / 3);
            $g = $huetoRgb($p, $q, $h);
            $b = $huetoRgb($p, $q, $h - 1 / 3);
        }

        return sprintf('#%02x%02x%02x', round($r * 255), round($g * 255), round($b * 255));
    }
}
