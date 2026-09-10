<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
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
    if (empty($_POST['csrf_token']) || empty($_SESSION['portal_csrf_token']) || $_POST['csrf_token'] !== $_SESSION['portal_csrf_token']) {
        $error = 'Invalid request. Please try again.';
    } else {
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
}

if (empty($_SESSION['portal_csrf_token'])) $_SESSION['portal_csrf_token'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['portal_csrf_token'];

$ss = new Sugar_Smarty();
$ss->assign('ERROR', $error);
$ss->assign('SUCCESS', $success);
$ss->assign('CSRF_TOKEN', $csrfToken);
$ss->assign('PW_MIN_LENGTH', SticPortalConfigUtils::get('PORTAL_PASSWORD_MIN_LENGTH', '8'));
$ss->assign('PW_REQUIRE_UPPER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_UPPER', '0'));
$ss->assign('PW_REQUIRE_LOWER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_LOWER', '0'));
$ss->assign('PW_REQUIRE_NUMBER', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_NUMBER', '0'));
$ss->assign('PW_REQUIRE_SPECIAL', SticPortalConfigUtils::get('PORTAL_PASSWORD_REQUIRE_SPECIAL', '0'));
$ss->assign('HOME_URL', SticPortalConfigUtils::get('PORTAL_HOME_URL', ''));
$ss->display('custom/themes/SuiteP/tpls/SticPortalChangePassword.tpl');
