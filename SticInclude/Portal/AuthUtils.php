<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'SticInclude/Portal/ConfigUtils.php';

class SticPortalAuthUtils
{
    private static $timeDate;

    private static function td()
    {
        if (!self::$timeDate) self::$timeDate = TimeDate::getInstance();
        return self::$timeDate;
    }

    private static function nowDb()
    {
        return self::td()->nowDb();
    }

    private static function futureDb($seconds)
    {
        return self::td()->getNow()->modify("+{$seconds} seconds")->asDb();
    }

    // ── Password hashing ──────────────────────────────────────────
    public static function hashPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($plainPassword, $hash)
    {
        return password_verify($plainPassword, $hash);
    }

    public static function needsRehash($hash)
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }

    // ── User lookup ──────────────────────────────────────────────
    public static function getPortalUserByUsername($username)
    {
        global $db;
        $GLOBALS['log']->debug(__METHOD__ . " - Looking up portal user by username: $username");
        $uname = $db->quoted($username);
        $result = $db->limitQuery("SELECT c.id FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id WHERE c.deleted=0 AND cc.stic_portal_enabled_c=1 AND cc.stic_portal_username_c=$uname", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Contacts', $row['id']);
            if ($bean && $bean->id) {
                self::loadCustomFields($bean, 'contacts_cstm');
                $GLOBALS['log']->debug(__METHOD__ . " - Found Contact: {$bean->id}");
                return array('bean' => $bean, 'type' => 'Contact');
            }
        }
        $result = $db->limitQuery("SELECT a.id FROM accounts a JOIN accounts_cstm ac ON ac.id_c = a.id WHERE a.deleted=0 AND ac.stic_portal_enabled_c=1 AND ac.stic_portal_username_c=$uname", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Accounts', $row['id']);
            if ($bean && $bean->id) {
                self::loadCustomFields($bean, 'accounts_cstm');
                $GLOBALS['log']->debug(__METHOD__ . " - Found Account: {$bean->id}");
                return array('bean' => $bean, 'type' => 'Account');
            }
        }
        $GLOBALS['log']->debug(__METHOD__ . " - Portal user not found: $username");
        return null;
    }

    // ── Lockout ──────────────────────────────────────────────────
    public static function checkLockout($bean)
    {
        $locked = $bean->stic_portal_locked_until_c;
        if (!empty($locked) && $locked !== '0000-00-00 00:00:00') {
            $ts = strtotime($locked);
            if ($ts !== false && $ts > time()) {
                return array('locked' => true, 'remaining_seconds' => $ts - time());
            }
        }
        return array('locked' => false, 'remaining_seconds' => 0);
    }

    public static function recordFailedAttempt($bean, $ipAddress = '')
    {
        $GLOBALS['log']->debug(__METHOD__ . " - Recording failed attempt for {$bean->stic_portal_username_c} ({$bean->id}), IP: $ipAddress");
        $maxAttempts = (int)SticPortalConfigUtils::get('PORTAL_MAX_FAILED_ATTEMPTS', 5);
        $lockMins    = (int)SticPortalConfigUtils::get('PORTAL_LOCKOUT_DURATION_MINUTES', 30);
        $bean->stic_portal_failed_attempts_c = (int)$bean->stic_portal_failed_attempts_c + 1;
        if ($bean->stic_portal_failed_attempts_c >= $maxAttempts) {
            $bean->stic_portal_locked_until_c = self::futureDb($lockMins * 60);
            self::sendSecurityNotification($bean, 'account_locked');
            $GLOBALS['log']->info(__METHOD__ . " - Account locked: {$bean->stic_portal_username_c}");
        }
        $bean->save();
        if ($ipAddress) {
            self::recordIpFailedAttempt($ipAddress);
        }
    }

    public static function resetFailedAttempts($bean)
    {
        $bean->stic_portal_failed_attempts_c = 0;
        $bean->stic_portal_locked_until_c = null;
        $bean->save();
    }

    // ── IP lockout ─────────────────────────────────────
    public static function isIpLocked($ipAddress)
    {
        global $db;
        $ip = $db->quoted($ipAddress);
        $result = $db->limitQuery("SELECT locked_until FROM stic_portal_login_attempts WHERE ip_address=$ip AND deleted=0", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row && !empty($row['locked_until']) && strtotime($row['locked_until']) > time()) {
            return true;
        }
        return false;
    }

    public static function recordIpFailedAttempt($ipAddress)
    {
        global $db;
        $max = (int)SticPortalConfigUtils::get('PORTAL_MAX_FAILED_ATTEMPTS', 5);
        $lockMins = (int)SticPortalConfigUtils::get('PORTAL_LOCKOUT_DURATION_MINUTES', 30);
        $ip = $db->quoted($ipAddress);
        $result = $db->limitQuery("SELECT id, failed_attempts FROM stic_portal_login_attempts WHERE ip_address=$ip AND deleted=0", 0, 1);
        $existing = $db->fetchByAssoc($result);
        $now = self::nowDb();
        if ($existing) {
            $newCount = (int)$existing['failed_attempts'] + 1;
            $db->query("UPDATE stic_portal_login_attempts SET failed_attempts=$newCount, last_attempt=" . $db->quoted($now) . ", date_modified=" . $db->quoted($now) . " WHERE id=" . $db->quoted($existing['id']));
            if ($newCount >= $max) {
                $until = self::futureDb($lockMins * 60);
                $db->query("UPDATE stic_portal_login_attempts SET locked_until=" . $db->quoted($until) . " WHERE id=" . $db->quoted($existing['id']));
            }
        } else {
            $id = create_guid();
            $db->query("INSERT INTO stic_portal_login_attempts (id, ip_address, failed_attempts, date_entered, date_modified, deleted) VALUES (" . $db->quoted($id) . ", $ip, 1, " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");
        }
    }

    // ── Remember me ────────────────────────────────────
    public static function generateRememberToken($bean)
    {
        $rawToken = bin2hex(random_bytes(32));
        $bean->stic_portal_remember_token_c = hash('sha256', $rawToken);
        $bean->save();
        $days = (int)SticPortalConfigUtils::get('PORTAL_REMEMBER_ME_DAYS', 30);
        $expire = time() + $days * 86400;
        setcookie('portal_remember', $rawToken, $expire, '/', '', false, true);
        return $rawToken;
    }

    public static function validateRememberToken($cookieToken)
    {
        if (empty($cookieToken)) {
            return null;
        }
        $hashed = $db->quoted(hash('sha256', $cookieToken));
        global $db;
        $result = $db->limitQuery("SELECT c.id FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id WHERE c.deleted=0 AND cc.stic_portal_remember_token_c=$hashed", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Contacts', $row['id']);
            if ($bean && $bean->id) {
                self::generateRememberToken($bean);
                return array('bean' => $bean, 'type' => 'Contact');
            }
        }
        $result = $db->limitQuery("SELECT a.id FROM accounts a JOIN accounts_cstm ac ON ac.id_c = a.id WHERE a.deleted=0 AND ac.stic_portal_remember_token_c=$hashed", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Accounts', $row['id']);
            if ($bean && $bean->id) {
                self::generateRememberToken($bean);
                return array('bean' => $bean, 'type' => 'Account');
            }
        }
        return null;
    }

    public static function clearRememberToken($bean)
    {
        $bean->stic_portal_remember_token_c = null;
        $bean->save();
        setcookie('portal_remember', '', time() - 3600, '/');
    }

    // ── Password policies ──────────────────────────────
    public static function validatePasswordPolicy($password)
    {
        $violations = array();
        $minLen = (int)SticPortalConfigUtils::get('PORTAL_PASSWORD_MIN_LENGTH', 8);
        if (strlen($password) < $minLen) {
            $violations[] = "Minimum length is {$minLen} characters";
        }
        if (SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_UPPER', '0') === '1' && !preg_match('/[A-Z]/', $password)) {
            $violations[] = 'At least one uppercase letter required';
        }
        if (SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_LOWER', '0') === '1' && !preg_match('/[a-z]/', $password)) {
            $violations[] = 'At least one lowercase letter required';
        }
        if (SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_NUMBER', '0') === '1' && !preg_match('/[0-9]/', $password)) {
            $violations[] = 'At least one digit required';
        }
        if (SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_SPECIAL', '0') === '1' && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $violations[] = 'At least one special character required';
        }
        return $violations;
    }

    // ── Password history ──────────────────────────────
    public static function archivePasswordHistory($bean, $oldHash)
    {
        $count = (int)SticPortalConfigUtils::get('PORTAL_PASSWORD_HISTORY_COUNT', 0);
        if ($count <= 0 || empty($oldHash)) {
            return;
        }
        global $db;
        $id = create_guid();
        $now = self::nowDb();
        $db->query("INSERT INTO stic_portal_password_history (id, parent_id, parent_type, password_hash, date_entered, date_modified, deleted) VALUES (" . $db->quoted($id) . ", " . $db->quoted($bean->id) . ", " . $db->quoted($bean->module_name) . ", " . $db->quoted($oldHash) . ", " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");
        $keep = max(0, $count);
        $db->query("DELETE FROM stic_portal_password_history WHERE parent_id=" . $db->quoted($bean->id) . " AND parent_type=" . $db->quoted($bean->module_name) . " AND deleted=0 ORDER BY date_entered ASC LIMIT 9999 OFFSET $keep");
    }

    public static function isPasswordInHistory($bean, $plainPassword)
    {
        $count = (int)SticPortalConfigUtils::get('PORTAL_PASSWORD_HISTORY_COUNT', 0);
        if ($count <= 0) return false;
        global $db;
        $result = $db->query("SELECT password_hash FROM stic_portal_password_history WHERE parent_id=" . $db->quoted($bean->id) . " AND parent_type=" . $db->quoted($bean->module_name) . " AND deleted=0 ORDER BY date_entered DESC LIMIT $count");
        while ($row = $db->fetchByAssoc($result)) {
            if (password_verify($plainPassword, $row['password_hash'])) return true;
        }
        return false;
    }

    // ── Password expiration ───────────────────────────
    public static function isPasswordExpired($bean)
    {
        $expires = $bean->stic_portal_password_expires_c;
        if (empty($expires) || $expires === '0000-00-00 00:00:00' || $expires === '0000-00-00') {
            return false;
        }
        $ts = strtotime($expires);
        return ($ts !== false && $ts <= time());
    }

    public static function setPasswordExpiration($bean)
    {
        $days = (int)SticPortalConfigUtils::get('PORTAL_PASSWORD_EXPIRATION_DAYS', 0);
        if ($days > 0) {
            $bean->stic_portal_password_expires_c = self::futureDb($days * 86400);
        } else {
            $bean->stic_portal_password_expires_c = null;
        }
    }

    // ── Password reset ────────────────────────────────
    public static function generateResetToken($bean)
    {
        $rawToken = bin2hex(random_bytes(32));
        $bean->stic_portal_reset_token_c = hash('sha256', $rawToken);
        $bean->stic_portal_reset_expires_c = self::futureDb(3600);
        $bean->save();
        return $rawToken;
    }

    public static function validateResetToken($token, $recordId)
    {
        if (empty($token) || empty($recordId)) {
            $GLOBALS['log']->debug(__METHOD__ . " - Empty token or recordId");
            return null;
        }
        global $db;
        $hashed = $db->quoted(hash('sha256', $token));
        $id = $db->quoted($recordId);
        $result = $db->limitQuery("SELECT c.id FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id WHERE c.id=$id AND c.deleted=0 AND cc.stic_portal_reset_token_c=$hashed AND cc.stic_portal_reset_expires_c > NOW()", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) { $GLOBALS['log']->debug(__METHOD__ . " - Valid reset token for Contact: {$row['id']}"); return array('bean' => BeanFactory::getBean('Contacts', $row['id']), 'type' => 'Contact'); }
        $result = $db->limitQuery("SELECT a.id FROM accounts a JOIN accounts_cstm ac ON ac.id_c = a.id WHERE a.id=$id AND a.deleted=0 AND ac.stic_portal_reset_token_c=$hashed AND ac.stic_portal_reset_expires_c > NOW()", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) { $GLOBALS['log']->debug(__METHOD__ . " - Valid reset token for Account: {$row['id']}"); return array('bean' => BeanFactory::getBean('Accounts', $row['id']), 'type' => 'Account'); }
        $GLOBALS['log']->debug(__METHOD__ . " - Invalid or expired reset token");
        return null;
    }

    public static function clearResetToken($bean)
    {
        $bean->stic_portal_reset_token_c = null;
        $bean->stic_portal_reset_expires_c = null;
        $bean->save();
    }

    // ── Magic link ────────────────────────────────────
    public static function generateMagicLinkToken($bean)
    {
        $rawToken = bin2hex(random_bytes(32));
        $bean->stic_portal_magic_token_c = hash('sha256', $rawToken);
        $mins = (int)SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_EXPIRATION_MINUTES', 15);
        $bean->stic_portal_magic_expires_c = self::futureDb($mins * 60);
        $bean->save();
        self::sendMagicLinkEmail($bean, $rawToken);
        return $rawToken;
    }

    public static function validateMagicLinkToken($token, $recordId)
    {
        if (empty($token) || empty($recordId)) return null;
        global $db;
        $hashed = $db->quoted(hash('sha256', $token));
        $id = $db->quoted($recordId);
        $result = $db->limitQuery("SELECT c.id FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id WHERE c.id=$id AND c.deleted=0 AND cc.stic_portal_magic_token_c=$hashed AND cc.stic_portal_magic_expires_c > NOW()", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Contacts', $row['id']);
            $bean->stic_portal_magic_token_c = null;
            $bean->stic_portal_magic_expires_c = null;
            $bean->save();
            return array('bean' => $bean, 'type' => 'Contact');
        }
        $result = $db->limitQuery("SELECT a.id FROM accounts a JOIN accounts_cstm ac ON ac.id_c = a.id WHERE a.id=$id AND a.deleted=0 AND ac.stic_portal_magic_token_c=$hashed AND ac.stic_portal_magic_expires_c > NOW()", 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            $bean = BeanFactory::getBean('Accounts', $row['id']);
            $bean->stic_portal_magic_token_c = null;
            $bean->stic_portal_magic_expires_c = null;
            $bean->save();
            return array('bean' => $bean, 'type' => 'Account');
        }
        return null;
    }

    public static function sendMagicLinkEmail($bean, $rawToken)
    {
        $templateId = SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_TEMPLATE', '');
        if (empty($templateId)) return false;
        $tpl = BeanFactory::getBean('EmailTemplates', $templateId);
        if (!$tpl || !$tpl->id) return false;
        require_once 'include/SugarPHPMailer.php';
        $to = self::getPrimaryEmail($bean);
        if (empty($to)) return false;
        $link = rtrim(SticPortalConfigUtils::get('PORTAL_HOME_URL', ''), '/');
        if (empty($link)) $link = 'http://localhost:8000/sinergiacrm';
        $link .= '/index.php?entryPoint=sticPortalMagicLogin&token=' . urlencode($rawToken) . '&id=' . urlencode($bean->id);
        $tpl->subject = str_replace(array('$portal_magic_link', '$portal_title'), array($link, SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal')), $tpl->subject);
        $tpl->body = str_replace(array('$portal_magic_link', '$portal_title'), array($link, SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal')), $tpl->body);
        $tpl->body_html = str_replace(array('$portal_magic_link', '$portal_title'), array($link, SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal')), $tpl->body_html);
        $mail = new SugarPHPMailer();
        $mail->From = 'noreply@sinergiacrm.org';
        $mail->FromName = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
        $mail->addAddress($to, self::getRecipientName($bean));
        $mail->Subject = $tpl->subject;
        $mail->Body = !empty($tpl->body_html) ? $tpl->body_html : $tpl->body;
        if (!empty($tpl->body_html)) { $mail->AltBody = $tpl->body; $mail->isHTML(true); }
        return $mail->Send();
    }

    // ── Session management ────────────────────────────
    public static function createPortalSession($bean, $type)
    {
        if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
        session_start();
        session_regenerate_id(true);
        $_SESSION['portal_user_type'] = $type;
        $_SESSION['portal_user_id'] = $bean->id;
        $_SESSION['portal_username'] = $bean->stic_portal_username_c;
        $_SESSION['portal_last_activity'] = time();
        $_SESSION['portal_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['portal_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $bean->stic_portal_session_id_c = session_id();
        $bean->stic_portal_last_login_c = self::nowDb();
        $bean->save();
    }

    public static function destroyPortalSession($bean = null)
    {
        if ($bean) {
            $bean->stic_portal_session_id_c = null;
            $bean->save();
        }
        session_unset();
        session_destroy();
        self::clearRememberToken($bean);
    }

    public static function validatePortalSession()
    {
        if (empty($_SESSION['portal_user_id'])) return null;
        $timeout = (int)SticPortalConfigUtils::get('PORTAL_SESSION_TIMEOUT_MINUTES', 60);
        if ($timeout > 0 && isset($_SESSION['portal_last_activity']) && time() - (int)$_SESSION['portal_last_activity'] > $timeout * 60) {
            self::destroyPortalSession();
            return null;
        }
        $_SESSION['portal_last_activity'] = time();
        if (empty($_SESSION['portal_user_type']) || empty($_SESSION['portal_user_id'])) return null;
        $bean = BeanFactory::getBean($_SESSION['portal_user_type'] . 's', $_SESSION['portal_user_id']);
        if (!$bean || !$bean->id || $bean->stic_portal_session_id_c !== session_id() || !$bean->stic_portal_enabled_c) return null;
        return $bean;
    }

    // ── Full authentication ───────────────────────────
    public static function authenticate($username, $password, $remember = false, $ipAddress = '')
    {
        $GLOBALS['log']->debug(__METHOD__ . " - Authenticating user: $username from IP: $ipAddress");
        $result = self::getPortalUserByUsername($username);
        if (!$result) {
            self::recordLoginAudit(null, null, $username, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? '', false, 'not_found', 'password');
            $GLOBALS['log']->info(__METHOD__ . " - User not found: $username");
            return array('success' => false, 'error_code' => 'invalid_credentials', 'error' => 'Invalid credentials');
        }
        $bean = $result['bean'];
        $type = $result['type'];
        $lockout = self::checkLockout($bean);
        if ($lockout['locked']) {
            $mins = max(1, ceil($lockout['remaining_seconds'] / 60));
            self::recordLoginAudit($bean, $type, $username, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? '', false, 'locked_out', 'password');
            $GLOBALS['log']->info(__METHOD__ . " - Account locked: $username, remaining: {$mins}min");
            return array('success' => false, 'error_code' => 'locked', 'error' => "Account locked. Try again in {$mins} minutes");
        }
        if (self::isIpLocked($ipAddress)) {
            self::recordLoginAudit($bean, $type, $username, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? '', false, 'ip_locked', 'password');
            $GLOBALS['log']->info(__METHOD__ . " - IP locked: $ipAddress");
            return array('success' => false, 'error_code' => 'ip_locked', 'error' => 'Too many attempts. Try again later');
        }
        if (!self::verifyPassword($password, $bean->stic_portal_hashed_c)) {
            self::recordFailedAttempt($bean, $ipAddress);
            self::recordLoginAudit($bean, $type, $username, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? '', false, 'invalid_credentials', 'password');
            $GLOBALS['log']->info(__METHOD__ . " - Invalid password for: $username");
            return array('success' => false, 'error_code' => 'invalid_credentials', 'error' => 'Invalid credentials');
        }
        if (self::needsRehash($bean->stic_portal_hashed_c)) {
            $bean->stic_portal_hashed_c = self::hashPassword($password);
            $bean->save();
            $GLOBALS['log']->debug(__METHOD__ . " - Rehashed password for: $username");
        }
        self::resetFailedAttempts($bean);
        self::recordLoginAudit($bean, $type, $username, $ipAddress, $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'password');
        self::sendSecurityNotification($bean, 'new_login');
        $mustChange = ($bean->stic_portal_force_pw_change_c || self::isPasswordExpired($bean));
        if ($remember) self::generateRememberToken($bean);
        self::createPortalSession($bean, $type);
        $GLOBALS['log']->info(__METHOD__ . " - Login successful: $username ({$type})");
        return array('success' => true, 'bean' => $bean, 'type' => $type, 'must_change_password' => $mustChange);
    }

    // ── Before-save logic hook ────────────────────────
    public static function processBeforeSave($bean)
    {
        // Portal password: copy plaintext from virtual field to hashed_c for processing
        if (!empty($bean->stic_portal_password_c)) {
            $bean->stic_portal_hashed_c = $bean->stic_portal_password_c;
            $bean->stic_portal_password_c = '';
            $bean->stic_portal_force_pw_change_c = 1;
        }
        if (empty($bean->stic_portal_hashed_c)) return;
        $submitted = $bean->stic_portal_hashed_c;
        $fetched   = $bean->fetched_row['stic_portal_hashed_c'] ?? null;
        if ($submitted !== $fetched) {
            $plain = $submitted;
            $violations = self::validatePasswordPolicy($plain);
            if (!empty($violations)) throw new RuntimeException('Password policy violations: ' . implode('; ', $violations));
            $oldHash = $fetched;
            if (self::isPasswordInHistory($bean, $plain)) throw new RuntimeException('Password was used recently. Please choose a different password.');
            self::archivePasswordHistory($bean, $oldHash);
            $bean->stic_portal_hashed_c = self::hashPassword($plain);
            $bean->stic_portal_password_changed_c = self::nowDb();
            $bean->stic_portal_force_pw_change_c = 0;
            self::setPasswordExpiration($bean);
            self::clearResetToken($bean);
        } elseif (empty($fetched)) {
            $violations = self::validatePasswordPolicy($submitted);
            if (!empty($violations)) throw new RuntimeException('Password policy violations: ' . implode('; ', $violations));
            $bean->stic_portal_hashed_c = self::hashPassword($submitted);
            $bean->stic_portal_password_changed_c = self::nowDb();
            $bean->stic_portal_force_pw_change_c = 0;
            self::setPasswordExpiration($bean);
        }
    }

    // ── Security notifications ───────────────────────
    public static function sendSecurityNotification($bean, $eventType)
    {
        $settingMap = array('password_changed' => 'PORTAL_NOTIFY_PASSWORD_CHANGED', 'new_login' => 'PORTAL_NOTIFY_NEW_LOGIN', 'account_locked' => 'PORTAL_NOTIFY_ACCOUNT_LOCKED', 'reset_requested' => 'PORTAL_NOTIFY_RESET_REQUESTED');
        $templateMap = array('password_changed' => 'PORTAL_TMPL_NOTIFY_PWCHG', 'new_login' => 'PORTAL_TMPL_NOTIFY_LOGIN', 'account_locked' => 'PORTAL_TMPL_NOTIFY_LOCK', 'reset_requested' => 'PORTAL_TMPL_NOTIFY_RESET');
        if (!isset($settingMap[$eventType]) || SticPortalConfigUtils::get($settingMap[$eventType], '0') !== '1') return false;
        require_once 'include/SugarPHPMailer.php';
        $to = self::getPrimaryEmail($bean);
        if (empty($to)) return false;
        $title = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $now = date('Y-m-d H:i:s');

        $templateId = SticPortalConfigUtils::get($templateMap[$eventType], '');
        $subject = "{$title} - Security notification";
        $bodyHtml = "<p>A security event occurred on your {$title} account.</p><p>Event: {$eventType}<br>Time: {$now}<br>IP: {$ip}<br>Browser: {$ua}</p><p>If this was not you, please contact support immediately.</p>";

        if (!empty($templateId)) {
            $tmpl = BeanFactory::getBean('EmailTemplates', $templateId);
            if ($tmpl && $tmpl->id) {
                if (!empty($tmpl->subject)) $subject = html_entity_decode($tmpl->subject, ENT_QUOTES);
                if (!empty($tmpl->body_html)) $bodyHtml = html_entity_decode($tmpl->body_html, ENT_QUOTES);
            }
        }

        $replace = array(
            '{$notification_time}'   => $now,
            '{$notification_ip}'     => $ip,
            '{$notification_ua}'     => $ua,
            '{$notification_event}'  => $eventType,
            '{$portal_title}'        => $title,
            '{$contact_name}'        => self::getRecipientName($bean),
        );
        $subject  = str_replace(array_keys($replace), array_values($replace), $subject);
        $bodyHtml = str_replace(array_keys($replace), array_values($replace), $bodyHtml);
        $bodyText = strip_tags(str_replace(array('<br>', '</p>'), array("\n", "\n\n"), $bodyHtml));

        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = 'noreply@sinergiacrm.org';
        $mail->FromName = $title;
        $mail->addAddress($to, self::getRecipientName($bean));
        $mail->Subject  = $subject;
        $mail->Body     = $bodyHtml;
        $mail->AltBody  = $bodyText;
        $mail->isHTML(true);
        return $mail->Send();
    }

    // ── Login audit ──────────────────────────────────
    public static function recordLoginAudit($bean, $type, $username, $ipAddress, $userAgent, $success, $failureReason = null, $authMethod = 'password')
    {
        global $db;
        $id = create_guid();
        $now = self::nowDb();
        $query = "INSERT INTO stic_portal_login_audit (id, parent_id, parent_type, username, ip_address, user_agent, success, failure_reason, auth_method, date_entered, date_modified, deleted) VALUES (" . $db->quoted($id) . ", " . ($bean ? $db->quoted($bean->id) : 'NULL') . ", " . ($type ? $db->quoted($type) : 'NULL') . ", " . $db->quoted($username) . ", " . $db->quoted($ipAddress) . ", " . $db->quoted(substr($userAgent, 0, 500)) . ", " . ($success ? '1' : '0') . ", " . ($failureReason ? $db->quoted($failureReason) : 'NULL') . ", " . $db->quoted($authMethod) . ", " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)";
        $db->query($query);
    }

    // ── Helpers ─────────────────────────────────────
    public static function loadCustomFields($bean, $cstmTable)
    {
        global $db;
        $result = $db->limitQuery("SELECT * FROM $cstmTable WHERE id_c=" . $db->quoted($bean->id), 0, 1);
        $row = $db->fetchByAssoc($result);
        if ($row) {
            foreach ($row as $k => $v) { if ($k !== 'id_c') $bean->$k = $v; }
        }
    }

    public static function getPrimaryEmail($bean)
    {
        if (!empty($bean->email1)) return $bean->email1;
        if (isset($bean->emailAddress) && method_exists($bean->emailAddress, 'getPrimaryAddress')) {
            $addr = $bean->emailAddress->getPrimaryAddress($bean);
            if ($addr) return $addr;
        }
        global $db;
        $r = $db->limitQuery("SELECT ea.email_address FROM email_addr_bean_rel eabr JOIN email_addresses ea ON ea.id=eabr.email_address_id WHERE eabr.bean_id=" . $db->quoted($bean->id) . " AND eabr.primary_address=1 AND eabr.deleted=0 AND ea.deleted=0", 0, 1);
        $row = $db->fetchByAssoc($r);
        return $row ? $row['email_address'] : '';
    }

    public static function getRecipientName($bean)
    {
        if (!empty($bean->first_name) || !empty($bean->last_name)) return trim(($bean->first_name ?? '') . ' ' . ($bean->last_name ?? ''));
        return $bean->name ?? '';
    }

    // ── Rate limit for magic link ────────────────────
    public static function isMagicLinkRateLimited($identifier, $identifierType = 'username')
    {
        global $db;
        $maxPerMin = 1; $maxPerHr = 5;
        $ident = $db->quoted($identifier);
        $idType = $db->quoted($identifierType);
        $result = $db->query("SELECT COUNT(*) c FROM stic_portal_magic_rate_limit WHERE identifier=$ident AND identifier_type=$idType AND window_start > DATE_SUB(NOW(), INTERVAL 60 SECOND) AND deleted=0");
        $row = $db->fetchByAssoc($result);
        if ($row && (int)$row['c'] >= $maxPerMin) return true;
        if ($identifierType === 'ip') {
            $result = $db->query("SELECT COUNT(*) c FROM stic_portal_magic_rate_limit WHERE identifier=$ident AND identifier_type='ip' AND window_start > DATE_SUB(NOW(), INTERVAL 1 HOUR) AND deleted=0");
            $row = $db->fetchByAssoc($result);
            if ($row && (int)$row['c'] >= $maxPerHr) return true;
        }
        $id = create_guid();
        $now = self::nowDb();
        $db->query("INSERT INTO stic_portal_magic_rate_limit (id, identifier, identifier_type, window_start) VALUES (" . $db->quoted($id) . ", $ident, $idType, " . $db->quoted($now) . ")");
        return false;
    }

    // ── Password change ──────────────────────────────
    public static function changePassword($bean, $currentPassword, $newPassword, $confirmPassword)
    {
        if ($newPassword !== $confirmPassword) return array('success' => false, 'error' => 'Passwords do not match');
        if (!self::verifyPassword($currentPassword, $bean->stic_portal_hashed_c)) return array('success' => false, 'error' => 'Current password is incorrect');
        $violations = self::validatePasswordPolicy($newPassword);
        if (!empty($violations)) return array('success' => false, 'error' => 'Password policy: ' . implode('; ', $violations));
        if (self::isPasswordInHistory($bean, $newPassword)) return array('success' => false, 'error' => 'Password was used recently');
        self::archivePasswordHistory($bean, $bean->stic_portal_hashed_c);
        $bean->stic_portal_hashed_c = self::hashPassword($newPassword);
        $bean->stic_portal_password_changed_c = self::nowDb();
        $bean->stic_portal_force_pw_change_c = 0;
        self::setPasswordExpiration($bean);
        $bean->save();
        self::sendSecurityNotification($bean, 'password_changed');
        return array('success' => true);
    }
}
