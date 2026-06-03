<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'SticInclude/SticPortalAuthUtils.php';
require_once 'SticInclude/SticPortalConfigUtils.php';

$token = $_GET['token'] ?? '';
$id    = $_GET['id'] ?? '';
$result = SticPortalAuthUtils::validateMagicLinkToken($token, $id);

if (!$result) {
    header('Location: index.php?entryPoint=sticPortalLogin&error=invalid_link');
    exit;
}

$bean = $result['bean'];
$type = $result['type'];

// Check lockout
$lockout = SticPortalAuthUtils::checkLockout($bean);
if ($lockout['locked']) {
    header('Location: index.php?entryPoint=sticPortalLogin&error=invalid_link');
    exit;
}

// Check if disabled
if (!$bean->stic_portal_enabled_c) {
    header('Location: index.php?entryPoint=sticPortalLogin&error=invalid_link');
    exit;
}

// Success: create session
SticPortalAuthUtils::resetFailedAttempts($bean);
SticPortalAuthUtils::recordLoginAudit($bean, $type, $bean->stic_portal_username_c, $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'magic_link');
SticPortalAuthUtils::sendSecurityNotification($bean, 'new_login');
SticPortalAuthUtils::createPortalSession($bean, $type);

$home = SticPortalConfigUtils::get('PORTAL_HOME_URL', '');
if ($bean->stic_portal_force_pw_change_c || SticPortalAuthUtils::isPasswordExpired($bean)) {
    header('Location: index.php?entryPoint=sticPortalChangePassword');
    exit;
}
if (!empty($home)) {
    header('Location: ' . $home);
    exit;
}
header('Content-Type: text/html');
echo '<h1>Welcome!</h1><p>You are logged in as ' . htmlspecialchars($bean->name ?? $bean->stic_portal_username_c) . '.</p><p><a href="index.php?entryPoint=sticPortalLogout">Logout</a></p>';
exit;
