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

// STIC-Custom 20261023 DEV - Related records panel
// Entry point handler of the right-hand "related records" panel.
//
//   GET  index.php?entryPoint=sticRelatedPanel&action=status&module=<Module>
//        -> {enabled, pinned, hide_empty}  (cheap, called on every detail view load)
//
//   GET  index.php?entryPoint=sticRelatedPanel&module=<Module>&record=<id>
//        -> full payload with sections (lazy loaded when the panel is opened)
//
//   POST index.php?entryPoint=sticRelatedPanel&action=saveOrder&module=<Module>
//        body: order[]=key1&order[]=key2...  (user section order preference)
//
//   POST index.php?entryPoint=sticRelatedPanel&action=resetOrder&module=<Module>
//        -> removes the user order preference
//
// Responses: 200 JSON; 400 invalid request; 403 no access; 404 record not found.
// See stic-ai-handbook: output/features/related-records-panel

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

require_once 'custom/include/stic_RelatedPanel/RelatedPanelSettings.php';

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

// -------------------------------------------------------------------- status
if ($action === 'status') {
    sticRelatedPanelRespond(200, array(
        'module' => $module,
        'enabled' => SticRelatedPanelSettings::isEnabledForModule($module),
        'pinned' => SticRelatedPanelSettings::isPinned(),
        'hide_empty' => SticRelatedPanelSettings::hideEmpty(),
        'user_order' => SticRelatedPanelSettings::getUserOrder($module),
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
    require_once 'custom/include/stic_RelatedPanel/RelatedPanelBuilder.php';
    $builder = new SticRelatedPanelBuilder();
    $payload = $builder->build($bean);
    sticRelatedPanelRespond(200, $payload);
} catch (Throwable $e) {
    $GLOBALS['log']->error('sticRelatedPanel: ' . $e->getMessage());
    sticRelatedPanelRespond(500, array('error' => 'LBL_RRP_ERROR'));
}
