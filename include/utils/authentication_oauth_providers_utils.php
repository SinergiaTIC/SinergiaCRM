<?php

function displayAuthenticateOAuthProviders() {
    $providersContentTemplate = new Sugar_Smarty();
    $providersContent = "<div id='oauth_providers'><span id='label_oauth_providers'>OR</span>";
    $providersContent = "<input type='hidden' id='oauth_provider' name='oauth_provider' />";

    


    if (file_exists('custom/modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php')) {
        require_once('custom/modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php');
        $oAuthClass = 'CustomOAuthAuthenticate';
    } else if (file_exists('modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php')) {
        require_once('modules/Users/authentication/OAuthAuthenticate/OAuthAuthenticate.php');
        $oAuthClass = 'OAuthAuthenticate';
    } else {
        return '';
    }
    
    $providers = $oAuthClass::getEnabledOAuthProviders();

    foreach($providers as $provider) {
        $providerOAuthClass = new $oAuthClass($provider);
        $providersContentTemplate->assign('PROVIDER_' . strtoupper($provider) . '_PARAMS', json_encode($providerOAuthClass->getLoginParams()));
        $providersContent .= $providerOAuthClass->getLoginTemplate($providersContentTemplate);
    }

    // TODO ADD optin to hide basic form
    if (!empty($providersContent)) {
        // return '';
    }
    $providersContent .= "</div>";
    return $providersContent;
}
