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
            // 'auth_only_form' => $providerSettings['auth_only_form'] ?? false,
            'enabled' => $providerSettings['enabled'] ?? false,
            'clientId' => $providerSettings['clientId'] ?? '',
        ];
    }

    public static function getAdminParams($providerSettings) {
        $backendParams = [
            'clientSecret' => $providerSettings['clientSecret'] ?? '',
            'scopes' => $providerSettings['scopes'] ?? 'openid user.email user.profile',
        ];
        return array_merge(self::getLoginParams($providerSettings), $backendParams);
    }

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