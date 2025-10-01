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

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Utilities for Microsoft OAuth authentication.
 */
class MicrosoftUtils
{
    /**
     * Get the login parameters for Microsoft authentication.
     * 
     * @param array $sugar_config The configuration array.
     * @return array The login parameters for Microsoft authentication.
     */
    public static function getLoginParams($providerSettings)
    {
        global $sugar_config;

        return [
            'enabled' => $providerSettings['enabled'] ?? false,
            'clientId' => $providerSettings['clientId'] ?? '',
            'tenantId' => $providerSettings['tenantId'] ?? '',
            'scopes' => $providerSettings['scopes'] ?: 'openid profile email User.Read',
            'redirectUri' => $providerSettings['redirectUri'] ?: $sugar_config['site_url'].'/index.php?module=Users&action=Login',
        ];
    }

    /**
     * Get the admin parameters for Microsoft authentication.
     * 
     * @param array $sugar_config The configuration array.
     * @return array The admin parameters for Microsoft authentication.
     */
    public static function getAdminParams($providerSettings) {
        $backendParams = [
            // We let this available in case it's necessary for future backend calls
        ];
        return array_merge(self::getLoginParams($providerSettings), $backendParams);
    }

    /**
     * Verifies a Microsoft ID token sent from the frontend.
     *
     * @return stdClass|false The decoded token payload (claims) on success, or false on failure.
     */
    public static function verifyToken()
    {
        global $sugar_config; // Or however you access your app's configuration

        if (empty($_REQUEST['microsoft_credentials'])) {
            return false;
        }

        $tenantId = $sugar_config['authenticationOauthProviders']['Microsoft']['tenantId'] ?? ''; 
        $clientId = $sugar_config['authenticationOauthProviders']['Microsoft']['clientId'] ?? '';
        // --------------------------------------------------------------------

        $idToken = $_REQUEST['microsoft_credentials'];

        try {
            // Get Microsoft's public signing keys
            // The keys are at a URL found in Microsoft's OpenID Connect discovery document.
            $discoveryUrl = "https://login.microsoftonline.com/{$tenantId}/v2.0/.well-known/openid-configuration";
            
            $httpClient = new GuzzleClient();
            $discoveryResponse = $httpClient->get($discoveryUrl);
            $discoveryJson = json_decode($discoveryResponse->getBody()->getContents());
            
            $jwksUri = $discoveryJson->jwks_uri;
            $jwksResponse = $httpClient->get($jwksUri);
            $jwks = json_decode($jwksResponse->getBody()->getContents(), true);

            // Decode the ID token
            // The library finds the correct key, verifies the signature, and checks standard claims like expiration ('exp').
            $decodedToken = JWT::decode($idToken, JWK::parseKeySet($jwks, 'RS256'));

            // Manually verify critical claims
            // Issuer ('iss'): Confirms the token was issued by Microsoft for your tenant.
            $expectedIssuer = "https://login.microsoftonline.com/{$tenantId}/v2.0";
            if ($decodedToken->iss !== $expectedIssuer) {
                $GLOBALS['log']->fatal("Token issuer mismatch. Expected: {$expectedIssuer}, Got: {$decodedToken->iss}");
                return false;
            }

            // Audience ('aud'): Confirms the token was intended for your application.
            if ($decodedToken->aud !== $clientId) {
                $GLOBALS['log']->fatal("Token audience mismatch. Expected: {$clientId}, Got: {$decodedToken->aud}");
                return false;
            }

            return (array) $decodedToken;

        } catch (Exception $e) {
            // The token is invalid (e.g., bad signature, expired, malformed)
            $GLOBALS['log']->fatal("Microsoft Token verification failed: " . $e->getMessage());
            return false;
        }
    }
}