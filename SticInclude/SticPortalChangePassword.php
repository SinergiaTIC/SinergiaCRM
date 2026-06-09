<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/SticPortalAuthUtils.php';
require_once 'SticInclude/SticPortalConfigUtils.php';

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
$ss->assign('HOME_URL', SticPortalConfigUtils::get('PORTAL_HOME_URL', ''));
$ss->display('custom/themes/SuiteP/tpls/SticPortalchange_password.tpl');
