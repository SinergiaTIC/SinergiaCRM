<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $result = SticPortalAuthUtils::getPortalUserByUsername($username);
    if ($result) {
        SticPortalAuthUtils::generateResetToken($result['bean']);
        SticPortalAuthUtils::sendSecurityNotification($result['bean'], 'reset_requested');
    }
    $message = 'If the account exists, a reset link has been sent to your email.';
}

$ss = new Sugar_Smarty();
$ss->assign('TITLE', 'Reset Password');
$ss->assign('ERROR', $error);
$ss->assign('MESSAGE', $message);
$ss->display('themes/SuiteP/tpls/portal_reset.tpl');
