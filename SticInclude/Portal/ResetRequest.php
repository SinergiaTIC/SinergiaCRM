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

$idRaw        = $_REQUEST['id'] ?? '';
$module       = $_REQUEST['return_module'] ?? 'Contacts';
$returnAction = $_REQUEST['return_action'] ?? 'DetailView';
$redirectUri  = $_REQUEST['redirect_uri'] ?? '';

if (empty($idRaw)) { die('Missing id parameter.'); }

// Accepts a single id (detail view) or a comma-separated list (list view bulk action)
$idList = array_filter(array_map('trim', explode(',', $idRaw)));

$portalUrl   = rtrim(SticPortalConfigUtils::get('PORTAL_HOME_URL', $GLOBALS['sugar_config']['site_url']), '/');
$portalTitle = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
$templateId  = SticPortalConfigUtils::get('PORTAL_TMPL_RESET', '');

// Load the raw template once; record/portal variables are parsed per record below
$tmpl        = !empty($templateId) ? BeanFactory::getBean('EmailTemplates', $templateId) : null;
$hasTemplate = ($tmpl && $tmpl->id);
$subject     = ($hasTemplate && !empty($tmpl->subject)) ? html_entity_decode($tmpl->subject, ENT_QUOTES) : 'Password Reset';
$bodyHtml    = $hasTemplate ? SticPortalAuthUtils::getTemplateBodyHtml($tmpl) : '';
if ($bodyHtml === '') {
    $bodyHtml = '<p>A password reset link has been generated for your account.</p><p>Click here to reset your password: <a href="$portal_reset_link">Reset Password</a></p><p>This link expires in 24 hours.</p>';
}

$sentCount = 0;
$errors    = array();

foreach ($idList as $id) {
    if (empty($id)) continue;

    $bean = BeanFactory::getBean($module, $id);
    if (!$bean || !$bean->id) { $errors[] = "$id: not found"; continue; }

    $usernameField = 'stic_portal_username_c';
    if (empty($bean->$usernameField)) { $errors[] = "$id: portal username not set"; continue; }

    $token = bin2hex(random_bytes(32));
    $bean->stic_portal_reset_token_c   = hash('sha256', $token);
    $bean->stic_portal_reset_expires_c = SticPortalAuthUtils::futureDb(86400); // UTC, consistent with validateResetToken() UTC_TIMESTAMP() comparison
    $bean->stic_portal_enabled_c = 1; // Auto-enable portal when reset request is sent
    $bean->save();

    $link = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
    if (!empty($redirectUri)) $link .= '&redirect_uri=' . urlencode($redirectUri);

    // Portal variables are parsed manually; record variables go through the
    // SinergiaCRM template parser (see SticPortalAuthUtils::parsePortalTemplate()).
    // Note: the parsed copies are used, so the raw template stays reusable for
    // the next record of the batch.
    $portalVars = array(
        '$portal_reset_link' => $link,
        '$portal_address'    => $portalUrl,
        '$portal_title'      => $portalTitle,
    );
    $subjectParsed  = SticPortalAuthUtils::parsePortalTemplate($subject, $bean, $portalVars);
    $bodyHtmlParsed = SticPortalAuthUtils::parsePortalTemplate($bodyHtml, $bean, $portalVars);
    $bodyText = strip_tags(str_replace(array('<br>', '</p>'), array("\n", "\n\n"), $bodyHtmlParsed));

    $emailAddr = SticPortalAuthUtils::getPrimaryEmail($bean) ?: $bean->$usernameField;

    $mailer = new SugarPHPMailer();
    $mailer->setMailerForSystem();
    $mailer->From     = $GLOBALS['sugar_config']['notify_fromaddress'] ?? 'no-reply@crm.local';
    $mailer->FromName = $portalTitle;
    $mailer->Subject  = $subjectParsed;
    $mailer->Body     = $bodyHtmlParsed;
    $mailer->AltBody  = $bodyText;
    $mailer->isHTML(true);
    $mailer->addAddress($emailAddr);

    if ($mailer->Send()) {
        SticPortalAuthUtils::recordLoginAudit($bean, 'RESET_SENT', $bean->$usernameField, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'admin_reset');
        SticPortalAuthUtils::sendSecurityNotification($bean, 'reset_requested');
        $sentCount++;
    } else {
        $errors[] = "$id: email failed";
    }
}

$redirectUrl = 'index.php?module=' . urlencode($module) . '&action=' . urlencode($returnAction);
if (count($idList) === 1 && $returnAction === 'DetailView') {
    $redirectUrl .= '&record=' . urlencode(reset($idList));
}
$msgParts = array();
if ($sentCount > 0) $msgParts[] = "$sentCount reset email(s) sent";
if (!empty($errors)) $msgParts[] = 'Errors: ' . implode('; ', $errors);
if ($msgParts) $redirectUrl .= '&msg=' . urlencode(implode('. ', $msgParts));
SugarApplication::redirect($redirectUrl);
