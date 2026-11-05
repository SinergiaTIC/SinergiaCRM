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
        $this->ss->assign('TREE', $this->buildTree());

        echo $this->ss->fetch('custom/modules/Administration/templates/SticRelatedPanelConfig.tpl');
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

                    $portal_key = (string) $key;
                    $subConfig = SticRelatedPanelSettings::getSubpanelConfig($moduleName, $portal_key);
                    // Field options of the header panel, but the chronology date
                    // must be selectable from ANY module of a collection
                    // (Activities/History include several modules).
                    $panelsForFields = ($panel->isCollection() && !empty($panel->sub_subpanels))
                        ? array_merge(array($headerPanel), array_values($panel->sub_subpanels))
                        : array($headerPanel);

                    $fields = array();
                    $indexes = array();
                    foreach ($panelsForFields as $fieldPanel) {
                        foreach ($this->getFieldOptions($fieldPanel) as $option) {
                            $name = (string) $option['name'];
                            if (isset($indexes[$name])) {
                                // Collections merge the list fields of every
                                // sub-subpanel: a later panel may translate the
                                // same field with its own vardefs (e.g. the
                                // 'filename' of Notes inside History), while
                                // the first occurrence may have resolved to
                                // the raw field name.
                                $index = $indexes[$name];
                                if ($fields[$index]['label'] === $name && $option['label'] !== $name) {
                                    $fields[$index]['label'] = $option['label'];
                                }
                                if ($fields[$index]['type'] === '' && $option['type'] !== '') {
                                    $fields[$index]['type'] = $option['type'];
                                }
                                continue;
                            }
                            $indexes[$name] = count($fields);
                            $fields[] = $option;
                        }
                    }
                    $dateFields = array();
                    foreach ($fields as $field) {
                        if (in_array($field['type'], array('date', 'datetime', 'datetimecombo'), true)) {
                            $dateFields[] = $field;
                        }
                    }

                    // Default (automatic) field of every select. The automatic
                    // option is rendered as "<Label> (Default)" and the default
                    // field is NOT repeated as a separate explicit option.
                    $defaults = $this->getSubpanelDefaults($fields, $dateFields);
                    // Configurations saved with the explicit default value are
                    // equivalent to the automatic option: show them as default.
                    foreach (array('date_field', 'title_field', 'subtitle_field') as $configKey) {
                        if ($subConfig[$configKey] !== '' && $subConfig[$configKey] === $defaults[$configKey]) {
                            $subConfig[$configKey] = '';
                        }
                    }

                    // Collection labels live in the collection definition
                    // (title_key), not in the first sub-subpanel header
                    $panelTitle = trim((string) $panel->get_title());
                    if ($panelTitle === '' && $panel->isCollection()) {
                        $titleKey = isset($panel->_instance_properties['title_key'])
                            ? (string) $panel->_instance_properties['title_key']
                            : '';
                        if ($titleKey !== '') {
                            $panelTitle = trim((string) translate($titleKey, $moduleName));
                        }
                    }
                    if ($panelTitle === '') {
                        $panelTitle = (string) $headerPanel->get_title();
                    }
                    if ($panelTitle === '') {
                        $panelTitle = $portal_key;
                    }

                    $subpanels[] = array(
                        'key' => $portal_key,
                        'title' => $panelTitle,
                        'module' => (string) $headerPanel->get_module_name(),
                        'icon' => $this->iconClass($headerPanel->get_module_name()),
                        'config' => $subConfig,
                        'fields' => $fields,
                        'date_fields' => $dateFields,
                        'defaults' => $defaults,
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

        // Alphabetical order by the displayed module name (case insensitive),
        // with the internal module name as tie-breaker.
        usort($tree, function ($a, $b) {
            $labelA = function_exists('mb_strtolower')
                ? mb_strtolower((string) $a['label'], 'UTF-8')
                : strtolower((string) $a['label']);
            $labelB = function_exists('mb_strtolower')
                ? mb_strtolower((string) $b['label'], 'UTF-8')
                : strtolower((string) $b['label']);
            if ($labelA !== $labelB) {
                return strcasecmp($labelA, $labelB);
            }
            return strcmp((string) $a['module'], (string) $b['module']);
        });

        return $tree;
    }

    /**
     * UNION helper columns of the subpanel queries (declared with
     * usage => query_only only in some of the collection subpanels, so the
     * flag alone is not enough): they are never valid targets for the three
     * selectors and usually carry no label.
     * @var string[]
     */
    protected $helperColumns = array(
        'contact_id',
        'contact_name_owner',
        'contact_name_mod',
        'assigned_user_owner',
        'assigned_user_mod',
        'reply_to_status',
        'file_url',
    );

    /**
     * Field options of the selectors of one subpanel (its module).
     */
    protected function getFieldOptions($headerPanel)
    {
        $fields = array();
        if (!$headerPanel) {
            return $fields;
        }
        $panelModule = (string) $headerPanel->get_module_name();
        $instance = isset($headerPanel->template_instance) ? $headerPanel->template_instance : null;
        $seen = array();

        foreach ($headerPanel->get_list_fields() as $fieldName => $def) {
            $fieldName = (string) $fieldName;
            $widget = isset($def['widget_class']) ? (string) $def['widget_class'] : '';
            if ($widget !== ''
                && (stripos($widget, 'Button') !== false || stripos($widget, 'SubPanelIcon') !== false)) {
                continue;
            }
            if (in_array($fieldName, array('edit_button', 'remove_button', 'select_button'), true)
                || in_array($fieldName, $this->helperColumns, true)) {
                continue;
            }
            $fields[] = array(
                'name' => $fieldName,
                'label' => $this->resolveFieldLabel($fieldName, $def, $panelModule, $instance),
                'type' => isset($def['type']) ? (string) $def['type'] : '',
            );
            $seen[$fieldName] = true;
        }

        // Fields that are valid targets even when they are not part of the
        // subpanel list fields (typical with date_modified / assigned_user_name).
        $extraNames = array('name', 'assigned_user_name', 'date_modified', 'date_entered');
        if ($instance && !empty($instance->field_defs)) {
            foreach ($extraNames as $extra) {
                if (isset($seen[$extra]) || !isset($instance->field_defs[$extra])) {
                    continue;
                }
                $def = $instance->field_defs[$extra];
                $fields[] = array(
                    'name' => $extra,
                    'label' => $this->resolveFieldLabel($extra, $def, $panelModule, $instance),
                    'type' => isset($def['type']) ? (string) $def['type'] : '',
                );
                $seen[$extra] = true;
            }
        }

        // Subpanel list_fields usually declare no 'type'; the reliable type
        // comes from the module vardefs (template_instance). Without this the
        // chronology date select would be empty for many subpanels.
        if ($instance && !empty($instance->field_defs)) {
            foreach (array_keys($fields) as $index) {
                $field = $fields[$index]['name'];
                $vardefType = isset($instance->field_defs[$field]['type'])
                    ? (string) $instance->field_defs[$field]['type']
                    : '';
                if ($vardefType !== '' && $fields[$index]['type'] === '') {
                    $fields[$index]['type'] = $vardefType;
                }
            }
        }
        return $fields;
    }

    /**
     * Translated label of a field. Subpanel list_fields entries (e.g. the
     * query_only first_name / last_name of the person templates) often carry
     * no vname, so the module vardefs are used as a fallback.
     *
     * @param string $fieldName
     * @param array $def
     * @param string $panelModule
     * @param SugarBean|null $instance
     * @return string
     */
    protected function resolveFieldLabel($fieldName, array $def, $panelModule, $instance)
    {
        // list_fields keys sometimes reference labels not shipped in the module
        // language (e.g. LBL_LIST_DATE in the Tasks/Meetings Activities
        // subpanels): fall back to the vardefs, whose label is the one the
        // detail view uses.
        $candidates = array();
        if (!empty($def['vname'])) {
            $candidates[] = (string) $def['vname'];
        }
        if ($instance && !empty($instance->field_defs[$fieldName]['vname'])) {
            $candidates[] = (string) $instance->field_defs[$fieldName]['vname'];
        }
        foreach ($candidates as $vname) {
            $translated = translate($vname, $panelModule);
            if ($translated && $translated !== $vname) {
                return $this->cleanLabel($translated);
            }
        }
        return (string) $fieldName;
    }

    /**
     * Normalizes a translated label for the selects (no trailing colon).
     *
     * @param string $label
     * @return string
     */
    protected function cleanLabel($label)
    {
        return trim(rtrim(trim((string) $label), ':'));
    }

    /**
     * Default (automatic) field of the three selectors of a subpanel.
     *
     * @param array $fields
     * @param array $dateFields
     * @return array{date_field:string,date_label:string,title_field:string,title_label:string,subtitle_field:string,subtitle_label:string}
     */
    protected function getSubpanelDefaults(array $fields, array $dateFields)
    {
        $dateField = $this->pickDefaultField($dateFields, array(SticRelatedPanelSettings::DEFAULT_DATE_FIELD, 'date_entered'));
        $titleField = $this->pickDefaultField($fields, array('name', 'document_name', 'subject'));
        $subtitleField = $this->pickDefaultField($fields, array('assigned_user_name', 'status'));

        return array(
            'date_field' => $dateField,
            'date_label' => $this->fieldLabelByName($dateField, $fields),
            'title_field' => $titleField,
            'title_label' => $this->fieldLabelByName($titleField, $fields),
            'subtitle_field' => $subtitleField,
            'subtitle_label' => $this->fieldLabelByName($subtitleField, $fields),
        );
    }

    /**
     * First candidate present in the field list, or the first candidate.
     *
     * @param array $fields
     * @param array $candidates
     * @return string
     */
    protected function pickDefaultField(array $fields, array $candidates)
    {
        $names = array();
        foreach ($fields as $field) {
            $names[(string) $field['name']] = true;
        }
        foreach ($candidates as $candidate) {
            if (isset($names[$candidate])) {
                return (string) $candidate;
            }
        }
        return (string) $candidates[0];
    }

    /**
     * Label of a field from the prepared options list.
     *
     * @param string $fieldName
     * @param array $fields
     * @return string
     */
    protected function fieldLabelByName($fieldName, array $fields)
    {
        foreach ($fields as $field) {
            if ((string) $field['name'] === (string) $fieldName) {
                return (string) $field['label'];
            }
        }
        return (string) $fieldName;
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
