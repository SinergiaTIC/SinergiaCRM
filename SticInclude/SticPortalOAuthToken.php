<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

header('Content-Type: application/json');
require_once 'SticInclude/SticPortalOAuthUtils.php';
require_once 'SticInclude/SticPortalAuthUtils.php';

$GLOBALS['log']->debug("PortalOAuthToken - " . $_SERVER['REQUEST_METHOD']);
global $db;

// GET: validate access token and return portal identity
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['access_token'] ?? '';
    $result = $db->limitQuery("SELECT portal_type, assigned_user_id FROM oauth2tokens WHERE access_token=" . $db->quoted($token) . " AND deleted=0 AND token_is_revoked=0", 0, 1);
    $row = $db->fetchByAssoc($result);
    if (!$row || empty($row['portal_type'])) {
        http_response_code(401);
        echo json_encode(['error' => 'invalid_token']);
        exit;
    }
    // Parse portal:Contact:uuid or portal:Account:uuid from assigned_user_id
    // Actually, portal_type is stored separately in the column we added
    echo json_encode(['portal_type' => $row['portal_type'], 'portal_id' => $row['assigned_user_id']]);
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
    $atExp = date('Y-m-d H:i:s', time() + 3600);
    $rtExp = date('Y-m-d H:i:s', time() + 2592000);
    $at = bin2hex(random_bytes(32));
    $rt = bin2hex(random_bytes(32));

    $type = $row['portal_type'];
    $portalId = $row['portal_id'];

    $db->query("INSERT INTO oauth2tokens (id, access_token, refresh_token, access_token_expires, refresh_token_expires, client, assigned_user_id, portal_type, token_is_revoked, token_type, grant_type, date_entered, date_modified, deleted) VALUES ("
        . $db->quoted(create_guid()) . ", "
        . $db->quoted($at) . ", "
        . $db->quoted($rt) . ", "
        . $db->quoted($atExp) . ", "
        . $db->quoted($rtExp) . ", "
        . $db->quoted($clientId) . ", "
        . $db->quoted($portalId) . ", "
        . $db->quoted($type) . ", "
        . "0, 'Bearer', 'portal_password', "
        . $db->quoted($now) . ", "
        . $db->quoted($now) . ", 0)");

    echo json_encode(['access_token' => $at, 'token_type' => 'Bearer', 'expires_in' => 3600, 'refresh_token' => $rt]);
    exit;
}

if ($grantType === 'refresh_token') {
    if (empty($refreshToken) || empty($clientId)) { http_response_code(400); echo json_encode(['error' => 'invalid_request']); exit; }
    $client = SticPortalOAuthUtils::validateClient($clientId, '');
    if (!$client) { http_response_code(400); echo json_encode(['error' => 'invalid_client']); exit; }

    $result = $db->limitQuery("SELECT * FROM oauth2tokens WHERE refresh_token=" . $db->quoted($refreshToken) . " AND client=" . $db->quoted($clientId) . " AND deleted=0 AND token_is_revoked=0", 0, 1);
    $row = $db->fetchByAssoc($result);
    if (!$row) { http_response_code(400); echo json_encode(['error' => 'invalid_grant']); exit; }

    $db->query("UPDATE oauth2tokens SET token_is_revoked=1 WHERE id=" . $db->quoted($row['id']));

    $now = date('Y-m-d H:i:s');
    $atExp = date('Y-m-d H:i:s', time() + 3600);
    $rtExp = date('Y-m-d H:i:s', time() + 2592000);
    $at = bin2hex(random_bytes(32));
    $rt = bin2hex(random_bytes(32));

    $db->query("INSERT INTO oauth2tokens (id, access_token, refresh_token, access_token_expires, refresh_token_expires, client, assigned_user_id, portal_type, token_is_revoked, token_type, grant_type, date_entered, date_modified, deleted) VALUES ("
        . $db->quoted(create_guid()) . ", "
        . $db->quoted($at) . ", "
        . $db->quoted($rt) . ", "
        . $db->quoted($atExp) . ", "
        . $db->quoted($rtExp) . ", "
        . $db->quoted($clientId) . ", "
        . $db->quoted($row['assigned_user_id']) . ", "
        . $db->quoted($row['portal_type']) . ", "
        . "0, 'Bearer', 'portal_password', "
        . $db->quoted($now) . ", "
        . $db->quoted($now) . ", 0)");

    echo json_encode(['access_token' => $at, 'token_type' => 'Bearer', 'expires_in' => 3600, 'refresh_token' => $rt]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'unsupported_grant_type']);
