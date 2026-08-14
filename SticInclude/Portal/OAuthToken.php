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
    echo json_encode([
        'valid' => true,
        'portal_type' => $descParts[0] ?? '',
        'portal_id' => $descParts[1] ?? '',
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error' => 'method_not_allowed']); exit; }

$grantType    = $_POST['grant_type'] ?? '';
$code         = $_POST['code'] ?? '';
$clientId     = $_POST['client_id'] ?? '';
$redirectUri  = $_POST['redirect_uri'] ?? '';
$refreshToken = $_POST['refresh_token'] ?? '';

if ($grantType === 'authorization_code') {
    if (empty($code) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, $redirectUri);
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

    $user = null;
    $rels = [];

    if ($portalType === 'Contact') {
        $user = $db->fetchByAssoc($db->limitQuery(
            "SELECT c.id, c.first_name, c.last_name, c.phone_mobile, c.birthdate,"
            . " c.primary_address_postalcode, c.primary_address_country, c.primary_address_state, c.primary_address_city,"
            . " cc.stic_identification_number_c, cc.stic_identification_type_c,"
            . " cc.stic_language_c, cc.stic_gender_c, cc.stic_age_c"
            . " FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id"
            . " WHERE c.id=" . $db->quoted($portalId), 0, 1));

        if ($user) {
            // Fetch primary email
            $er = $db->limitQuery(
                "SELECT ea.email_address FROM email_addr_bean_rel eabr"
                . " JOIN email_addresses ea ON ea.id = eabr.email_address_id"
                . " WHERE eabr.bean_id=" . $db->quoted($portalId)
                . " AND eabr.bean_module='Contacts' AND eabr.primary_address=1"
                . " AND eabr.deleted=0 AND ea.deleted=0", 0, 1);
            $emailRow = $db->fetchByAssoc($er);
            $user['email'] = $emailRow['email_address'] ?? '';

            // All relationships (active + ended)
            $rr = $db->query(
                "SELECT sr.id, sr.name, sr.relationship_type, sr.start_date, sr.end_date, sr.role,"
                . " p.name AS project_name, p.estimated_start_date, p.estimated_end_date,"
                . " sr.stic_portal_decidim_excluded_c"
                . " FROM stic_contacts_relationships sr"
                . " JOIN stic_contacts_relationships_contacts_c lnk ON lnk.stic_contacts_relationships_contactscontacts_ida = sr.id"
                . " LEFT JOIN stic_contacts_relationships_project_c prj ON prj.stic_conta0d5aonships_idb = sr.id AND prj.deleted = 0"
                . " LEFT JOIN project p ON p.id = prj.stic_contacts_relationships_projectproject_ida AND p.deleted = 0"
                . " WHERE lnk.stic_contae394onships_idb = " . $db->quoted($portalId)
                . " AND sr.deleted = 0 AND lnk.deleted = 0 ORDER BY sr.start_date DESC");
            while ($rrow = $db->fetchByAssoc($rr)) { $rels[] = $rrow; }
        }
    } elseif ($portalType === 'Account') {
        $user = $db->fetchByAssoc($db->limitQuery(
            "SELECT a.id, a.name, a.phone_office, a.phone_alternate, a.website,"
            . " a.billing_address_postalcode, a.billing_address_country, a.billing_address_state, a.billing_address_city,"
            . " a.description, a.account_type, a.industry,"
            . " ac.stic_identification_number_c, ac.stic_identification_type_c, ac.stic_language_c"
            . " FROM accounts a JOIN accounts_cstm ac ON ac.id_c = a.id"
            . " WHERE a.id=" . $db->quoted($portalId), 0, 1));

        if ($user) {
            $er = $db->limitQuery(
                "SELECT ea.email_address FROM email_addr_bean_rel eabr"
                . " JOIN email_addresses ea ON ea.id = eabr.email_address_id"
                . " WHERE eabr.bean_id=" . $db->quoted($portalId)
                . " AND eabr.bean_module='Accounts' AND eabr.primary_address=1"
                . " AND eabr.deleted=0 AND ea.deleted=0", 0, 1);
            $emailRow = $db->fetchByAssoc($er);
            $user['email'] = $emailRow['email_address'] ?? '';
        }
    }

    echo json_encode([
        'access_token' => $at,
        'token_type' => 'Bearer',
        'expires_in' => 3600,
        'refresh_token' => $rt,
        'portal_id' => $portalId,
        'portal_type' => $portalType,
        'user' => $user,
        'relationships' => $rels,
        'relationship_count' => count($rels),
    ]);
    exit;
}

if ($grantType === 'refresh_token') {
    if (empty($refreshToken) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, '');
    if (!$client) { http_response_code(400); echo json_encode(['error' => 'invalid_client']); exit; }

    // Retrieve and revoke old token via Bean
    $old = BeanFactory::newBean('OAuth2Tokens');
    $old->retrieve_by_string_fields(['refresh_token' => $refreshToken]);
    if (!$old->id || $old->token_is_revoked == '1') { http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit; }
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
