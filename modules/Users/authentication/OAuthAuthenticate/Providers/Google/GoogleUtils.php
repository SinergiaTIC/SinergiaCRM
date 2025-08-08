<?php

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
            'auth_only_form' => $providerSettings['auth_only_form'] ?? false,
            'auth_enabled' => $providerSettings['enabled'] ?? false,
            'auth_client_id' => $providerSettings['auth_client_id'] ?? '',
        ];
    }

    public static function getLoginTemplate(&$ss) {
        if (file_exists('custom/modules/Users/authentication/OAuthAuthenticate/Providers/Google/GoogleLoginTemplate.tpl')) {
            return $ss->fetch('custom/modules/Users/authentication/OAuthAuthenticate/Providers/Google/GoogleLoginTemplate.tpl');
        } else if (file_exists('modules/Users/authentication/OAuthAuthenticate/Providers/Google/GoogleLoginTemplate.tpl')) {
            return $ss->fetch('modules/Users/authentication/OAuthAuthenticate/Providers/Google/GoogleLoginTemplate.tpl');
        } else {
            return '';
        }
    }

    public static function verifyToken() {
        global $sugar_config;
        if (isset($_REQUEST['google_credentials']) && $_REQUEST['google_credentials'] != '') {
            $client = new Google_Client(['client_id' => $sugar_config['google_auth_client_id']]);
            $client->setClientId($sugar_config['google_auth_client_id']);
            $client->setClientSecret($sugar_config['google_signin_clientsecret']);
            return $client->verifyIdToken($_REQUEST['google_credentials']);
        } else {
            return [];
        }
    }
}