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
 * Generates an OAuth2 authorization code, stores it via the OAuth2Tokens bean,
 * and redirects the browser to the client's callback URL.
 */
class SticPortalAuthCodeGenerator
{
    public static function generateAndRedirect($portalId, $portalType, $clientId, $redirectUri, $state = '')
    {
        $code = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 600);

        $token = BeanFactory::newBean('OAuth2Tokens');
        $token->id = create_guid();
        $token->new_with_id = true;
        $token->access_token = $code;
        $token->access_token_expires = $expires;
        $token->token_type = 'auth_code';
        $token->token_is_revoked = 0;
        $token->description = $portalType . '|' . $portalId;
        $token->client = $clientId;
        $token->save();

        $sep = (strpos($redirectUri, '?') === false) ? '?' : '&';
        $url = $redirectUri . $sep . 'code=' . urlencode($code) . (!empty($state) ? '&state=' . urlencode($state) : '');
        header('Location: ' . $url);
        exit;
    }
}
