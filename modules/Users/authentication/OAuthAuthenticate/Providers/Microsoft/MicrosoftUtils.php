<?php

// Make sure to include your Composer autoloader, e.g., require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;

use GuzzleHttp\Client as GuzzleClient;

class MicrosoftUtils
{
    public static function getLoginParams($providerSettings)
    {
        global $sugar_config;

        return [
            'enabled' => $providerSettings['enabled'] ?? false,
            'clientId' => $providerSettings['clientId'] ?? '',
            'tenantId' => $providerSettings['tenantId'] ?? '',
            'scopes' => $providerSettings['scopes'] ?? 'openid profile email User.Read',
            'redirectUri' => $providerSettings['redirectUri'] ?: $sugar_config['site_url'].'/index.php?module=Users&action=Login',
        ];
    }

    public static function getAdminParams($providerSettings) {
        $backendParams = [
            // 'clientSecret' => $providerSettings['clientSecret'] ?? '',
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
            // ## Step 1: Get Microsoft's public signing keys
            // The keys are at a URL found in Microsoft's OpenID Connect discovery document.
            $discoveryUrl = "https://login.microsoftonline.com/{$tenantId}/v2.0/.well-known/openid-configuration";
            
            $httpClient = new GuzzleClient();
            $discoveryResponse = $httpClient->get($discoveryUrl);
            $discoveryJson = json_decode($discoveryResponse->getBody()->getContents());
            
            $jwksUri = $discoveryJson->jwks_uri;
            $jwksResponse = $httpClient->get($jwksUri);
            $jwks = json_decode($jwksResponse->getBody()->getContents(), true);

            // ## Step 2: Decode the ID token
            // The library finds the correct key, verifies the signature, and checks standard claims like expiration ('exp').
            $decodedToken = JWT::decode($idToken, JWK::parseKeySet($jwks, 'RS256'));

            // ## Step 3: Manually verify critical claims
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