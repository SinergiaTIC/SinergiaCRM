<?php

function displayAdminAuthenticationOAuthProviders() {
    global $current_language;

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

function displayAuthenticationOAuthAuthentication() {
    $providersContentTemplate = new Sugar_Smarty();
    $providersContent = "<div id='oauth_providers'><span id='label_oauth_providers'>OR</span>";
    $providersContent = "<input type='hidden' id='oauth_provider' name='oauth_provider' />";

    if (!$oAuthClass = getOAuthProviderClass()) {
        return false;
    }
    $providers = getEnabledOAuthProviders();

    foreach($providers as $provider) {
        $providerOAuthClass = new $oAuthClass($provider);
        $providersContent .= $providerOAuthClass->getLoginTemplate($providersContentTemplate);
    }

    // TODO ADD optin to hide basic form
    if (!empty($providersContent)) {
        // return '';
    }
    $providersContent .= "</div>";
    return $providersContent;
}

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


function getEnabledOAuthProviders () 
{
    global $sugar_config;

    $providers = [];
    if (isset($sugar_config['authenticationOauthProviders']) && is_array($sugar_config['authenticationOauthProviders'])) {
        foreach ($sugar_config['authenticationOauthProviders'] as $provider => $settings) {
            if (isset($settings['enabled']) && $settings['enabled'] == true) {
                $providers[] = $provider;
            }
        }
    }
    return $providers;
}

function getAvailableOauthProviders() {
    $corePath   = 'modules/Users/authentication/OAuthAuthenticate/Providers';
    $customPath = 'custom/modules/Users/authentication/OAuthAuthenticate/Providers';

    // Get folder names (basenames) from core and custom
    $coreProviders   = is_dir($corePath)   ? array_map('basename', glob($corePath . '/*', GLOB_ONLYDIR)) : [];
    $customProviders = is_dir($customPath) ? array_map('basename', glob($customPath . '/*', GLOB_ONLYDIR)) : [];

    // Merge and remove duplicates
    return array_values(array_unique(array_merge($coreProviders, $customProviders)));
}
