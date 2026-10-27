<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// STIC-Custom 20261023 DEV - Related records panel
// Administration screen: global options plus a module -> subpanel tree where the
// admin decides where the panel appears, the section order and, per subpanel,
// which field is shown as title / subtitle / right field and how records are sorted.
// Reached from the "SinergiaCRM" admin section:
//   index.php?module=Administration&action=sticrelatedpanel
// See stic-ai-handbook: output/features/related-records-panel

require_once 'include/SubPanel/SubPanelDefinitions.php';
require_once 'custom/include/stic_RelatedPanel/RelatedPanelSettings.php';

class ViewSticrelatedpanel extends SugarView
{
    /**
     * Modules that never make sense as a "record detail view" with related records.
     * @var string[]
     */
    protected $skipModules = array(        'Home',
        'Administration',
        'Calendar',
        'Reports',
        'SavedSearch',
        'SugarFeed',
        'Trackers',
        'Connectors',
        'Employees',
        'OAuthKeys',
        'OAuthTokens',
        'ExternalOAuthConnection',
        'ExternalOAuthProvider',
    );

    /**
     * AJAX saves (config_json POST) must answer with plain JSON: no page chrome.
     * The options have to be set in the constructor because SugarView::process()
     * renders the header BEFORE calling preDisplay()/display().
     */
    public function __construct()
    {
        parent::__construct();
        if (!empty($_REQUEST['rrp_save']) && strpos((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json') !== false) {
            $this->options['show_header'] = false;
            $this->options['show_footer'] = false;
            $this->options['show_title'] = false;
            $this->options['show_javascript'] = false;
        }
    }

    /**
     * @see SugarView::_getModuleTitleParams()
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            "<a href='index.php?module=Administration&action=index'>" . $mod_strings['LBL_MODULE_NAME'] . '</a>',
            translate('LBL_RRP_ADMIN_TITLE', 'Administration'),
        );
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die('Unauthorized access to administration.');
        }
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $mod_strings;

        $saved = false;
        $saveError = false;
        if (!empty($_REQUEST['rrp_save'])) {
            // The admin screen submits ONE JSON document built on the client
            // (the full form would exceed PHP max_input_vars, and $_POST values
            // are mangled by the request cleaning). The JSON travels as the raw
            // request body, read from php://input.
            $data = array();
            $rawInput = (string) file_get_contents('php://input');
            $decodedBody = $rawInput !== '' ? json_decode($rawInput, true) : null;
            if (is_array($decodedBody)) {
                $data = $decodedBody;
            } elseif (!empty($_POST['modules'])) {
                $data = $_POST; // Legacy plain form POST
            }
            if (!empty($data)) {
                $config = SticRelatedPanelSettings::configFromData($data);
                $saved = SticRelatedPanelSettings::saveConfig($config);
                $saveError = !$saved;
            } else {
                $saveError = true;
            }
            // AJAX save: answer with plain JSON (page chrome disabled in the constructor)
            if ($decodedBody !== null) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('saved' => $saved));
                exit;
            }
        }

        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('title', $this->getModuleTitle(false));
        $this->ss->assign('CONFIG', SticRelatedPanelSettings::getConfig());
        $this->ss->assign('TREE', $this->buildTree());
        $this->ss->assign('SAVED', $saved || !empty($_REQUEST['rrp_saved']));
        $this->ss->assign('SAVE_ERROR', $saveError);
        $this->ss->assign('SORT_OPTIONS', $this->getSortOptions());
        $this->ss->assign('DIR_OPTIONS', $this->getDirOptions());

        echo $this->ss->fetch('custom/modules/Administration/templates/SticRelatedPanelConfig.tpl');
    }

    /**
     * Sort field options (labels translated in the Administration language files).
     *
     * @return array
     */
    protected function getSortOptions()
    {
        return array(
            array('value' => 'title', 'label' => translate('LBL_RRP_SORT_TITLE', 'Administration')),
            array('value' => 'subtitle', 'label' => translate('LBL_RRP_SORT_SUBTITLE', 'Administration')),
            array('value' => 'right', 'label' => translate('LBL_RRP_SORT_RIGHT', 'Administration')),
        );
    }

    /**
     * Sort direction options.
     *
     * @return array
     */
    protected function getDirOptions()
    {
        return array(
            array('value' => 'asc', 'label' => translate('LBL_RRP_SORT_ASC', 'Administration')),
            array('value' => 'desc', 'label' => translate('LBL_RRP_SORT_DESC', 'Administration')),
        );
    }

    /**
     * Builds the module -> subpanel tree with the effective configuration
     * and the displayable fields of every subpanel.
     *
     * @return array
     */
    protected function buildTree()
    {
        global $beanList, $app_list_strings;

        $tree = array();

        foreach ($beanList as $moduleName => $beanName) {
            if (in_array($moduleName, $this->skipModules, true)) {
                continue;
            }

            // Only modules that declare subpanels
            if (!file_exists('modules/' . $moduleName . '/metadata/subpaneldefs.php')
                && !file_exists('custom/modules/' . $moduleName . '/Ext/Layoutdefs/layoutdefs.ext.php')) {
                continue;
            }

            try {
                $bean = BeanFactory::newBean($moduleName);
                if (!$bean || empty($bean->module_dir)) {
                    continue;
                }
                $definitions = new SubPanelDefinitions($bean);
                $tabs = $definitions->get_available_tabs();
                if (empty($tabs)) {
                    continue;
                }

                $subpanels = array();
                $index = 0;
                foreach ($tabs as $key) {
                    $panel = $definitions->load_subpanel($key);
                    if ($panel === false) {
                        continue;
                    }

                    $headerPanel = $panel->isCollection() ? $panel->get_header_panel_def() : $panel;
                    if (!$headerPanel) {
                        $headerPanel = $panel;
                    }
                    if (!$headerPanel) {
                        continue;
                    }

                    $subConfig = SticRelatedPanelSettings::getSubpanelConfig($moduleName, $key);
                    $subpanels[] = array(
                        'key' => (string) $key,
                        'title' => (string) $headerPanel->get_title(),
                        'module' => (string) $headerPanel->get_module_name(),
                        'icon' => $this->iconClass($headerPanel->get_module_name()),
                        'config' => $subConfig,
                        'fields' => $this->getFieldOptions($headerPanel),
                        'sort_order' => ($subConfig['order'] !== null ? (int) $subConfig['order'] : 1000 + $index),
                    );
                    $index++;
                }

                if (empty($subpanels)) {
                    continue;
                }

                // Show subpanels in the configured order
                usort($subpanels, function ($a, $b) {
                    if ($a['sort_order'] === $b['sort_order']) {
                        return 0;
                    }
                    return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
                });

                $config = SticRelatedPanelSettings::getConfig();
                $moduleEnabled = true;
                if (isset($config['modules'][$moduleName]['enabled'])) {
                    $moduleEnabled = !empty($config['modules'][$moduleName]['enabled']);
                }

                $label = isset($app_list_strings['moduleList'][$moduleName])
                    ? $app_list_strings['moduleList'][$moduleName]
                    : $moduleName;

                $tree[] = array(
                    'module' => $moduleName,
                    'label' => $label,
                    'icon' => $this->iconClass($moduleName),
                    'enabled' => $moduleEnabled,
                    'subpanels' => $subpanels,
                );
            } catch (Throwable $e) {
                $GLOBALS['log']->debug('RelatedPanel admin tree: ' . $moduleName . ' - ' . $e->getMessage());
                continue;
            }
        }

        return $tree;
    }

    /**
     * Displayable field options of a subpanel (used by the title/subtitle/right selects).
     *
     * @param aSubPanel $headerPanel
     * @return array
     */
    protected function getFieldOptions($headerPanel)
    {
        $fields = array();
        if (!$headerPanel) {
            return $fields;
        }
        $panelModule = $headerPanel->get_module_name();
        foreach ($headerPanel->get_list_fields() as $fieldName => $def) {
            $fieldName = (string) $fieldName;
            if (isset($def['widget_class']) && stripos((string) $def['widget_class'], 'Button') !== false) {
                continue;
            }
            if (in_array($fieldName, array('edit_button', 'remove_button', 'select_button'), true)) {
                continue;
            }
            $label = $fieldName;
            if (!empty($def['vname'])) {
                $translated = translate($def['vname'], $panelModule);
                if ($translated && $translated !== $def['vname']) {
                    $label = $translated;
                }
            }
            $fields[] = array('name' => $fieldName, 'label' => $label);
        }
        return $fields;
    }

    /**
     * Icon CSS class for a module (suitepicon font).
     *
     * @param string $module
     * @return string
     */
    protected function iconClass($module)
    {
        $module = strtolower((string) $module);
        if ($module === '') {
            return '';
        }
        return 'suitepicon-module-' . str_replace('_', '-', $module);
    }
}
