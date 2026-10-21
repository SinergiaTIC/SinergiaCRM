<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
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
 * SSO helpers shared by the token entry point and action_sdaRedirect.
 *
 * Mints short-lived, single-use JWTs that SinergiaDA validates to open a
 * session for CRM users without asking for credentials.
 */
class SticSdaSso
{
    /** Token lifetime in seconds (keep in sync with SDA SSO_TTL). */
    const TTL = 60;

    /** JWT issuer claim. */
    const ISS = 'sinergiacrm';

    /** JWT audience claim. */
    const AUD = 'sinergiada';

    /**
     * Whether the SSO integration is usable (SDA enabled + dedicated secret set).
     *
     * @return bool
     */
    public static function isReady()
    {
        global $sugar_config;

        if (empty($sugar_config['stic_sinergiada']['enabled'])) {
            return false;
        }

        return self::getSecret() !== '';
    }

    /**
     * Dedicated SSO signing secret. Never reuse the session seed_string.
     *
     * @return string
     */
    public static function getSecret()
    {
        global $sugar_config;

        $secret = $sugar_config['stic_sinergiada']['sso_secret'] ?? '';

        return is_string($secret) ? trim($secret) : '';
    }

    /**
     * Permission of a CRM user in SinergiaDA.
     *
     * @param string $userId CRM user id.
     * @return int|false 1 = allowed, 2 = read-only, false = not allowed.
     */
    public static function getAllowed($userId)
    {
        if (empty($userId)) {
            return false;
        }

        $db = DBManagerFactory::getInstance();
        $q = "SELECT sda_allowed_c FROM users_cstm WHERE id_c = '" . $db->quote($userId) . "'";
        $r = $db->query($q);
        $a = $db->fetchByAssoc($r);
        $allowed = (int) ($a['sda_allowed_c'] ?? 0);

        return in_array($allowed, [1, 2], true) ? $allowed : false;
    }

    /**
     * Mint the SSO JWT for a CRM user.
     *
     * @param string $userName CRM user_name (becomes the "sub" claim).
     * @param int    $allowed  users_cstm.sda_allowed_c (2 = read-only).
     * @return string|null The JWT, or null when SSO is not configured.
     */
    public static function mint($userName, $allowed)
    {
        $secret = self::getSecret();
        if ($secret === '' || $userName === '' || $userName === null) {
            return null;
        }

        $now = time();
        $claims = [
            'iss' => self::ISS,
            'aud' => self::AUD,
            'sub' => (string) $userName,
            'iat' => $now,
            'exp' => $now + self::TTL,
            'jti' => bin2hex(random_bytes(16)),
        ];
        if ((int) $allowed === 2) {
            $claims['ro'] = true;
        }

        try {
            return \Firebase\JWT\JWT::encode($claims, $secret, 'HS256');
        } catch (\Exception $e) {
            $GLOBALS['log']->error(
                'Line ' . __LINE__ . ': ' . __METHOD__ . ': Unable to mint SDA SSO token: ' . $e->getMessage()
            );
            return null;
        }
    }

    /**
     * Invalidates the SinergiaDA sessions of the user logging out of SinergiaCRM.
     *
     * Fired from the Users module before_logout hook. Failures are logged and
     * never block the CRM logout.
     *
     * @param SugarBean $bean      User bean being logged out.
     * @param string    $event     Hook event (unused).
     * @param array     $arguments Hook arguments (unused).
     * @return void
     */
    public static function notifyLogout(&$bean, $event = null, $arguments = null)
    {
        if (!self::isReady() || empty($bean->user_name)) {
            return;
        }

        if (self::getAllowed($bean->id) === false) {
            return;
        }

        $token = self::mint($bean->user_name, 1);
        $url = self::getApiUrl('sso/logout');
        if ($token === null || $url === '') {
            return;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('token' => $token)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($status !== 200) {
            $GLOBALS['log']->error(
                'Line ' . __LINE__ . ': ' . __METHOD__ . ': SinergiaDA SSO logout failed for user ['
                . $bean->user_name . ']: ' . ($error !== '' ? $error : 'HTTP ' . $status)
            );
        }
    }

    /**
     * SinergiaDA API base URL (the public URL serves the API under /edapi).
     *
     * @param string $path Endpoint path.
     * @return string Full URL, or '' when the integration is not configured.
     */
    private static function getApiUrl($path)
    {
        global $sugar_config;

        $base = trim($sugar_config['stic_sinergiada']['sso_api_url'] ?? '');
        if ($base === '') {
            $public = trim($sugar_config['stic_sinergiada_public']['url'] ?? '');
            $base = $public === '' ? '' : rtrim($public, '/') . '/edapi';
        }

        return $base === '' ? '' : rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Sanitize the "return" route echoed back to SinergiaDA.
     * Only relative, single-token paths are accepted (open-redirect protection).
     *
     * @param mixed $return Raw value from the request.
     * @return string Safe relative route or '' when rejected.
     */
    public static function sanitizeReturn($return)
    {
        if (!is_string($return)) {
            return '';
        }
        $return = trim($return);
        if ($return === '' || strlen($return) > 256) {
            return '';
        }
        // No control characters, quotes, angle brackets or backslashes.
        if (preg_match('/[\x00-\x1F\x7F<>"\'\\\\]/', $return)) {
            return '';
        }
        // No absolute URLs or protocol-relative paths.
        if (preg_match('#^[a-z][a-z0-9+.\-]*:#i', $return) || strpos($return, '//') === 0) {
            return '';
        }
        // No parent-directory traversal (checked raw and percent-decoded).
        $decodedReturn = rawurldecode($return);
        if (preg_match('#(^|/)\.\.(/|$)#', $return) || preg_match('#(^|/)\.\.(/|\\\\|$)#', $decodedReturn)) {
            return '';
        }

        return $return;
    }
}
