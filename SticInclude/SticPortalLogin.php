<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';
require_once 'SticInclude/SticPortalConfigUtils.php';
require_once 'SticInclude/SticPortalOAuthUtils.php';

session_start();
$message = '';
$error   = '';
$mode    = 'password';
// Detect OAuth flow from URL params
$oauthClientId    = $_GET['client_id'] ?? $_POST['client_id'] ?? '';
$oauthRedirectUri = $_GET['redirect_uri'] ?? $_POST['redirect_uri'] ?? '';
$oauthState       = $_GET['state'] ?? $_POST['state'] ?? '';
$isOAuth = !empty($oauthClientId) && !empty($oauthRedirectUri);

// Validate OAuth client if present
$oauthClient = null;
if ($isOAuth) {
    $oauthClient = SticPortalOAuthUtils::validateClient($oauthClientId, $oauthRedirectUri);
    if (!$oauthClient) {
        $error = 'Invalid OAuth client or redirect URI.';
        $isOAuth = false;
        $GLOBALS['log']->info("Portal OAuth - Invalid client_id=$oauthClientId or redirect_uri");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || empty($_SESSION['portal_csrf_token']) || $_POST['csrf_token'] !== $_SESSION['portal_csrf_token']) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = $_POST['username'] ?? '';
        $mode     = $_POST['portal_mode'] ?? 'password';
        $magicEnabled = SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_ENABLED', '0');
        if ($mode === 'magic_link' && $magicEnabled === '1') {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            if (SticPortalAuthUtils::isMagicLinkRateLimited($username, 'username') || SticPortalAuthUtils::isMagicLinkRateLimited($ip, 'ip')) {
                $message = 'If the account exists, a magic link has been sent to your email.';
            } else {
                $result = SticPortalAuthUtils::getPortalUserByUsername($username);
                if ($result) SticPortalAuthUtils::generateMagicLinkToken($result['bean']);
                $message = 'If the account exists, a magic link has been sent to your email.';
            }
        } else {
            $password = $_POST['password'] ?? '';
            $remember = !empty($_POST['remember']);
            $ip       = $_SERVER['REMOTE_ADDR'] ?? '';
            $auth     = SticPortalAuthUtils::authenticate($username, $password, $remember, $ip);
            if ($auth['success']) {
                // OAuth flow: generate auth code and redirect
                if ($isOAuth) {
                    $code = SticPortalOAuthUtils::createAuthCode($auth['bean']->id, $auth['type'], $oauthClientId, $oauthRedirectUri);
                    $sep = (strpos($oauthRedirectUri, '?') === false) ? '?' : '&';
                    $redirect = $oauthRedirectUri . $sep . 'code=' . urlencode($code) . (!empty($oauthState) ? '&state=' . urlencode($oauthState) : '');
                    header('Location: ' . $redirect); exit;
                }
                $redirect = SticPortalConfigUtils::get('PORTAL_HOME_URL', 'index.php?entryPoint=sticPortalLogin');
                if ($auth['must_change_password']) $redirect = 'index.php?entryPoint=sticPortalChangePassword';
                header('Location: ' . $redirect); exit;
            }
            $error = ($auth['error_code'] === 'locked' || $auth['error_code'] === 'ip_locked') ? $auth['error'] : 'Invalid credentials.';
        }
    }
}

if (empty($_SESSION['portal_csrf_token'])) $_SESSION['portal_csrf_token'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['portal_csrf_token'];

if (isset($_GET['error']) && $_GET['error'] === 'invalid_link') $error = 'Invalid or expired link. Please request a new one.';
if (isset($_GET['error']) && $_GET['error'] === 'session_expired') $error = 'Session expired. Please log in again.';

$ss = new Sugar_Smarty();
$ss->assign('TITLE',       SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal'));
$ss->assign('LOGO_URL',    SticPortalConfigUtils::getLogoUrl());
$ss->assign('LOGO_WIDTH',  SticPortalConfigUtils::get('PORTAL_LOGO_WIDTH', '212'));
$ss->assign('MAGIC_ENABLED', SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_ENABLED', '0'));
$ss->assign('ERROR',   $error);
$ss->assign('MESSAGE', $message);
$ss->assign('MODE',    $mode);
$ss->assign('CSRF_TOKEN', $csrfToken);
$ss->assign('OAUTH_CLIENT_ID', $oauthClientId);
$ss->assign('OAUTH_REDIRECT_URI', $oauthRedirectUri);
$ss->assign('OAUTH_STATE', $oauthState);
$ss->assign('IS_OAUTH', $isOAuth);
$ss->display('themes/SuiteP/tpls/portal_login.tpl');
