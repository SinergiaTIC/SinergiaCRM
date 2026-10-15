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

header('Content-Type: application/json');
require_once 'SticInclude/Portal/OAuthUtils.php';
require_once 'SticInclude/Portal/AuthUtils.php';

$GLOBALS['log']->debug("PortalOAuthToken - " . $_SERVER['REQUEST_METHOD']);
global $db;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tokenVal = $_GET['access_token'] ?? '';
    $t = BeanFactory::newBean('OAuth2Tokens');
    $t->retrieve_by_string_fields(['access_token' => $tokenVal]);
    if (!$t->id || $t->token_is_revoked == '1') { http_response_code(401); echo json_encode(['error' => 'invalid_token']); exit; }
    if (!empty($t->access_token_expires) && strtotime($t->access_token_expires) < time()) {
        http_response_code(401); echo json_encode(['error' => 'token_expired']); exit;
    }
    // Portal info is stored in the description field as "Contact|{id}" or "Account|{id}"
    $descParts = explode('|', $t->description ?? '');
    $portalType = $descParts[0] ?? '';
    $portalModule = '';
    if ($portalType === 'Contact') {
        $portalModule = 'Contacts';
    } elseif ($portalType === 'Account') {
        $portalModule = 'Accounts';
    }
    echo json_encode([
        'valid' => true,
        'portal_type' => $portalModule,
        'portal_id' => $descParts[1] ?? '',
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error' => 'method_not_allowed']); exit; }

$grantType    = $_POST['grant_type'] ?? '';
$code         = $_POST['code'] ?? '';
$clientId     = $_POST['client_id'] ?? '';
$clientSecret = $_POST['client_secret'] ?? '';
$redirectUri  = $_POST['redirect_uri'] ?? '';
$refreshToken = $_POST['refresh_token'] ?? '';

if ($grantType === 'authorization_code') {
    if (empty($code) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, $redirectUri, $clientSecret);
    if (!$client) { http_response_code(400); echo json_encode(['error' => 'invalid_client']); exit; }

    // Retrieve auth code via Bean
    $authCode = BeanFactory::newBean('OAuth2Tokens');
    $authCode->retrieve_by_string_fields(['access_token' => $code]);
    if (!$authCode->id || $authCode->token_type !== 'auth_code' || $authCode->token_is_revoked == '1'
        || strtotime($authCode->access_token_expires) < time() || $authCode->client !== $clientId) {
        http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit;
    }

    // Revoke the auth code
    $authCode->token_is_revoked = 1;
    $authCode->save();

    // Parse portal info from description: "Contact|00000a55..." or "Account|00000b66..."
    $descParts = explode('|', $authCode->description ?? '');
    $portalType = $descParts[0] ?? '';
    $portalId   = $descParts[1] ?? '';

    // Issue new tokens via Bean
    $at = bin2hex(random_bytes(32));
    $rt = bin2hex(random_bytes(32));
    $atExp = date('Y-m-d H:i:s', time() + 3600);
    $rtExp = date('Y-m-d H:i:s', time() + 2592000);

    $token = BeanFactory::newBean('OAuth2Tokens');
    $token->id = create_guid();
    $token->new_with_id = true;
    $token->access_token = $at;
    $token->access_token_expires = $atExp;
    $token->refresh_token = $rt;
    $token->refresh_token_expires = $rtExp;
    $token->token_type = 'Bearer';
    $token->token_is_revoked = 0;
    $token->description = $authCode->description;
    $token->client = $clientId;
    $token->save();

    $emailBeanModule = '';
    if ($portalType === 'Contact') {
        $emailBeanModule = 'Contacts';
    } elseif ($portalType === 'Account') {
        $emailBeanModule = 'Accounts';
    }

    $user = null;
    if ($emailBeanModule !== '') {
        $er = $db->limitQuery(
            "SELECT ea.email_address FROM email_addr_bean_rel eabr"
            . " JOIN email_addresses ea ON ea.id = eabr.email_address_id"
            . " WHERE eabr.bean_id=" . $db->quoted($portalId)
            . " AND eabr.bean_module=" . $db->quoted($emailBeanModule)
            . " AND eabr.primary_address=1 AND eabr.deleted=0 AND ea.deleted=0", 0, 1);
        $emailRow = $db->fetchByAssoc($er);
        $user = [
            'id' => $portalId,
            'email' => $emailRow['email_address'] ?? '',
        ];
    }

    echo json_encode([
        'access_token' => $at,
        'token_type' => 'Bearer',
        'expires_in' => 3600,
        'refresh_token' => $rt,
        'portal_id' => $portalId,
        'portal_type' => $emailBeanModule,
        'user' => $user,
    ]);
    exit;
}

if ($grantType === 'refresh_token') {
    if (empty($refreshToken) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, '', $clientSecret);
    if (!$client) { http_response_code(400); echo json_encode(['error' => 'invalid_client']); exit; }

    // Retrieve and revoke old token via Bean
    $old = BeanFactory::newBean('OAuth2Tokens');
    $old->retrieve_by_string_fields(['refresh_token' => $refreshToken]);
    // The refresh token must belong to the requesting client (stolen-token / cross-client protection)
    if (!$old->id || $old->token_is_revoked == '1' || $old->client !== $clientId) {
        http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit;
    }
    $old->token_is_revoked = 1;
    $old->save();

    $at = bin2hex(random_bytes(32));
    $rt = bin2hex(random_bytes(32));
    $atExp = date('Y-m-d H:i:s', time() + 3600);
    $rtExp = date('Y-m-d H:i:s', time() + 2592000);

    $token = BeanFactory::newBean('OAuth2Tokens');
    $token->id = create_guid();
    $token->new_with_id = true;
    $token->access_token = $at;
    $token->access_token_expires = $atExp;
    $token->refresh_token = $rt;
    $token->refresh_token_expires = $rtExp;
    $token->token_type = 'Bearer';
    $token->token_is_revoked = 0;
    $token->description = $old->description;
    $token->client = $old->client;
    $token->save();

    echo json_encode(["access_token" => $at, "token_type" => "Bearer", "expires_in" => 3600, "refresh_token" => $rt]);
    exit;
}

http_response_code(400); echo json_encode(['error' => 'unsupported_grant_type']);
