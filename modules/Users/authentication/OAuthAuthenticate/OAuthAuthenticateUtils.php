<?php
// STIC-Custom AAM 20250825 - Adding OAuth Authentication providers
// https://github.com/SinergiaTIC/SinergiaCRM/pull/552
/**
 * Utils for OAuth authentication. 
 */
/**
 * Display all available OAuth providers in admin configuration page.
 */
function displayAdminAuthenticationOAuthProviders() {
    if (!$oAuthClass = getOAuthProviderClass()) {
        return false;
    }

    $providersContentTemplate = new Sugar_Smarty();
    $providersContent = "";
    
    foreach (getAvailableOauthProviders() as $provider) {
        $providerOAuthClass = new $oAuthClass($provider);
        $providersContent .= $providerOAuthClass->getAdminTemplate($providersContentTemplate);
    }
    return $providersContent;
}

/**
 * Display all enabled OAuth providers in login page.
 */
function displayLoginOAuthAuthentication() {
    global $sugar_config;
    if (!$oAuthClass = getOAuthProviderClass()) {
        return false;
    }
    if (isset($sugar_config['authenticationClass']) && $sugar_config['authenticationClass'] == 'OAuthAuthenticate') {
        if ($providers = getEnabledOAuthProviders()) {
            $providersContentTemplate = new Sugar_Smarty();
            $providersContent = "<div id='oauth_providers' name='oauth_providers' class='oauth-providers-container'><span id='label_oauth_providers'>".translate('LBL_OAUTH_AUTH_LOGIN_CONTAINER', "Users")."</span>";
            $providersContent .= "<input type='hidden' id='oauth_provider' name='oauth_provider' />";

            foreach($providers as $provider) {
                $providerOAuthClass = new $oAuthClass($provider);
                $providersContent .= $providerOAuthClass->getLoginTemplate($providersContentTemplate);
            }

            // TODO ADD option to hide basic form
            if (!empty($providersContent)) {
                // return '';
            }
            $providersContent .= "</div>";
            return $providersContent;
        }
    }
    return '';
}

/**
 * Get OAuthAuthenticate class name, depending if custom or core implementation exists.
 */
function getOAuthProviderClass () {
    if (file_exists('custom/modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php')) {
        require_once('custom/modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php');
        return 'CustomOAuthAuthenticate';
    } else if (file_exists('modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php')) {
        require_once('modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php');
        return 'OAuthAuthenticate';
    } else {
        return '';
    }
}

/**
 * Get enabled OAuth providers from config.php
 */
function getEnabledOAuthProviders () 
{
    global $sugar_config;
    $providers = [];
    if (isset($sugar_config['authenticationOauthProviders']) && is_array($sugar_config['authenticationOauthProviders'])) {
        foreach ($sugar_config['authenticationOauthProviders'] as $provider => $settings) {
            if (isset($settings['enabled']) && $settings['enabled'] == 'true') {
                $providers[] = $provider;
            }
        }
    }
    return $providers;
}

/**
 * Get all available OAuth providers (core + custom)
 */
function getAvailableOauthProviders() {
    $corePath   = 'modules/Users/authentication/OAuthAuthenticate/Providers';
    $customPath = 'custom/modules/Users/authentication/OAuthAuthenticate/Providers';

    // Get folder names (basenames) from core and custom
    $coreProviders   = is_dir($corePath)   ? array_map('basename', glob($corePath . '/*', GLOB_ONLYDIR)) : [];
    $customProviders = is_dir($customPath) ? array_map('basename', glob($customPath . '/*', GLOB_ONLYDIR)) : [];

    // Merge and remove duplicates
    return array_values(array_unique(array_merge($coreProviders, $customProviders)));
}

// END STIC-Custom