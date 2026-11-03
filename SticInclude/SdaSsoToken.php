<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
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

require_once 'SticInclude/SdaSso.php';

/**
 * stic_sda_sso_token entry point.
 *
 * Returns a short-lived SSO JWT for the logged-in user so the CRM page
 * embedding SinergiaDA can hand it over (postMessage handshake) or use it
 * in a redirect. Session authentication is enforced by the registry ("auth" => true).
 */

/**
 * Emit a JSON response and stop.
 *
 * @param int   $status HTTP status code.
 * @param array $payload Response body.
 * @return void
 */
function sticSdaSsoJsonResponse($status, array $payload)
{
    @ob_end_clean();
    while (ob_get_level() > 0) {
        @ob_clean();
        @ob_end_clean();
    }
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Referrer-Policy: no-referrer');
    header('Cache-Control: no-store');
    echo json_encode($payload);
    exit;
}

// CSRF guard: when the browser sends an Origin header it must match this site.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$allowedOrigins = [$scheme . '://' . $_SERVER['HTTP_HOST']];
$siteUrl = $GLOBALS['sugar_config']['site_url'] ?? '';
if (!empty($siteUrl)) {
    $allowedOrigins[] = rtrim($siteUrl, '/');
}
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && !in_array($origin, $allowedOrigins, true)) {
    $GLOBALS['log']->warn(
        'Line ' . __LINE__ . ': ' . __METHOD__ . ': Rejected SDA SSO token request from origin ' . $origin
    );
    sticSdaSsoJsonResponse(403, ['error' => 'origin_not_allowed']);
}

global $current_user;
if (empty($current_user) || empty($current_user->id) || empty($current_user->user_name)) {
    sticSdaSsoJsonResponse(401, ['error' => 'not_authenticated']);
}

if (!SticSdaSso::isReady()) {
    sticSdaSsoJsonResponse(503, ['error' => 'sso_not_configured']);
}

$allowed = SticSdaSso::getAllowed($current_user->id);
if ($allowed === false) {
    $GLOBALS['log']->warn(
        'Line ' . __LINE__ . ': ' . __METHOD__ . ': Denied SDA SSO token for user [' .
        $current_user->user_name . ']: not allowed in SinergiaDA'
    );
    sticSdaSsoJsonResponse(403, ['error' => 'not_allowed']);
}

$token = SticSdaSso::mint($current_user->user_name, $allowed);
if ($token === null) {
    sticSdaSsoJsonResponse(500, ['error' => 'token_error']);
}

$GLOBALS['log']->info(
    'Line ' . __LINE__ . ': ' . __METHOD__ . ': Issued SDA SSO token for user [' .
    $current_user->user_name . ']'
);

sticSdaSsoJsonResponse(200, ['token' => $token, 'expires_in' => SticSdaSso::TTL]);
