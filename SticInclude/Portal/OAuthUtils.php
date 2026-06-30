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
        if (!empty($redirectUri) && !empty($client->redirect_url) && strpos($redirectUri, $client->redirect_url) !== 0) {
            $GLOBALS['log']->debug(__METHOD__ . " - Redirect URI mismatch: $redirectUri vs registered {$client->redirect_url}");
            return null;
        }
        $GLOBALS['log']->debug(__METHOD__ . " - Client validated: $clientId");
        return $client;
    }
}
