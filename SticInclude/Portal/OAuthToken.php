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
    $token = $_GET['access_token'] ?? '';
    $result = $db->limitQuery("SELECT id, assigned_user_id FROM oauth2tokens WHERE access_token=" . $db->quoted($token) . " AND deleted=0 AND token_is_revoked=0", 0, 1);
    $row = $db->fetchByAssoc($result);
    if (!$row) { http_response_code(401); echo json_encode(['error' => 'invalid_token']); exit; }
    echo json_encode(['valid' => true, 'portal_id' => $row['assigned_user_id']]);
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

    $result = $db->limitQuery("SELECT * FROM stic_portal_auth_codes WHERE auth_code=" . $db->quoted($code) . " AND deleted=0 AND is_revoked=0", 0, 1);
    $row = $db->fetchByAssoc($result);
    if (!$row || $row['client_id'] !== $clientId) { http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit; }

    $db->query("UPDATE stic_portal_auth_codes SET is_revoked=1 WHERE id=" . $db->quoted($row['id']));

    $now = date('Y-m-d H:i:s');
    $atExp = date('Y-m-d H:i:s', time() + 3600); $rtExp = date('Y-m-d H:i:s', time() + 2592000);
    $at = bin2hex(random_bytes(32)); $rt = bin2hex(random_bytes(32));
    $portalId = $row['portal_id'];

    $db->query("INSERT INTO oauth2tokens (id, access_token, access_token_expires, refresh_token, refresh_token_expires, token_type, token_is_revoked, date_entered, date_modified, deleted) VALUES ("
        . $db->quoted(create_guid()) . ", " . $db->quoted($at) . ", " . $db->quoted($atExp) . ", " . $db->quoted($rt) . ", "
        . $db->quoted($rtExp) . ", " . $db->quoted("Bearer") . ", 0, " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");

    // Include relationships directly in the response
    // Include full Contact info
    $contact = $db->fetchByAssoc($db->limitQuery("SELECT c.id, c.first_name, c.last_name, c.title, c.department, c.phone_mobile, c.phone_work, c.phone_home, c.description, cc.stic_portal_username_c, cc.stic_portal_enabled_c, cc.stic_portal_last_login_c, cc.stic_portal_failed_attempts_c, cc.stic_portal_locked_until_c FROM contacts c JOIN contacts_cstm cc ON cc.id_c = c.id WHERE c.id=" . $db->quoted($portalId), 0, 1));
    // All relationships (active + ended)
    $rels = [];
    $rr = $db->query("SELECT sr.id, sr.name, sr.relationship_type, sr.start_date, sr.end_date, sr.role, p.name AS project_name FROM stic_contacts_relationships sr JOIN stic_contacts_relationships_contacts_c lnk ON lnk.stic_contacts_relationships_contactscontacts_ida = sr.id LEFT JOIN stic_contacts_relationships_project_c prj ON prj.stic_conta0d5aonships_idb = sr.id AND prj.deleted = 0 LEFT JOIN project p ON p.id = prj.stic_contacts_relationships_projectproject_ida AND p.deleted = 0 WHERE lnk.stic_contae394onships_idb = " . $db->quoted($portalId) . " AND sr.deleted = 0 AND lnk.deleted = 0 ORDER BY sr.start_date DESC");
    while ($rrow = $db->fetchByAssoc($rr)) { $rels[] = $rrow; }

    echo json_encode(['access_token' => $at, 'token_type' => 'Bearer', 'expires_in' => 3600, 'refresh_token' => $rt, 'portal_id' => $portalId, 'contact' => $contact, 'relationships' => $rels, 'relationship_count' => count($rels)]);
    exit;
}

if ($grantType === 'refresh_token') {
    if (empty($refreshToken) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, '');
    if (!$client) { http_response_code(400); echo json_encode(['error' => 'invalid_client']); exit; }

    $result = $db->limitQuery("SELECT * FROM oauth2tokens WHERE refresh_token=" . $db->quoted($refreshToken) . " AND deleted=0 AND token_is_revoked=0", 0, 1);
    $row = $db->fetchByAssoc($result);
    if (!$row) { http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit; }

    $db->query("UPDATE oauth2tokens SET token_is_revoked=1 WHERE id=" . $db->quoted($row['id']));

    $now = date('Y-m-d H:i:s');
    $atExp = date('Y-m-d H:i:s', time() + 3600); $rtExp = date('Y-m-d H:i:s', time() + 2592000);
    $at = bin2hex(random_bytes(32)); $rt = bin2hex(random_bytes(32));

    $db->query("INSERT INTO oauth2tokens (id, access_token, access_token_expires, refresh_token, refresh_token_expires, token_type, token_is_revoked, date_entered, date_modified, deleted) VALUES ("
        . $db->quoted(create_guid()) . ", " . $db->quoted($at) . ", " . $db->quoted($atExp) . ", " . $db->quoted($rt) . ", "
        . $db->quoted($rtExp) . ", " . $db->quoted("Bearer") . ", 0, " . $db->quoted($now) . ", " . $db->quoted($now) . ", 0)");

    echo json_encode(["access_token" => $at, "token_type" => "Bearer", "expires_in" => 3600, "refresh_token" => $rt]);
    exit;
}

http_response_code(400); echo json_encode(['error' => 'unsupported_grant_type']);
