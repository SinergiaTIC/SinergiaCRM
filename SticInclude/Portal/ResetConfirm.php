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
            // Set hashed_c directly with plaintext — processBeforeSave validates policy, checks history, and hashes
            $bean->stic_portal_hashed_c = $newPassword;
            $bean->stic_portal_force_pw_change_c = 0;
            $bean->stic_portal_reset_token_c = '';
            $bean->stic_portal_reset_expires_c = '';
            $bean->stic_portal_failed_attempts_c = 0;
            $bean->stic_portal_locked_until_c = '';
            $GLOBALS['log']->debug("ResetConfirm: about to save (single) with hashed_c=" . substr($newPassword, 0, 20) . "...");
            $bean->save();
            $GLOBALS['log']->info("ResetConfirm: save done, hashed=" . substr($bean->stic_portal_hashed_c ?? '', 0, 40));
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
