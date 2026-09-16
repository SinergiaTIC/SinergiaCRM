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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'SticInclude/Portal/AuthUtils.php';
require_once 'SticInclude/Portal/ConfigUtils.php';

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

// IP lockout applies to magic-link auto-login too (brute-force resilience)
if (SticPortalAuthUtils::isIpLocked($_SERVER['REMOTE_ADDR'] ?? '')) {
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
