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

/**
 * Utilities for Google OAuth authentication.
 */
class GoogleUtils
{
    /**
     * Get the login parameters for Google authentication.
     *
     * @param array $sugar_config The configuration array.
     * @return array The login parameters for Google authentication.
     */
    public static function getLoginParams($providerSettings)
    {
        return [
            'enabled' => $providerSettings['enabled'] ?? false,
            'clientId' => $providerSettings['clientId'] ?? '',
        ];
    }

    /**
     * Get the admin parameters for Google authentication.
     * 
     * @param array $sugar_config The configuration array.
     * @return array The admin parameters for Google authentication.
     */
    public static function getAdminParams($providerSettings) {
        $backendParams = [
            'clientSecret' => $providerSettings['clientSecret'] ?? '',
            'scopes' => $providerSettings['scopes'] ?? 'openid user.email user.profile',
        ];
        return array_merge(self::getLoginParams($providerSettings), $backendParams);
    }

    /**
     * Verifies a Google ID token sent from the frontend.
     * 
     * @return array The decoded token payload (claims) on success, or an empty array on failure.
     */
    public static function verifyToken() {
        global $sugar_config;
        if (isset($_REQUEST['google_credentials']) && $_REQUEST['google_credentials'] != '') {
            $client = new Google_Client(['client_id' => $sugar_config['authenticationOauthProviders']['Google']['clientId']]);
            $client->setClientId($sugar_config['authenticationOauthProviders']['Google']['clientId']);
            $client->setClientSecret($sugar_config['authenticationOauthProviders']['Google']['clientSecret']);
            return $client->verifyIdToken($_REQUEST['google_credentials']);
        } else {
            return [];
        }
    }
}