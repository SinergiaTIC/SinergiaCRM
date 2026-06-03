<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';

$token  = $_GET['token'] ?? '';
$id     = $_GET['id'] ?? '';
$result = SticPortalAuthUtils::validateResetToken($token, $id);

if (!$result) {
    header('Location: index.php?entryPoint=sticPortalReset&error=invalid');
    exit;
}

$bean    = $result['bean'];
$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
$ss->display('themes/SuiteP/tpls/portal_reset_confirm.tpl');
