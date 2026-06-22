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

$message = '';
$error   = $_GET["error"] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $result = SticPortalAuthUtils::getPortalUserByUsername($username);
    if ($result) {
        $bean = $result['bean'];
        $rawToken = SticPortalAuthUtils::generateResetToken($bean);
        SticPortalAuthUtils::sendSecurityNotification($bean, 'reset_requested');
        // Also send the actual reset link email
        sendResetLinkEmail($bean, $rawToken);
    }
    $message = 'If the account exists, a reset link has been sent to your email.';
}

$ss = new Sugar_Smarty();
$ss->assign('TITLE', 'Reset Password');
$ss->assign('ERROR', $error);
$ss->assign('MESSAGE', $message);
$ss->display('custom/themes/SuiteP/tpls/SticPortalReset.tpl');

function sendResetLinkEmail($bean, $rawToken) {
    $portalUrl = SticPortalConfigUtils::get('PORTAL_HOME_URL', $GLOBALS['sugar_config']['site_url']);
    $portalUrl = rtrim($portalUrl, '/');
    $link = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($rawToken) . '&id=' . urlencode($bean->id);

    $templateId = SticPortalConfigUtils::get('PORTAL_TMPL_RESET', '');
    $to = SticPortalAuthUtils::getPrimaryEmail($bean);
    if (empty($to) || empty($templateId)) return;

    $tmpl = BeanFactory::getBean('EmailTemplates', $templateId);
    if (!$tmpl || !$tmpl->id) return;

    $subject  = html_entity_decode($tmpl->subject, ENT_QUOTES);
    $bodyHtml = html_entity_decode($tmpl->body_html, ENT_QUOTES);

    $replace = [
        '{$portal_reset_link}' => $link,
        '{$portal_address}'    => $portalUrl,
        '{$portal_title}'      => SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal'),
    ];
    $subject  = str_replace(array_keys($replace), array_values($replace), $subject);
    $bodyHtml = str_replace(array_keys($replace), array_values($replace), $bodyHtml);
    $bodyText = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $bodyHtml));

    require_once 'include/SugarPHPMailer.php';
    $mailer = new SugarPHPMailer();
    $mailer->setMailerForSystem();
    $mailer->From     = $GLOBALS['sugar_config']['notify_fromaddress'] ?? 'no-reply@crm.local';
    $mailer->FromName = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
    $mailer->Subject  = $subject;
    $mailer->Body     = $bodyHtml;
    $mailer->AltBody  = $bodyText;
    $mailer->isHTML(true);
    $mailer->addAddress($to);
    $mailer->Send();
}
