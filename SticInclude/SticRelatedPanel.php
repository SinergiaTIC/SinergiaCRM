<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2026 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Entry point handler of the right-hand "related records" panel.
//
//   GET  index.php?entryPoint=sticRelatedPanel&panel_action=status&module=<Module>
//        -> {enabled, pinned, hide_empty, user_prefs, hidden_relations}
//        (cheap, called on every detail view load)
//
//   GET  index.php?entryPoint=sticRelatedPanel&module=<Module>&record=<id>
//        -> full payload with a flat "timeline" and the filterable "relations"
//
//   POST index.php?entryPoint=sticRelatedPanel&panel_action=saveFilter&module=<Module>
//        raw JSON body: {"hidden": ["contacts", ...]}  (user relation filter)
//
//   POST index.php?entryPoint=sticRelatedPanel&panel_action=savePrefs&module=<Module>
//        body: pinned=1&shrink=0  (user panel preferences)
//
// Responses: 200 JSON; 400 invalid request; 403 no access; 404 record not found.

global $beanList, $current_user;

/**
 * Sends a JSON response and stops.
 */
function sticRelatedPanelRespond($httpCode, array $payload)
{
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

require_once 'SticInclude/SticRelatedPanel/RelatedPanelSettings.php';

$action = isset($_REQUEST['panel_action']) ? strtolower((string) $_REQUEST['panel_action']) : '';
$module = isset($_REQUEST['module']) ? trim((string) $_REQUEST['module']) : '';
$recordId = isset($_REQUEST['record']) ? trim((string) $_REQUEST['record']) : '';

if ($module === '' || !isset($beanList[$module])) {
    sticRelatedPanelRespond(400, array('error' => 'LBL_RRP_INVALID_REQUEST'));
}

// ---------------------------------------------------------------- user order
if ($action === 'saveorder' || $action === 'resetorder') {
    if (!is_object($current_user) || empty($current_user->id)) {
        sticRelatedPanelRespond(403, array('error' => 'LBL_RRP_NO_ACCESS'));
    }
    if ($action === 'saveorder') {
        $order = isset($_POST['order']) && is_array($_POST['order']) ? $_POST['order'] : array();
        sticRelatedPanelRespond(200, array('saved' => SticRelatedPanelSettings::saveUserOrder($module, $order)));
    }
    sticRelatedPanelRespond(200, array('saved' => SticRelatedPanelSettings::resetUserOrder($module)));
}

// ------------------------------------------------------------- user prefs
// Per-user panel preferences (pin/shrink), set from the panel header toolbar
if ($action === 'saveprefs') {
    if (!is_object($current_user) || empty($current_user->id)) {
        sticRelatedPanelRespond(403, array('error' => 'LBL_RRP_NO_ACCESS'));
    }
    $pinned = array_key_exists('pinned', $_POST) ? (!empty($_POST['pinned'])) : null;
    $shrink = array_key_exists('shrink', $_POST) ? (!empty($_POST['shrink'])) : null;
    sticRelatedPanelRespond(200, array(
        'saved' => SticRelatedPanelSettings::saveUserPrefs($pinned, $shrink),
        'user_prefs' => SticRelatedPanelSettings::getUserPrefs(),
    ));
}

// ------------------------------------------------------------ user filter
// Relations hidden by the user in the panel (JSON body: {"hidden": [...]})
if ($action === 'savefilter') {
    if (!is_object($current_user) || empty($current_user->id)) {
        sticRelatedPanelRespond(403, array('error' => 'LBL_RRP_NO_ACCESS'));
    }
    $hidden = array();
    $rawInput = (string) file_get_contents('php://input');
    $decodedBody = $rawInput !== '' ? json_decode($rawInput, true) : null;
    if (is_array($decodedBody) && isset($decodedBody['hidden']) && is_array($decodedBody['hidden'])) {
        $hidden = $decodedBody['hidden'];
    } elseif (isset($_POST['hidden']) && is_array($_POST['hidden'])) {
        $hidden = $_POST['hidden'];
    }
    sticRelatedPanelRespond(200, array(
        'saved' => SticRelatedPanelSettings::saveUserFilter($module, $hidden),
        'hidden_relations' => SticRelatedPanelSettings::getUserFilter($module),
    ));
}

// -------------------------------------------------------------------- status
if ($action === 'status') {
    sticRelatedPanelRespond(200, array(
        'module' => $module,
        'enabled' => SticRelatedPanelSettings::isEnabledForModule($module),
        'pinned' => SticRelatedPanelSettings::isPinned(),
        'hide_empty' => SticRelatedPanelSettings::hideEmpty(),
        'hidden_relations' => SticRelatedPanelSettings::getUserFilter($module),
        'user_prefs' => SticRelatedPanelSettings::getUserPrefs(),
    ));
}

// ---------------------------------------------------------------------- data
if ($recordId === '') {
    sticRelatedPanelRespond(400, array('error' => 'LBL_RRP_INVALID_REQUEST'));
}

// Basic id format check to avoid useless queries.
if (!preg_match('/^[a-zA-Z0-9\-]{6,36}$/', $recordId)) {
    sticRelatedPanelRespond(400, array('error' => 'LBL_RRP_INVALID_REQUEST'));
}

$bean = BeanFactory::getBean($module, $recordId);
if (!$bean || empty($bean->id)) {
    sticRelatedPanelRespond(404, array('error' => 'LBL_RRP_NOT_FOUND'));
}

if (!$bean->ACLAccess('DetailView')) {
    sticRelatedPanelRespond(403, array('error' => 'LBL_RRP_NO_ACCESS'));
}

try {
    require_once 'SticInclude/SticRelatedPanel/RelatedPanelBuilder.php';
    $builder = new SticRelatedPanelBuilder();
    $payload = $builder->build($bean);
    sticRelatedPanelRespond(200, $payload);
} catch (Throwable $e) {
    $GLOBALS['log']->error('sticRelatedPanel: ' . $e->getMessage());
    sticRelatedPanelRespond(500, array('error' => 'LBL_RRP_ERROR'));
}
