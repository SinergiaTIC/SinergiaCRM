<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'SticInclude/Portal/AuthUtils.php';

session_start();
if (!empty($_SESSION['portal_user_id'])) {
    SticPortalAuthUtils::destroyPortalSession();
}
session_write_close();
if (session_status() === PHP_SESSION_ACTIVE) session_destroy();

header('Location: index.php?entryPoint=sticPortalLogin');
exit;
