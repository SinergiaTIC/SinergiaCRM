<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';
require_once 'SticInclude/SticPortalConfigUtils.php';
require_once 'SticInclude/SticPortalOAuthRepository.php';

session_start();
$message = ''; $error = ''; $mode = 'password';
$oauthClientId = $_GET['client_id'] ?? $_POST['client_id'] ?? '';
$oauthRedirectUri = $_GET['redirect_uri'] ?? $_POST['redirect_uri'] ?? '';
$oauthState = $_GET['state'] ?? $_POST['state'] ?? '';
$isOAuth = !empty($oauthClientId) && !empty($oauthRedirectUri);

if ($isOAuth) {
    $oauthClient = \BeanFactory::getBean('OAuth2Clients', $oauthClientId);
    if (!$oauthClient || !$oauthClient->id || $oauthClient->deleted == 1 || (strpos($oauthRedirectUri, $oauthClient->redirect_url ?? '') !== 0)) {
        $error = 'Invalid OAuth client or redirect URI.'; $isOAuth = false;
    }
}

// Honeypot: hidden field that bots auto-fill. Humans can't see it.
// Note: Bot UA blacklist intentionally NOT implemented — OAuth authorization
// endpoints must accept all legitimate clients regardless of User-Agent.
// Brute-force is handled by per-credential lockout, IP lockout, and CSRF.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['portal_hp'])) {
        $error = 'Invalid credentials.';
    } elseif (empty($_POST['csrf_token']) || empty($_SESSION['portal_csrf_token']) || $_POST['csrf_token'] !== $_SESSION['portal_csrf_token']) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = $_POST['username'] ?? '';
        $mode = $_POST['portal_mode'] ?? 'password';
        if ($mode === 'magic_link' && SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_ENABLED','0') === '1') {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            if (SticPortalAuthUtils::isMagicLinkRateLimited($username,'username') || SticPortalAuthUtils::isMagicLinkRateLimited($ip,'ip')) {
                $message = 'If the account exists, a magic link has been sent to your email.';
            } else {
                $result = SticPortalAuthUtils::getPortalUserByUsername($username);
                if ($result) SticPortalAuthUtils::generateMagicLinkToken($result['bean']);
                $message = 'If the account exists, a magic link has been sent to your email.';
            }
        } else {
            $password = $_POST['password'] ?? '';
            $remember = !empty($_POST['remember']);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $auth = SticPortalAuthUtils::authenticate($username, $password, $remember, $ip);
            if ($auth['success']) {
                if ($isOAuth) SticPortalAuthCodeGenerator::generateAndRedirect($auth['bean']->id, $auth['type'], $oauthClientId, $oauthRedirectUri, $oauthState);
                $redirect = SticPortalConfigUtils::get('PORTAL_HOME_URL', 'index.php?entryPoint=sticPortalLogin');
                if ($auth['must_change_password']) $redirect = 'index.php?entryPoint=sticPortalChangePassword';
                header('Location: ' . $redirect); exit;
            }
            $error = ($auth['error_code'] === 'locked' || $auth['error_code'] === 'ip_locked')
                ? 'Too many failed attempts. If the account exists and exceeded the limit, an email has been sent with instructions.'
                : 'Invalid credentials.';
        }
    }
}

if (empty($_SESSION['portal_csrf_token'])) $_SESSION['portal_csrf_token'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['portal_csrf_token'];
if (isset($_GET['error']) && $_GET['error'] === 'invalid_link') $error = 'Invalid or expired link. Please request a new one.';
if (isset($_GET['error']) && $_GET['error'] === 'session_expired') $error = 'Session expired. Please log in again.';

$ss = new Sugar_Smarty();
$ss->assign('TITLE', SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal'));
$ss->assign('LOGO_URL', SticPortalConfigUtils::getLogoUrl());
$ss->assign('LOGO_WIDTH', SticPortalConfigUtils::get('PORTAL_LOGO_WIDTH', '212'));
$ss->assign('MAGIC_ENABLED', SticPortalConfigUtils::get('PORTAL_MAGIC_LINK_ENABLED', '0'));
$ss->assign('ERROR', $error); $ss->assign('MESSAGE', $message);
$ss->assign('MODE', $mode); $ss->assign('CSRF_TOKEN', $csrfToken);
$ss->assign('OAUTH_CLIENT_ID', $oauthClientId); $ss->assign('OAUTH_REDIRECT_URI', $oauthRedirectUri);
$ss->assign('OAUTH_STATE', $oauthState); $ss->assign('IS_OAUTH', $isOAuth);
$ss->display('custom/themes/SuiteP/tpls/SticPortalLogin.tpl');
