<?php
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

    public static function handleLogoUpload($fileField)
    {
        if (empty($fileField) || empty($fileField['tmp_name']) || $fileField['error'] !== UPLOAD_ERR_OK) {
            $GLOBALS['log']->fatal(__METHOD__ . " - Invalid file upload");
            return false;
        }
        $allowed = array('image/png' => 'png', 'image/jpeg' => 'jpg', 'image/svg+xml' => 'svg');
        $finfo   = function_exists('mime_content_type') ? mime_content_type($fileField['tmp_name']) : $fileField['type'];
        if (!isset($allowed[$finfo])) {
            $GLOBALS['log']->fatal(__METHOD__ . " - Unsupported file type: $finfo");
            return false;
        }
        $ext        = $allowed[$finfo];
        $targetName = 'portal_logo.' . $ext;
        $targetDir  = 'custom/themes/default/images';
        if (!is_dir($targetDir)) @mkdir($targetDir, 0777, true);
        $targetPath = $targetDir . '/' . $targetName;
        if (!move_uploaded_file($fileField['tmp_name'], $targetPath)) {
            $GLOBALS['log']->fatal(__METHOD__ . " - Failed to move uploaded file to $targetPath");
            return false;
        }
        @chmod($targetPath, 0644);
        self::set('PORTAL_LOGO', $targetName);
        $GLOBALS['log']->info(__METHOD__ . " - Logo uploaded: $targetName");
        return $targetName;
    }

    public static function clearAllLockouts()
    {
        global $db;
        $db->query("UPDATE contacts_cstm cc JOIN contacts c ON c.id = cc.id_c SET cc.stic_portal_failed_attempts_c = 0, cc.stic_portal_locked_until_c = NULL WHERE c.deleted = 0");
        $db->query("UPDATE accounts_cstm ac JOIN accounts a ON a.id = ac.id_c SET ac.stic_portal_failed_attempts_c = 0, ac.stic_portal_locked_until_c = NULL WHERE a.deleted = 0");
        $db->query("DELETE FROM stic_portal_login_attempts WHERE 1=1");
        $GLOBALS['log']->info(__METHOD__ . " - All lockouts cleared");
    }

    public static function clearAllSessions()
    {
        global $db;
        $db->query("UPDATE contacts_cstm cc JOIN contacts c ON c.id = cc.id_c SET cc.stic_portal_session_id_c = NULL WHERE c.deleted = 0");
        $db->query("UPDATE accounts_cstm ac JOIN accounts a ON a.id = ac.id_c SET ac.stic_portal_session_id_c = NULL WHERE a.deleted = 0");
        $GLOBALS['log']->info(__METHOD__ . " - All sessions cleared");
    }

    public static function purgeOldAudit()
    {
        global $db;
        $days = (int) self::get('PORTAL_AUDIT_RETENTION_DAYS', 365);
        if ($days <= 0) return 0;
        $db->query("DELETE FROM stic_portal_login_audit WHERE date_entered < DATE_SUB(NOW(), INTERVAL $days DAY)");
        $GLOBALS['log']->info(__METHOD__ . " - Purged audit older than $days days");
    }
}
