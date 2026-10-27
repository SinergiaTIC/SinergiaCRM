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
// Structured configuration of the related records panel, stored as JSON in the
// native SuiteCRM "config" table (category "stic", name "related_panel_config")
// because the value can easily exceed the 255 chars of stic_settings.value.
// Shape (all values optional; the panel falls back to sane defaults):
// {
//   "enabled": true,            // global on/off switch
//   "pinned": false,            // panel pinned to the right, beside the detail panels only
//   "hide_empty": false,        // hide subpanels without records
//   "max_per_section": 10,      // records returned per section (hard cap 30)
//   "modules": {
//     "Contacts": {
//       "enabled": true,
//       "subpanels": {
//         "opportunities": {
//           "enabled": true,
//           "title_field": "",           // "" = auto (first record link)
//           "subtitle_field": "",        // "" = auto (assigned user / status)
//           "right_field": "",           // "" = auto (first date field)
//           "sort_field": "right",       // "title" | "subtitle" | "right"
//           "sort_dir": "desc",          // "asc" | "desc"
//           "order": 3                   // admin order (position in the tree)
//         }
//       }
//     }
//   }
// }
// Per-user section order is stored as a user preference ("rrp_order_<Module>").
// See stic-ai-handbook: output/features/related-records-panel

class SticRelatedPanelSettings
{
    const SETTING_MAX_LEGACY = 'related_panel_max';

    const CONFIG_CATEGORY = 'stic';
    const CONFIG_NAME = 'related_panel_config';

    const DEFAULT_MAX = 10;
    const HARD_MAX = 30;
    const SORT_FIELDS = array('title', 'subtitle', 'right');
    const SORT_DIRS = array('asc', 'desc');

    /**
     * Parsed configuration (request-level cache).
     * @var array|null
     */
    protected static $config = null;

    // ---------------------------------------------------------------- access

    /**
     * Full configuration array with defaults applied.
     *
     * @return array
     */
    public static function getConfig()
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $config = array(
            'enabled' => true,
            'pinned' => false,
            'hide_empty' => false,
            'max_per_section' => self::DEFAULT_MAX,
            'modules' => array(),
        );

        try {
            $db = DBManagerFactory::getInstance();
            $json = $db->fetchByAssoc($db->query(
                'SELECT value FROM config WHERE category = \'' . self::CONFIG_CATEGORY
                . '\' AND name = \'' . self::CONFIG_NAME . '\''
            ));
            if ($json && !empty($json['value'])) {
                // SuiteCRM DB layer applies to_html()/from_html() conventions:
                // values read through the DBManager arrive HTML-encoded
                $raw = (string) $json['value'];
                $decoded = json_decode(from_html($raw), true);
                if (!is_array($decoded)) {
                    $decoded = json_decode($raw, true);
                }
                if (is_array($decoded)) {
                    $config = self::mergeDefaults($config, $decoded);
                }
            }
            // Legacy scalar setting for the per-section limit (stic_Settings)
            $bean = BeanFactory::getBean('stic_Settings');
            if ($bean && $config['max_per_section'] === self::DEFAULT_MAX) {
                $bean->retrieve_by_string_fields(array('name' => self::SETTING_MAX_LEGACY, 'deleted' => 0));
                if (!empty($bean->id) && !empty($bean->value)) {
                    $max = (int) $bean->value;
                    if ($max > 0) {
                        $config['max_per_section'] = min($max, self::HARD_MAX);
                    }
                }
            }
        } catch (Throwable $e) {
            $GLOBALS['log']->debug('SticRelatedPanelSettings: ' . $e->getMessage());
        }

        self::$config = $config;
        return $config;
    }

    /**
     * Global enable switch.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        $config = self::getConfig();
        return !empty($config['enabled']);
    }

    /**
     * Whether the panel must be pinned to the right of the detail panels.
     *
     * @return bool
     */
    public static function isPinned()
    {
        $config = self::getConfig();
        return !empty($config['pinned']);
    }

    /**
     * Whether subpanels without records must be hidden (instead of shown at the bottom with 0).
     *
     * @return bool
     */
    public static function hideEmpty()
    {
        $config = self::getConfig();
        return !empty($config['hide_empty']);
    }

    /**
     * Maximum records per section.
     *
     * @return int
     */
    public static function getMax()
    {
        $config = self::getConfig();
        $max = (int) $config['max_per_section'];
        if ($max <= 0) {
            $max = self::DEFAULT_MAX;
        }
        return min($max, self::HARD_MAX);
    }

    /**
     * Whether the panel is enabled for a module (module enabled + global enabled).
     *
     * @param string $module
     * @return bool
     */
    public static function isEnabledForModule($module)
    {
        if (!self::isEnabled()) {
            return false;
        }
        $config = self::getConfig();
        $module = (string) $module;
        if ($module === '' || empty($config['modules'][$module])) {
            return true; // No explicit configuration: enabled by default
        }
        if (isset($config['modules'][$module]['enabled'])) {
            return !empty($config['modules'][$module]['enabled']);
        }
        return true;
    }

    /**
     * Effective configuration of one subpanel of a module, defaults applied.
     *
     * @param string $module
     * @param string $panelKey
     * @return array{enabled:bool,title_field:string,subtitle_field:string,right_field:string,sort_field:string,sort_dir:string,order:int}
     */
    public static function getSubpanelConfig($module, $panelKey)
    {
        $config = self::getConfig();
        $subpanel = array(
            'enabled' => true,
            'title_field' => '',
            'subtitle_field' => '',
            'right_field' => '',
            'sort_field' => 'right',
            'sort_dir' => 'desc',
            'order' => null,
        );
        if (isset($config['modules'][$module]['subpanels'][$panelKey])
            && is_array($config['modules'][$module]['subpanels'][$panelKey])) {
            $raw = $config['modules'][$module]['subpanels'][$panelKey];
            foreach (array('enabled', 'title_field', 'subtitle_field', 'right_field', 'sort_field', 'sort_dir', 'order') as $key) {
                if (array_key_exists($key, $raw)) {
                    $subpanel[$key] = $raw[$key];
                }
            }
        }
        // Sanitize
        if (!in_array($subpanel['sort_field'], self::SORT_FIELDS, true)) {
            $subpanel['sort_field'] = 'right';
        }
        if (!in_array($subpanel['sort_dir'], self::SORT_DIRS, true)) {
            $subpanel['sort_dir'] = 'desc';
        }
        foreach (array('title_field', 'subtitle_field', 'right_field') as $fieldKey) {
            if (!preg_match('/^[a-z0-9_]*$/i', (string) $subpanel[$fieldKey])) {
                $subpanel[$fieldKey] = '';
            }
        }
        return $subpanel;
    }

    /**
     * Saves a full configuration array (already sanitized) as JSON in the
     * native "config" table (direct SQL to avoid bean field encoding and the
     * 255 chars limit of stic_settings.value).
     *
     * @param array $config
     * @return bool
     */
    public static function saveConfig(array $config)
    {
        try {
            $db = DBManagerFactory::getInstance();
            $json = json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($json === false) {
                return false;
            }
            $db->query(
                'DELETE FROM config WHERE category = \'' . self::CONFIG_CATEGORY
                . '\' AND name = \'' . self::CONFIG_NAME . '\''
            );
            $db->query(
                'INSERT INTO config (category, name, value) VALUES (\''
                . self::CONFIG_CATEGORY . '\', \'' . self::CONFIG_NAME . '\', '
                . $db->quoted($json) . ')'
            );
            self::$config = null; // Invalidate request cache
            return true;
        } catch (Throwable $e) {
            $GLOBALS['log']->error('SticRelatedPanelSettings::saveConfig: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Replaces the panel configuration with the values posted by the admin screen.
     * Builds a clean config from a nested data array (whitelist + sanitize).
     *
     * @param array $data Nested array (from $_POST or from a decoded client JSON)
     * @return array Clean configuration
     */
    public static function configFromData(array $data)
    {
        $config = array(
            'enabled' => empty($data['enabled']) ? false : true,
            'pinned' => empty($data['pinned']) ? false : true,
            'hide_empty' => empty($data['hide_empty']) ? false : true,
            'max_per_section' => self::DEFAULT_MAX,
            'modules' => array(),
        );
        if (isset($data['max_per_section'])) {
            $max = (int) $data['max_per_section'];
            if ($max > 0) {
                $config['max_per_section'] = min($max, self::HARD_MAX);
            }
        }

        $modules = isset($data['modules']) && is_array($data['modules']) ? $data['modules'] : array();
        foreach ($modules as $module => $moduleData) {
            $module = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $module);
            if ($module === '' || !is_array($moduleData)) {
                continue;
            }
            $config['modules'][$module] = array(
                'enabled' => empty($moduleData['enabled']) ? false : true,
                'subpanels' => array(),
            );
            $subpanels = isset($moduleData['subpanels']) && is_array($moduleData['subpanels'])
                ? $moduleData['subpanels'] : array();
            $order = 0;
            foreach ($subpanels as $panelKey => $panelData) {
                $panelKey = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $panelKey);
                if ($panelKey === '' || !is_array($panelData)) {
                    continue;
                }
                $sub = array(
                    'enabled' => empty($panelData['enabled']) ? false : true,
                    'title_field' => preg_replace('/[^a-z0-9_]/i', '', (string) (isset($panelData['title_field']) ? $panelData['title_field'] : '')),
                    'subtitle_field' => preg_replace('/[^a-z0-9_]/i', '', (string) (isset($panelData['subtitle_field']) ? $panelData['subtitle_field'] : '')),
                    'right_field' => preg_replace('/[^a-z0-9_]/i', '', (string) (isset($panelData['right_field']) ? $panelData['right_field'] : '')),
                    'sort_field' => (isset($panelData['sort_field']) && in_array($panelData['sort_field'], self::SORT_FIELDS, true))
                        ? $panelData['sort_field'] : 'right',
                    'sort_dir' => (isset($panelData['sort_dir']) && in_array($panelData['sort_dir'], self::SORT_DIRS, true))
                        ? $panelData['sort_dir'] : 'desc',
                    'order' => $order++,
                );
                $config['modules'][$module]['subpanels'][$panelKey] = $sub;
            }
        }
        return $config;
    }

    /**
     * Legacy entry point kept for compatibility with plain form POSTs
     * (the admin screen now submits a single JSON built on the client,
     * because the full form exceeds PHP max_input_vars).
     *
     * @param array $post Typically $_POST
     * @return array
     */
    public static function configFromPost(array $post)
    {
        return self::configFromData($post);
    }

    /**
     * User preference: custom section order for a module (array of subpanel keys).
     *
     * @param string $module
     * @param string $userPreferenceCat Optional category (global by default)
     * @return array
     */
    public static function getUserOrder($module)
    {
        global $current_user;
        if (!is_object($current_user) || empty($current_user->id)) {
            return array();
        }
        $order = $current_user->getPreference('rrp_order_' . $module);
        return is_array($order) ? $order : array();
    }

    /**
     * User preferences of the panel itself:
     *  - pinned: keep the panel fixed beside the detail view (null = admin default)
     *  - shrink: make the detail view narrower to make room for the panel (null = default true)
     *
     * @return array{pinned:?bool, shrink:?bool}
     */
    public static function getUserPrefs()
    {
        global $current_user;
        $prefs = array('pinned' => null, 'shrink' => null);
        if (!is_object($current_user) || empty($current_user->id)) {
            return $prefs;
        }
        $stored = $current_user->getPreference('rrp_prefs');
        if (is_array($stored)) {
            if (array_key_exists('pinned', $stored)) {
                $prefs['pinned'] = (bool) $stored['pinned'];
            }
            if (array_key_exists('shrink', $stored)) {
                $prefs['shrink'] = (bool) $stored['shrink'];
            }
        }
        return $prefs;
    }

    /**
     * Saves one or both user preferences of the panel (null leaves the value untouched).
     *
     * @param bool|null $pinned
     * @param bool|null $shrink
     * @return bool
     */
    public static function saveUserPrefs($pinned = null, $shrink = null)
    {
        global $current_user;
        if (!is_object($current_user) || empty($current_user->id)) {
            return false;
        }
        $stored = $current_user->getPreference('rrp_prefs');
        $prefs = is_array($stored) ? $stored : array();
        if ($pinned !== null) {
            $prefs['pinned'] = (bool) $pinned;
        }
        if ($shrink !== null) {
            $prefs['shrink'] = (bool) $shrink;
        }
        $current_user->setPreference('rrp_prefs', $prefs, 0, 'global');
        return (bool) $current_user->savePreferencesToDB();
    }

    /**
     * Saves the user section order for a module.
     *
     * @param string $module
     * @param array $keys
     * @return bool
     */
    public static function saveUserOrder($module, array $keys)
    {
        global $current_user;
        if (!is_object($current_user) || empty($current_user->id)) {
            return false;
        }
        $clean = array();
        foreach ($keys as $key) {
            $key = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $key);
            if ($key !== '') {
                $clean[] = $key;
            }
        }
        $current_user->setPreference('rrp_order_' . $module, $clean, 0, 'global');
        return (bool) $current_user->savePreferencesToDB();
    }

    /**
     * Removes the user section order for a module.
     *
     * @param string $module
     * @return bool
     */
    public static function resetUserOrder($module)
    {
        global $current_user;
        if (!is_object($current_user) || empty($current_user->id)) {
            return false;
        }
        $current_user->setPreference('rrp_order_' . $module, null, 0, 'global');
        return (bool) $current_user->savePreferencesToDB();
    }

    // --------------------------------------------------------------- helpers

    /**
     * Applies defaults on top of a decoded configuration.
     *
     * @param array $defaults
     * @param array $decoded
     * @return array
     */
    protected static function mergeDefaults(array $defaults, array $decoded)
    {
        foreach (array('enabled', 'pinned', 'hide_empty') as $key) {
            if (array_key_exists($key, $decoded)) {
                $defaults[$key] = !empty($decoded[$key]);
            }
        }
        if (isset($decoded['max_per_section'])) {
            $max = (int) $decoded['max_per_section'];
            if ($max > 0) {
                $defaults['max_per_section'] = min($max, self::HARD_MAX);
            }
        }
        if (isset($decoded['modules']) && is_array($decoded['modules'])) {
            foreach ($decoded['modules'] as $module => $moduleData) {
                if (!is_array($moduleData)) {
                    continue;
                }
                $defaults['modules'][$module] = array(
                    'enabled' => isset($moduleData['enabled']) ? !empty($moduleData['enabled']) : true,
                    'subpanels' => isset($moduleData['subpanels']) && is_array($moduleData['subpanels'])
                        ? $moduleData['subpanels'] : array(),
                );
            }
        }
        return $defaults;
    }
}
