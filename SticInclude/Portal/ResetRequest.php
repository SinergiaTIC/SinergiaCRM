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

$id          = $_REQUEST['id'] ?? '';
$module      = $_REQUEST['return_module'] ?? 'Contacts';
$redirectUri = $_REQUEST['redirect_uri'] ?? '';

if (empty($id)) { die('Missing id parameter.'); }

$bean = BeanFactory::getBean($module, $id);
if (!$bean || !$bean->id) { die('Record not found.'); }

$usernameField = 'stic_portal_username_c';
if (empty($bean->$usernameField)) { die('Portal username not set.'); }

$token = bin2hex(random_bytes(32));
$bean->stic_portal_reset_token_c   = hash('sha256', $token);
$bean->stic_portal_reset_expires_c = date('Y-m-d H:i:s', time() + 86400);
$bean->save();

$portalUrl = SticPortalConfigUtils::get('PORTAL_HOME_URL', $GLOBALS['sugar_config']['site_url']);
$portalUrl = rtrim($portalUrl, '/');

$link = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
if (!empty($redirectUri)) $link .= '&redirect_uri=' . urlencode($redirectUri);

$templateId = SticPortalConfigUtils::get('PORTAL_TMPL_RESET', '');

if (empty($templateId)) {
    $subject  = 'Password Reset';
    $bodyHtml = "<p>A password reset link has been generated for your account.</p><p>Click here to reset your password: <a href=\"{$link}\">Reset Password</a></p><p>This link expires in 24 hours.</p>";
} else {
    $tmpl = BeanFactory::getBean('EmailTemplates', $templateId);
    $subject  = $tmpl && $tmpl->id ? html_entity_decode($tmpl->subject, ENT_QUOTES) : 'Password Reset';
    $bodyHtml = $tmpl && $tmpl->id ? html_entity_decode($tmpl->body_html, ENT_QUOTES) : "<p>Click to reset: <a href=\"{$link}\">Reset Password</a></p>";
}

$replace = array(
    '{$portal_reset_link}' => $link,
    '{$portal_address}'    => $portalUrl,
    '{$portal_title}'      => SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal'),
);
$subject  = str_replace(array_keys($replace), array_values($replace), $subject);
$bodyHtml = str_replace(array_keys($replace), array_values($replace), $bodyHtml);
$bodyText = strip_tags(str_replace(array('<br>', '</p>'), array("\n", "\n\n"), $bodyHtml));

$emailAddr = SticPortalAuthUtils::getPrimaryEmail($bean) ?: $bean->$usernameField;

require_once 'include/SugarPHPMailer.php';
$mailer = new SugarPHPMailer();
$mailer->setMailerForSystem();
$mailer->From     = $GLOBALS['sugar_config']['notify_fromaddress'] ?? 'no-reply@crm.local';
$mailer->FromName = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
$mailer->Subject  = $subject;
$mailer->Body     = $bodyHtml;
$mailer->AltBody  = $bodyText;
$mailer->isHTML(true);
$mailer->addAddress($emailAddr);

if ($mailer->Send()) {
    SticPortalAuthUtils::recordLoginAudit($bean, 'RESET_SENT', $bean->$usernameField, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'admin_reset');
    SticPortalAuthUtils::sendSecurityNotification($bean, 'reset_requested');
}

$returnModule = $_REQUEST['return_module'] ?? 'Contacts';
$returnAction = $_REQUEST['return_action'] ?? 'DetailView';
$redirectUrl = "index.php?module=" . urlencode($returnModule) . "&action=" . urlencode($returnAction);
if ($returnAction === 'DetailView') $redirectUrl .= '&record=' . urlencode($id);
$redirectUrl .= '&msg=reset_sent';
SugarApplication::redirect($redirectUrl);
