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

$loginUrl = $portalUrl . '/index.php?entryPoint=sticPortalLogin';
// If a specific external app was selected, use its URL; otherwise show the portal login URL
$appUrl = !empty($redirectUri) ? $redirectUri : $loginUrl;
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
    $expires = SticPortalAuthUtils::futureDb(86400); // UTC, consistent with validateResetToken() UTC_TIMESTAMP() comparison
    $bean->stic_portal_reset_token_c   = hash('sha256', $token);
    $bean->stic_portal_reset_expires_c = $expires;
    $bean->stic_portal_enabled_c = 1; // Auto-enable portal when invitation is sent
    $bean->save();

    $resetLink = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
        $redirectUri = $_REQUEST['redirect_uri'] ?? '';
        if (!empty($redirectUri)) $resetLink .= '&redirect_uri=' . urlencode($redirectUri);

    // Get template content (or use defaults)
    $adminStrings = return_module_language($GLOBALS['current_language'], 'Administration');
    $subject  = str_replace('$portal_title', $portalTitle, $adminStrings['LBL_STIC_PORTAL_INVITATION_SUBJECT']);
    $bodyHtml = $adminStrings['LBL_STIC_PORTAL_INVITATION_BODY'];

    if (!empty($templateId)) {
        $tmpl = BeanFactory::getBean('EmailTemplates', $templateId);
        if ($tmpl && $tmpl->id) {
            if (!empty($tmpl->subject)) $subject = html_entity_decode($tmpl->subject, ENT_QUOTES);
            $tmplBody = SticPortalAuthUtils::getTemplateBodyHtml($tmpl);
            if ($tmplBody !== '') $bodyHtml = $tmplBody;
        }
    }

    // ── Email template variable parsing ─────────────
    // TWO variable groups:
    //  1) Record variables ($contact_xxx / $account_xxx) go through the core
    //     SinergiaCRM/SuiteCRM template parser — EmailTemplate::parse_email_template()
    //     called with the record and its module (see
    //     SticPortalAuthUtils::parsePortalTemplate()). Because the parser walks the
    //     record's field definitions, ANY field of this Contact/Account — present now
    //     or added in the future — can be referenced from the email templates with the
    //     standard SinergiaCRM template syntax ($contact_first_name,
    //     $account_stic_identification_number_c, ...), with no portal-specific code
    //     changes needed.
    //  2) Portal variables (no record behind them — they do not belong to any module)
    //     must be parsed manually and documented:
    //       $portal_title, $portal_address, $portal_login_url, $portal_reset_link
    //     (see SticPortalAuthUtils::parsePortalTemplate for the full manual list used
    //     by every portal email; the record name fields are native parser variables:
    //     $contact_full_name / $account_name).
    $portalVars = array(
        '$portal_address'      => $appUrl,
        '$portal_login_url'    => $loginUrl,
        '$portal_title'        => $portalTitle,
        '$portal_reset_link'   => $resetLink,
    );
    $subject  = SticPortalAuthUtils::parsePortalTemplate($subject, $bean, $portalVars);
    $bodyHtml = SticPortalAuthUtils::parsePortalTemplate($bodyHtml, $bean, $portalVars);
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
        SticPortalAuthUtils::recordLoginAudit($bean, $bean->module_name, $bean->$usernameField,
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
