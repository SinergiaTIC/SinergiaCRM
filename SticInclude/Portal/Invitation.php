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
require_once 'SticInclude/Portal/ConfigUtils.php';
require_once 'include/SugarPHPMailer.php';

global $db, $sugar_config;

$idRaw        = $_REQUEST['id'] ?? '';
$module       = $_REQUEST['return_module'] ?? 'Contacts';
$returnAction = $_REQUEST['return_action'] ?? 'DetailView';

if (empty($idRaw)) { die('Missing id parameter.'); }

$idList = array_filter(array_map('trim', explode(',', $idRaw)));

$allConfig = SticPortalConfigUtils::getAll();
$portalTitle = !empty($allConfig['PORTAL_TITLE']) ? $allConfig['PORTAL_TITLE'] : 'SinergiaCRM Portal';
$portalUrl   = !empty($allConfig['PORTAL_HOME_URL']) ? rtrim($allConfig['PORTAL_HOME_URL'], '/') : $sugar_config['site_url'];
$redirectUri = $_REQUEST['redirect_uri'] ?? '';
// If a specific external app was selected, use its URL; otherwise show the portal login URL
$appUrl = !empty($redirectUri) ? $redirectUri : $portalUrl . '/index.php?entryPoint=sticPortalLogin';
$templateKey = ($module === 'Accounts') ? 'PORTAL_TMPL_CRED_ACCOUNTS' : 'PORTAL_TMPL_CRED_CONTACTS';
$templateId  = $allConfig[$templateKey] ?? '';

$sentCount = 0;
$errors    = array();

foreach ($idList as $id) {
    if (empty($id)) continue;

    $bean = BeanFactory::getBean($module, $id);
    if (!$bean || !$bean->id) { $errors[] = "$id: not found"; continue; }

    $usernameField = 'stic_portal_username_c';
    $emailAddr = SticPortalAuthUtils::getPrimaryEmail($bean);
    if (empty($emailAddr)) { $errors[] = "$id: no email address"; continue; }
    if (empty($bean->$usernameField)) {
        $bean->$usernameField = $emailAddr;
        $bean->save();
    }

    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 86400);
    $bean->stic_portal_reset_token_c   = hash('sha256', $token);
    $bean->stic_portal_reset_expires_c = $expires;
    $bean->stic_portal_enabled_c = 1; // Auto-enable portal when invitation is sent
    $bean->save();

    $resetLink = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
        $redirectUri = $_REQUEST['redirect_uri'] ?? '';
        if (!empty($redirectUri)) $resetLink .= '&redirect_uri=' . urlencode($redirectUri);

    

    if ($module === 'Contacts') {
        $firstName = $bean->first_name ?? '';
        $lastName  = $bean->last_name ?? '';
        $fullName  = $bean->full_name ?? $bean->$usernameField;
    } else {
        $firstName = $bean->name ?? '';
        $lastName  = '';
        $fullName  = $bean->name ?? '';
    }

    // Get template content (or use defaults)
    $adminStrings = return_module_language($GLOBALS['current_language'], 'Administration');
    $subject  = str_replace('{$portal_title}', $portalTitle, $adminStrings['LBL_STIC_PORTAL_INVITATION_SUBJECT']);
    $bodyHtml = $adminStrings['LBL_STIC_PORTAL_INVITATION_BODY'];

    if (!empty($templateId)) {
        $tmpl = BeanFactory::getBean('EmailTemplates', $templateId);
        if ($tmpl && $tmpl->id) {
            if (!empty($tmpl->subject)) $subject = html_entity_decode($tmpl->subject, ENT_QUOTES);
            if (!empty($tmpl->body_html)) $bodyHtml = html_entity_decode($tmpl->body_html, ENT_QUOTES);
        }
    }

    // Replace Smarty-style variables
    $replace = array(
        '{$contact_first_name}'               => $firstName,
        '{$contact_last_name}'                => $lastName,
        '{$contact_name}'                     => $fullName,
        '{$contact_description}'              => $bean->description ?? '',
        '{$contact_stic_portal_username_c}'   => $bean->$usernameField,
        '{$portal_address}'                   => $appUrl,
        '{$portal_login_url}'                 => $loginUrl,
        '{$portal_title}'                     => $portalTitle,
        '{$portal_reset_link}'                => $resetLink,
        // Also handle Account-prefixed variables
        '{$account_stic_portal_username_c}'   => $bean->$usernameField,
    );
    $subject  = str_replace(array_keys($replace), array_values($replace), $subject);
    $bodyHtml = str_replace(array_keys($replace), array_values($replace), $bodyHtml);
    $bodyText = strip_tags(str_replace(array('<br>', '</p>'), array("\n", "\n\n"), $bodyHtml));

    $mailer = new SugarPHPMailer();
    $mailer->setMailerForSystem();
    $mailer->From     = $sugar_config['notify_fromaddress'] ?? 'no-reply@crm.local';
    $mailer->FromName = $portalTitle;
    $mailer->Subject  = $subject;
    $mailer->Body     = $bodyHtml;
    $mailer->AltBody  = $bodyText;
    $mailer->isHTML(true);
    $mailer->addAddress($emailAddr);
    $sent = $mailer->Send();

    if ($sent) {
        SticPortalAuthUtils::recordLoginAudit($bean, 'RESET_SENT', $bean->$usernameField,
            $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'invitation');
        $sentCount++;
    } else {
        $errors[] = "$id: email failed";
    }
}

$redirectUrl = 'index.php?module=' . urlencode($module) . '&action=' . urlencode($returnAction);
if (count($idList) === 1 && $returnAction === 'DetailView') {
    $redirectUrl .= '&record=' . urlencode($idList[0]);
}
$params = array();
$msgParts = array();
if ($sentCount > 0) $msgParts[] = "$sentCount invitation(s) sent";
if (!empty($errors)) $msgParts[] = 'Errors: ' . implode('; ', $errors);
if ($msgParts) $params[] = 'msg=' . urlencode(implode('. ', $msgParts));
if ($params) $redirectUrl .= '&' . implode('&', $params);
SugarApplication::redirect($redirectUrl);
