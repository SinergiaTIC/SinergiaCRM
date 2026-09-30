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
    /**
     * Validate that a client_id exists, uses the portal authorization-code grant
     * type, and (for confidential clients with a stored secret) that the supplied
     * client_secret matches. Also verifies that redirect_uri starts with the
     * client's registered redirect_url when one is configured.
     *
     * @param string $clientId
     * @param string $redirectUri
     * @param string $clientSecret Optional secret supplied by the caller.
     * @return SugarBean|null The validated client bean, or null.
     */
    public static function validateClient($clientId, $redirectUri, $clientSecret = '')
    {
        $client = BeanFactory::getBean('OAuth2Clients', $clientId);
        if (!$client || !$client->id || $client->deleted == 1) {
            $GLOBALS['log']->debug(__METHOD__ . " - Client not found: $clientId");
            return null;
        }
        if ($client->allowed_grant_type !== 'portal_authorization_code') {
            $GLOBALS['log']->debug(__METHOD__ . " - Client {$client->name} has grant type {$client->allowed_grant_type}, not portal_authorization_code");
            return null;
        }
        if (!empty($redirectUri)) {
            $registered = $client->redirect_url ?? '';
            // A registered redirect_url must exist and the supplied URI must start with it,
            // otherwise any redirect_uri would be accepted (open-redirect / code-capture vector).
            if (empty($registered) || strpos($redirectUri, $registered) !== 0) {
                $GLOBALS['log']->debug(__METHOD__ . " - Redirect URI mismatch: $redirectUri vs registered " . ($registered ?: '(none)'));
                return null;
            }
        }
        // Confidential clients (those with a stored secret) must authenticate with client_secret.
        if (!empty($client->secret) && hash('sha256', (string)$clientSecret) !== $client->secret) {
            $GLOBALS['log']->debug(__METHOD__ . " - Client secret mismatch for: $clientId");
            return null;
        }
        $GLOBALS['log']->debug(__METHOD__ . " - Client validated: $clientId");
        return $client;
    }
}
