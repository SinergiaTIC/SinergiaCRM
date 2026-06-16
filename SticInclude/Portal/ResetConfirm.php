<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/Portal/AuthUtils.php';

$token       = $_GET['token'] ?? '';
$id          = $_GET['id'] ?? '';
$redirectUri = $_REQUEST['redirect_uri'] ?? '';
$result      = SticPortalAuthUtils::validateResetToken($token, $id);

if (!$result) {
    $loc = 'index.php?entryPoint=sticPortalReset&error=invalid';
    if ($redirectUri) $loc .= '&redirect_uri=' . urlencode($redirectUri);
    header('Location: ' . $loc);
    exit;
}

$bean    = $result['bean'];
$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirectUri = $_POST['redirect_uri'] ?? $redirectUri;
    $newPassword = $_POST['new_password'] ?? '';
    $confirm     = $_POST['confirm_password'] ?? '';
    if ($newPassword !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        $violations = SticPortalAuthUtils::validatePasswordPolicy($newPassword);
        if (!empty($violations)) {
            $error = 'Password policy: ' . implode('; ', $violations);
        } else {
            $newHash = SticPortalAuthUtils::hashPassword($newPassword);
            SticPortalAuthUtils::archivePasswordHistory($bean, $bean->stic_portal_hashed_c);
            $bean->stic_portal_hashed_c = $newHash;
            $bean->stic_portal_password_changed_c = gmdate('Y-m-d H:i:s');
            $bean->stic_portal_force_pw_change_c = 0;
            SticPortalAuthUtils::setPasswordExpiration($bean);
            SticPortalAuthUtils::clearResetToken($bean);
            SticPortalAuthUtils::resetFailedAttempts($bean);
            $bean->save();
            SticPortalAuthUtils::sendSecurityNotification($bean, 'password_changed');
            $success = true;
        }
    }
}

$ss = new Sugar_Smarty();
$ss->assign('ERROR', $error);
$ss->assign('SUCCESS', $success);
$ss->assign('REDIRECT_URI', $redirectUri);
$ss->assign('PW_MIN_LENGTH', SticPortalConfigUtils::get('PORTAL_PASSWORD_MIN_LENGTH', '8'));
$ss->assign('PW_REQUIRE_UPPER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_UPPER', '0'));
$ss->assign('PW_REQUIRE_LOWER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_LOWER', '0'));
$ss->assign('PW_REQUIRE_NUMBER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_NUMBER', '0'));
$ss->assign('PW_REQUIRE_SPECIAL', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_SPECIAL', '0'));
$ss->display('custom/themes/SuiteP/tpls/SticPortalResetConfirm.tpl');
