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

/**
 * SticPortalConfigUtils
 *
 * Read / write helpers for the portal configuration settings.
 * Settings are stored in the SuiteCRM `config` table under the
 * category `portal`, using the core Administration bean for CRUD.
 */
class SticPortalConfigUtils
{
    const CATEGORY = 'portal';

    /**
     * Get the Administration bean with settings loaded for the portal category.
     * @return Administration The bean with portal settings cached in memory.
     */
    private static function getAdmin()
    {
        if (empty($GLOBALS['sticPortalConfigAdmin'])) {
            $admin = BeanFactory::newBean('Administration');
            $admin->retrieveSettings(self::CATEGORY, true);
            $GLOBALS['sticPortalConfigAdmin'] = $admin;
        }
        return $GLOBALS['sticPortalConfigAdmin'];
    }

    /**
     * Clear the cached Administration bean so the next call reloads from DB.
     */
    private static function clearAdminCache()
    {
        unset($GLOBALS['sticPortalConfigAdmin']);
    }

    /**
     * Get a single portal configuration value.
     * @param string $name    The setting key (e.g., 'PORTAL_TITLE').
     * @param mixed  $default Value to return if the key is not set.
     * @return mixed The stored value or the default.
     */
    public static function get($name, $default = null)
    {
        $admin = self::getAdmin();
        return $admin->settings[self::CATEGORY . '_' . $name] ?? $default;
    }

    /**
     * Set a single portal configuration value.
     * Uses the core Administration::saveSetting() which writes to the `config` table.
     * @param string $name  The setting key (e.g., 'PORTAL_TITLE').
     * @param mixed  $value The value to store. Will be cast to string.
     */
    public static function set($name, $value)
    {
        $admin = self::getAdmin();
        $admin->saveSetting(self::CATEGORY, $name, (string) $value);
        // Refresh the cached value so subsequent get() returns the new value
        $admin->settings[self::CATEGORY . '_' . $name] = (string) $value;
    }

    /**
     * Delete a portal configuration value from the config table.
     * @param string $name The setting key to remove.
     */
    public static function delete($name)
    {
        global $db;
        $db->query("DELETE FROM config WHERE category=" . $db->quoted(self::CATEGORY) . " AND name=" . $db->quoted($name));
        self::clearAdminCache();
    }

    /**
     * Get all portal configuration settings as an associative array.
     * @return array Associative array of name => value for all portal settings.
     */
    public static function getAll()
    {
        $admin = self::getAdmin();
        $prefix = self::CATEGORY . '_';
        $plen = strlen($prefix);
        $out = array();
        foreach ($admin->settings as $k => $v) {
            if (strpos($k, $prefix) === 0) {
                $out[substr($k, $plen)] = $v;
            }
        }
        return $out;
    }

    /**
     * Save portal configuration settings from a POST array.
     * Only keys starting with 'PORTAL_' will be saved. Uses the core
     * Administration bean's saveSetting() to persist each key.
     *
     * @param array $post The $_POST array from a form submission.
     * @return array The list of keys that were saved.
     */
    public static function saveFromPost($post)
    {
        $admin = self::getAdmin();
        $saved = array();
        foreach ($post as $k => $v) {
            if (strpos($k, 'PORTAL_') === 0) {
                $val = is_array($v) ? implode(',', $v) : (string) $v;
                $admin->saveSetting(self::CATEGORY, $k, $val);
                $admin->settings[self::CATEGORY . '_' . $k] = $val;
                $saved[] = $k;
            }
        }
        $GLOBALS['log']->info(__METHOD__ . " - Saved " . count($saved) . " portal settings");
        return $saved;
    }

    /**
     * Get the URL of the portal logo image.
     * If a custom logo is set in the PORTAL_LOGO setting, return its URL;
     * otherwise return the default company logo URL.
     * @return string The URL of the logo image.
     */
    public static function getLogoUrl()
    {
        $logoFile = self::get('PORTAL_LOGO', '');
        if (!empty($logoFile)) {
            $path = 'custom/themes/default/images/' . $logoFile;
            if (file_exists($path)) {
                return SugarThemeRegistry::current()->getImageURL($logoFile, false, false, false, $path);
            }
        }
        return SugarThemeRegistry::current()->getImageURL('company_logo.png');
    }

    /**
     * Handle a logo file upload from a form submission.
     * Validates the file type (PNG, JPG, SVG) and moves it to the
     * custom/themes/default/images directory. Updates the PORTAL_LOGO setting.
     *
     * @param array $fileField The $_FILES array for the uploaded file.
     * @return string|false The new filename if successful, or false on failure.
     */
    public static function handleLogoUpload($fileField)
    {
        if (empty($fileField) || empty($fileField['tmp_name']) || $fileField['error'] !== UPLOAD_ERR_OK) {
            $GLOBALS['log']->error(__METHOD__ . " - Invalid file upload");
            return false;
        }
        $allowed = array('image/png' => 'png', 'image/jpeg' => 'jpg', 'image/svg+xml' => 'svg');
        $finfo   = function_exists('mime_content_type') ? mime_content_type($fileField['tmp_name']) : $fileField['type'];
        if (!isset($allowed[$finfo])) {
            $GLOBALS['log']->error(__METHOD__ . " - Unsupported file type: $finfo");
            return false;
        }
        $ext        = $allowed[$finfo];
        $targetName = 'portal_logo.' . $ext;
        $targetDir  = 'custom/themes/default/images';
        if (!is_dir($targetDir)) @mkdir($targetDir, 0777, true);
        $targetPath = $targetDir . '/' . $targetName;
        if (!move_uploaded_file($fileField['tmp_name'], $targetPath)) {
            $GLOBALS['log']->error(__METHOD__ . " - Failed to move uploaded file to $targetPath");
            return false;
        }
        @chmod($targetPath, 0644);
        self::set('PORTAL_LOGO', $targetName);
        $GLOBALS['log']->info(__METHOD__ . " - Logo uploaded: $targetName");
        return $targetName;
    }

    /**
     * Clear all portal lockouts and failed login attempts for contacts and accounts.
     * Resets stic_portal_failed_attempts_c and stic_portal_locked_until_c fields,
     * and deletes all records from stic_portal_login_attempts.
     * Uses SQL directly for performance — may affect many records.
     */
    public static function clearAllLockouts()
    {
        global $db;
        $db->query("UPDATE contacts_cstm cc JOIN contacts c ON c.id = cc.id_c SET cc.stic_portal_failed_attempts_c = 0, cc.stic_portal_locked_until_c = NULL WHERE c.deleted = 0");
        $db->query("UPDATE accounts_cstm ac JOIN accounts a ON a.id = ac.id_c SET ac.stic_portal_failed_attempts_c = 0, ac.stic_portal_locked_until_c = NULL WHERE a.deleted = 0");
        $db->query("DELETE FROM stic_portal_login_attempts WHERE 1=1");
        $GLOBALS['log']->info(__METHOD__ . " - All lockouts cleared");
    }

    /**
     * Clear all portal sessions for contacts and accounts.
     * Resets stic_portal_session_id_c to NULL for all records.
     * Uses SQL directly for performance — may affect many records.
     */
    public static function clearAllSessions()
    {
        global $db;
        $db->query("UPDATE contacts_cstm cc JOIN contacts c ON c.id = cc.id_c SET cc.stic_portal_session_id_c = NULL WHERE c.deleted = 0");
        $db->query("UPDATE accounts_cstm ac JOIN accounts a ON a.id = ac.id_c SET ac.stic_portal_session_id_c = NULL WHERE a.deleted = 0");
        $GLOBALS['log']->info(__METHOD__ . " - All sessions cleared");
    }

    /**
     * Purge old portal login audit records based on the configured retention period.
     * Deletes records from stic_portal_login_audit older than the configured number of days.
     * @return int The number of records deleted.
     */
    public static function purgeOldAudit()
    {
        global $db;
        $days = (int) self::get('PORTAL_AUDIT_RETENTION_DAYS', 365);
        if ($days <= 0) return 0;
        $db->query("DELETE FROM stic_portal_login_audit WHERE date_entered < DATE_SUB(NOW(), INTERVAL $days DAY)");
        $GLOBALS['log']->info(__METHOD__ . " - Purged audit older than $days days");
    }
}
