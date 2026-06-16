<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/Portal/AuthUtils.php';
require_once 'SticInclude/Portal/ConfigUtils.php';

session_start();
$bean = SticPortalAuthUtils::validatePortalSession();

if (!$bean) {
    header('Location: index.php?entryPoint=sticPortalLogin&error=session_expired');
    exit;
}

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $changeResult = SticPortalAuthUtils::changePassword($bean, $current, $new, $confirm);
    if ($changeResult['success']) {
        $success = true;
    } else {
        $error = $changeResult['error'];
    }
}

$ss = new Sugar_Smarty();
$ss->assign('ERROR', $error);
$ss->assign('SUCCESS', $success);
$ss->assign('PW_MIN_LENGTH', SticPortalConfigUtils::get('PORTAL_PASSWORD_MIN_LENGTH', '8'));
$ss->assign('PW_REQUIRE_UPPER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_UPPER', '0'));
$ss->assign('PW_REQUIRE_LOWER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_LOWER', '0'));
$ss->assign('PW_REQUIRE_NUMBER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_NUMBER', '0'));
$ss->assign('PW_REQUIRE_SPECIAL', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_SPECIAL', '0'));
$ss->assign('HOME_URL', SticPortalConfigUtils::get('PORTAL_HOME_URL', ''));
$ss->display('custom/themes/SuiteP/tpls/SticPortalChangePassword.tpl');
