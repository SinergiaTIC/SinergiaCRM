<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
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
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'SticInclude/Portal/ActionsUtils.php';

$idRaw        = $_REQUEST['id'] ?? '';
$module       = $_REQUEST['return_module'] ?? 'Contacts';
$returnAction = $_REQUEST['return_action'] ?? 'DetailView';
$redirectUri  = $_REQUEST['redirect_uri'] ?? '';

// Accepts a single id (detail view) or a comma-separated list (list view bulk action)
$idList = array_filter(array_map('trim', explode(',', $idRaw)));

if (empty($idList)) {
    $message = translate('LBL_STIC_PORTAL_ACTION_ERROR_ONE', 'Administration');
    SugarApplication::appendErrorMessage("<p class='msg-error'><strong>1</strong> "
        . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . ' No records were selected.</p>');
    SugarApplication::redirect('index.php?module=' . urlencode($module) . '&action=' . urlencode($returnAction));
}

$sentCount = 0;
$errors    = array();

foreach ($idList as $id) {
    if (empty($id)) continue;

    $bean = BeanFactory::getBean($module, $id);
    if (!$bean || !$bean->id) { $errors[] = "$id: not found"; continue; }

    $result = SticPortalActionsUtils::sendPasswordReset($bean, $redirectUri);
    if ($result['success']) {
        $sentCount++;
    } else {
        $errors[] = "$id: " . $result['error'];
    }
}

$redirectUrl = 'index.php?module=' . urlencode($module) . '&action=' . urlencode($returnAction);
if (count($idList) === 1 && $returnAction === 'DetailView') {
    $redirectUrl .= '&record=' . urlencode(reset($idList));
}
if ($sentCount > 0) {
    $sentKey = $sentCount === 1 ? 'LBL_STIC_PORTAL_RESET_SENT_ONE' : 'LBL_STIC_PORTAL_RESET_SENT_MANY';
    $sentLabel = translate($sentKey, 'Administration');
    SugarApplication::appendSuccessMessage("<p class='label label-success'><strong>" . (int) $sentCount . '</strong> '
        . htmlspecialchars($sentLabel, ENT_QUOTES, 'UTF-8') . '.</p>');
    $GLOBALS['log']->debug('Portal password reset: ' . $sentCount . ' email(s) sent successfully.');
}
if (!empty($errors)) {
    $errorKey = count($errors) === 1 ? 'LBL_STIC_PORTAL_ACTION_ERROR_ONE' : 'LBL_STIC_PORTAL_ACTION_ERROR_MANY';
    $errorLabel = translate($errorKey, 'Administration');
    SugarApplication::appendErrorMessage("<p class='msg-error'><strong>" . count($errors) . '</strong> '
        . htmlspecialchars($errorLabel, ENT_QUOTES, 'UTF-8') . ' '
        . htmlspecialchars(implode('; ', $errors), ENT_QUOTES, 'UTF-8') . '</p>');
    $GLOBALS['log']->debug('Portal password reset: ' . count($errors) . ' record(s) failed.');
}
SugarApplication::redirect($redirectUrl);
