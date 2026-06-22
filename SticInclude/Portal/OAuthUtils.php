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
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class SticPortalOAuthUtils
{
    private static $td;
    private static function td() { if (!self::$td) self::$td = TimeDate::getInstance(); return self::$td; }
    private static function nowDb() { return self::td()->nowDb(); }
    private static function futureDb($s) { return self::td()->getNow()->modify("+{$s} seconds")->asDb(); }

    /**
     * Validate that a client_id exists in OAuth2Clients and the redirect_uri
     * starts with the registered redirect_url. Returns the client row or null.
     */
    public static function validateClient($clientId, $redirectUri)
    {
        $client = BeanFactory::getBean('OAuth2Clients', $clientId);
        if (!$client || !$client->id || $client->deleted == 1) {
            $GLOBALS['log']->debug(__METHOD__ . " - Client not found: $clientId");
            return null;
        }
        // Only validate redirect_uri if provided (token exchange may not send it)
        if (!empty($redirectUri) && !empty($client->redirect_url) && strpos($redirectUri, $client->redirect_url) !== 0) {
            $GLOBALS['log']->debug(__METHOD__ . " - Redirect URI mismatch: $redirectUri vs registered {$client->redirect_url}");
            return null;
        }
        $GLOBALS['log']->debug(__METHOD__ . " - Client validated: $clientId");
        return $client;
    }

    public static function createAuthCode($portalId, $portalType, $clientId, $redirectUri)
    {
        global $db;
        $code = bin2hex(random_bytes(32));
        $now = self::nowDb();
        $expires = self::futureDb(600);
        $db->query("INSERT INTO stic_portal_oauth_codes (id, authorization_code, client_id, portal_id, portal_type, redirect_uri, expires_at, date_entered, date_modified, deleted) VALUES (" . $db->quoted(create_guid()) . ", " . $db->quoted($code) . ", " . $db->quoted($clientId) . ", " . $db->quoted($portalId) . ", " . $db->quoted($portalType) . ", " . $db->quoted($redirectUri) . ", " . $db->quoted($expires) . ", " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");
        $GLOBALS['log']->info(__METHOD__ . " - Auth code created for {$portalType}:{$portalId} / client:{$clientId}");
        return $code;
    }

    public static function consumeAuthCode($code, $clientId, $redirectUri)
    {
        global $db;
        $result = $db->limitQuery("SELECT * FROM stic_portal_oauth_codes WHERE authorization_code=" . $db->quoted($code) . " AND deleted=0 AND is_revoked=0", 0, 1);
        $row = $db->fetchByAssoc($result);
        if (!$row) { $GLOBALS['log']->debug(__METHOD__ . " - Auth code not found"); return null; }
        if (strtotime($row['expires_at']) < time()) { $GLOBALS['log']->debug(__METHOD__ . " - Auth code expired"); return null; }
        if (!empty($clientId) && $row['client_id'] !== $clientId) { $GLOBALS['log']->debug(__METHOD__ . " - Client ID mismatch"); return null; }
        if (!empty($redirectUri) && $row['redirect_uri'] !== $redirectUri) { $GLOBALS['log']->debug(__METHOD__ . " - Redirect URI mismatch"); return null; }
        $db->query("UPDATE stic_portal_oauth_codes SET is_revoked=1 WHERE id=" . $db->quoted($row['id']));
        $GLOBALS['log']->info(__METHOD__ . " - Auth code consumed: {$row['portal_type']}:{$row['portal_id']}");
        return array('portal_id' => $row['portal_id'], 'portal_type' => $row['portal_type']);
    }

    public static function issueTokens($portalId, $portalType, $clientId)
    {
        global $db;
        $now = self::nowDb();
        $accessExpires  = self::futureDb(3600);
        $refreshExpires = self::futureDb(2592000);
        $accessToken  = bin2hex(random_bytes(32));
        $refreshToken = bin2hex(random_bytes(32));
        $db->query("INSERT INTO stic_portal_oauth_tokens (id, access_token, refresh_token, client_id, portal_id, portal_type, access_token_expires, refresh_token_expires, date_entered, date_modified, deleted) VALUES (" . $db->quoted(create_guid()) . ", " . $db->quoted($accessToken) . ", " . $db->quoted($refreshToken) . ", " . $db->quoted($clientId) . ", " . $db->quoted($portalId) . ", " . $db->quoted($portalType) . ", " . $db->quoted($accessExpires) . ", " . $db->quoted($refreshExpires) . ", " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");
        $GLOBALS['log']->info(__METHOD__ . " - Tokens issued for {$portalType}:{$portalId}");
        return array('access_token' => $accessToken, 'token_type' => 'Bearer', 'expires_in' => 3600, 'refresh_token' => $refreshToken, 'scope' => 'portal');
    }

    public static function validateAccessToken($accessToken)
    {
        global $db;
        $result = $db->limitQuery("SELECT * FROM stic_portal_oauth_tokens WHERE access_token=" . $db->quoted($accessToken) . " AND deleted=0 AND is_revoked=0", 0, 1);
        $row = $db->fetchByAssoc($result);
        if (!$row || strtotime($row['access_token_expires']) < time()) return null;
        return array('portal_id' => $row['portal_id'], 'portal_type' => $row['portal_type'], 'client_id' => $row['client_id']);
    }

    public static function refreshToken($refreshToken, $clientId)
    {
        global $db;
        $result = $db->limitQuery("SELECT * FROM stic_portal_oauth_tokens WHERE refresh_token=" . $db->quoted($refreshToken) . " AND client_id=" . $db->quoted($clientId) . " AND deleted=0 AND is_revoked=0", 0, 1);
        $row = $db->fetchByAssoc($result);
        if (!$row || strtotime($row['refresh_token_expires']) < time()) return null;
        $db->query("UPDATE stic_portal_oauth_tokens SET is_revoked=1 WHERE id=" . $db->quoted($row['id']));
        $GLOBALS['log']->info(__METHOD__ . " - Refresh token used for client:{$clientId}");
        return self::issueTokens($row['portal_id'], $row['portal_type'], $clientId);
    }

    public static function revokeToken($accessToken)
    {
        global $db;
        $db->query("UPDATE stic_portal_oauth_tokens SET is_revoked=1 WHERE access_token=" . $db->quoted($accessToken) . " AND deleted=0");
        $GLOBALS['log']->info(__METHOD__ . " - Token revoked");
    }
}
