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

/**
 * Generates an OAuth2 authorization code, stores it in oauth2tokens,
 * and redirects the browser to the client's callback URL.
 */
class SticPortalAuthCodeGenerator
{
    public static function generateAndRedirect($portalId, $portalType, $clientId, $redirectUri, $state = '')
    {
        global $db;
        $code = bin2hex(random_bytes(32));
        $now = date('Y-m-d H:i:s');
        $expires = date('Y-m-d H:i:s', time() + 600);
        $db->query("INSERT INTO oauth2tokens (id, access_token, access_token_expires, token_type, token_is_revoked, platform, client, assigned_user_id, date_entered, date_modified, deleted) VALUES ("
            . $db->quoted(create_guid()) . ", " . $db->quoted($code) . ", " . $db->quoted($expires) . ", "
            . $db->quoted('auth_code') . ", 0, " . $db->quoted($portalType) . ", "
            . $db->quoted($clientId) . ", " . $db->quoted($portalId) . ", "
            . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)"
        );
        $sep = (strpos($redirectUri, '?') === false) ? '?' : '&';
        $url = $redirectUri . $sep . 'code=' . urlencode($code) . (!empty($state) ? '&state=' . urlencode($state) : '');
        header('Location: ' . $url);
        exit;
    }
}
