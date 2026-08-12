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
 * category `portal`.
 */
class SticPortalConfigUtils
{
    const CATEGORY = 'portal';

    public static function get($name, $default = null)
    {
        global $db;
        $result = $db->limitQuery("SELECT value FROM config WHERE category=" . $db->quoted(self::CATEGORY) . " AND name=" . $db->quoted($name), 0, 1);
        $row = $db->fetchByAssoc($result);
        return $row ? $row['value'] : $default;
    }

    public static function set($name, $value)
    {
        global $db;
        $cat   = $db->quoted(self::CATEGORY);
        $nameQ = $db->quoted($name);
        $valQ  = $db->quoted((string) $value);
        $db->query("INSERT INTO config (category, name, value) VALUES ($cat, $nameQ, $valQ) ON DUPLICATE KEY UPDATE value = $valQ");
        $GLOBALS['log']->debug(__METHOD__ . " - Set $name = $value");
    }

    public static function delete($name)
    {
        global $db;
        $db->query("DELETE FROM config WHERE category=" . $db->quoted(self::CATEGORY) . " AND name=" . $db->quoted($name));
    }

    public static function getAll()
    {
        global $db;
        $result = $db->query("SELECT name, value FROM config WHERE category=" . $db->quoted(self::CATEGORY));
        $out = array();
        while ($row = $db->fetchByAssoc($result)) {
            $out[$row['name']] = $row['value'];
        }
        return $out;
    }

    /**
     * Save portal configuration settings from a POST array.
     * Only keys starting with 'PORTAL_' will be saved.
     * @param array $post The $_POST array from a form submission.
     * @return array The list of keys that were saved.
     * @see SticPortalConfigUtils::set()
     * @see SticPortalConfigUtils::get()
     * @see SticPortalConfigUtils::getAll()
     * @see SticPortalConfigUtils::delete()
     */
    public static function saveFromPost($post)
    {
        $saved = array();
        foreach ($post as $k => $v) {
            if (strpos($k, 'PORTAL_') === 0) {
                self::set($k, is_array($v) ? implode(',', $v) : (string) $v);
                $saved[] = $k;
            }
        }
        $GLOBALS['log']->info(__METHOD__ . " - Saved " . count($saved) . " portal settings");
        return $saved;
    }

    /**
     * Get the URL of the portal logo image.
     * If a custom logo is set in the PORTAL_LOGO setting, return its URL; otherwise return the default company logo URL.
     * @return string The URL of the logo image
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
     * Validates the file type (PNG, JPG, SVG) and moves it to the custom/themes/default/images directory.
     * Updates the PORTAL_LOGO setting with the new filename.
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
     * This will reset the `stic_portal_failed_attempts_c` and `stic_portal_locked_until_c` fields to 0/NULL, and delete all records from `stic_portal_login_attempts`.
     * Use with caution, as this will allow previously locked users to attempt login again.
     * Using SQL directly for performance, as this may affect many records.
     * @return void
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
     * This will reset the `stic_portal_session_id_c` field to NULL for all contacts and accounts.
     * Use with caution, as this will log out all currently logged-in users.
     * Using SQL directly for performance, as this may affect many records.
     * @return void
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
     * The retention period is defined by the `PORTAL_AUDIT_RETENTION_DAYS` setting, defaulting to 365 days if not set.
     * This will delete records from the `stic_portal_login_audit` table that are older than the specified number of days.
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
