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

require_once 'SticInclude/Portal/AuthUtils.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
require_once 'modules/Emails/Email.php';
require_once 'include/SugarPHPMailer.php';
require_once 'include/OutboundEmail/OutboundEmail.php';

/**
 * Shared Portal Actions business logic: send the portal invitation email and
 * the portal password reset email for a Contact/Account.
 *
 * PUBLIC API FOR CUSTOM CODE — call it from anywhere (logic hooks, entry
 * points, custom modules, schedulers...):
 *
 *   SticPortalActionsUtils::send('invitation', $bean);
 *   SticPortalActionsUtils::send('pwreset', $bean, $oauthClientId);
 *
 * or the granular methods:
 *
 *   SticPortalActionsUtils::sendInvitation($bean, $redirectUri);
 *   SticPortalActionsUtils::sendPasswordReset($bean, $redirectUri);
 *
 * Both the Portal Actions entry points (SticInclude/Portal/Invitation.php and
 * ResetRequest.php, used by the CRM UI popup) and the workflow action
 * (modules/AOW_Actions/actions/actionPortalActions.php) call these methods, so
 * the UI, the workflow and any custom code always execute exactly the same
 * behavior.
 */
class SticPortalActionsUtils
{
    /**
     * Resolve the default portal username for a Contact/Account following the
     * admin configuration (Portal Configuration > General). The special value
     * 'email' (default) uses the record's primary email; any other value is the
     * name of a varchar/text field of the record. Falls back to the primary
     * email when the configured field is empty or does not exist.
     *
     * @param SugarBean $bean Contact or Account.
     * @return string Default username ('' when no source value is available).
     */
    public static function resolveDefaultUsername($bean)
    {
        $settingKey = ($bean->module_dir === 'Accounts') ? 'PORTAL_USERNAME_FIELD_ACCOUNTS' : 'PORTAL_USERNAME_FIELD_CONTACTS';
        $field = SticPortalConfigUtils::get($settingKey, 'email');
        if ($field !== '' && $field !== 'email' && isset($bean->field_defs[$field])) {
            $value = trim((string) ($bean->$field ?? ''));
            if ($value !== '') {
                return $value;
            }
        }
        return SticPortalAuthUtils::getPrimaryEmail($bean);
    }

    /**
     * Ensure the record has a portal username, filling it from the configured
     * default source when empty.
     *
     * @param SugarBean $bean Contact or Account.
     * @return string The portal username ('' when no source value is available).
     */
    public static function ensurePortalUsername($bean)
    {
        if (empty($bean->stic_portal_username_c)) {
            $username = self::resolveDefaultUsername($bean);
            if ($username !== '') {
                $bean->stic_portal_username_c = $username;
                $bean->save();
            }
        }
        return (string) $bean->stic_portal_username_c;
    }

    /**
     * Send the portal invitation email (welcome message + password setup link).
     * Auto-enables the portal access and fills the portal username from the
     * configured default source when empty.
     *
     * @param SugarBean $bean Contact or Account.
     * @param string $redirectUri Optional external app URL registered on the OAuth2 client.
     * @param string $oauthClientId Optional OAuth2 client id, recorded in the login audit.
     * @param string $oauthClientName Optional OAuth2 client name, recorded in the login audit.
     * @param array $options Optional overrides, as sent by the workflow action:
     *                         'template_id'  => string, '' (default) uses the Portal Configuration template,
     *                         'to'/'cc'/'bcc'=> array of recipient addresses ('' uses the record's primary email),
     *                         'individual'  => bool, send one email per recipient,
     *                         'output_smtp'  => string, outbound account name ('' = system),
     *                         'from_email'/'from_name'/'reply_to'/'reply_to_name' => sender overrides.
     * @return array array('success' => bool, 'error' => string)
     */
    public static function sendInvitation($bean, $redirectUri = '', $oauthClientId = '', $oauthClientName = '', array $options = array())
    {
        global $sugar_config;

        if (!$bean || !$bean->id) {
            return array('success' => false, 'error' => 'record not found');
        }

        $emailAddr = SticPortalAuthUtils::getPrimaryEmail($bean);

        $username = self::ensurePortalUsername($bean);
        if ($username === '') {
            return array('success' => false, 'error' => 'no portal username available');
        }

        $allConfig   = SticPortalConfigUtils::getAll();
        $portalTitle = !empty($allConfig['PORTAL_TITLE']) ? $allConfig['PORTAL_TITLE'] : 'SinergiaCRM Portal';
        $portalUrl   = !empty($allConfig['PORTAL_HOME_URL']) ? rtrim($allConfig['PORTAL_HOME_URL'], '/') : $sugar_config['site_url'];
        $loginUrl    = $portalUrl . '/index.php?entryPoint=sticPortalLogin';
        // If a specific external app was selected, use its URL; otherwise show the portal login URL
        $appUrl      = !empty($redirectUri) ? $redirectUri : $loginUrl;
        $templateKey = ($bean->module_dir === 'Accounts') ? 'PORTAL_TMPL_CRED_ACCOUNTS' : 'PORTAL_TMPL_CRED_CONTACTS';
        // Workflow override: an explicit template wins; '' (default) uses the
        // template selected in Portal Configuration (same as the UI popup).
        $templateId  = ($options['template_id'] ?? '') !== '' ? (string) $options['template_id'] : ($allConfig[$templateKey] ?? '');

        $token   = bin2hex(random_bytes(32));
        $expires = SticPortalAuthUtils::futureDb(86400); // UTC, consistent with validateResetToken() UTC_TIMESTAMP() comparison
        $bean->stic_portal_reset_token_c    = hash('sha256', $token);
        $bean->stic_portal_reset_expires_c = $expires;
        $bean->stic_portal_enabled_c = 1; // Auto-enable portal when invitation is sent
        $bean->save();

        $resetLink = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
        if (!empty($redirectUri)) {
            $resetLink .= '&redirect_uri=' . urlencode($redirectUri);
        }

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

        // Recipients: workflow-provided addresses, falling back to the record's
        // primary email (the behavior of the Portal Actions popup).
        $toList = array_values(array_filter(array_map('trim', (array) ($options['to'] ?? array()))));
        if (empty($toList)) {
            if (empty($emailAddr)) {
                return array('success' => false, 'error' => 'no email address');
            }
            $toList = array($emailAddr);
        }
        $ccList = !empty($options['cc']) ? (array) $options['cc'] : array();
        $bccList = !empty($options['bcc']) ? (array) $options['bcc'] : array();

        $sent = !empty($options['individual'])
            ? self::sendMailEach($subject, $bodyHtml, $toList, $ccList, $bccList, $options)
            : self::sendMail($subject, $bodyHtml, $toList, $ccList, $bccList, $options);
        if (!$sent) {
            return array('success' => false, 'error' => 'email failed');
        }

        SticPortalAuthUtils::recordLoginAudit($bean, $bean->module_name, $bean->stic_portal_username_c,
            $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'invitation', $oauthClientId, $oauthClientName);
        return array('success' => true, 'error' => '');
    }

    /**
     * Send the portal password reset email. Auto-enables the portal access and
     * fills the portal username from the configured default source when empty.
     *
     * @param SugarBean $bean Contact or Account.
     * @param string $redirectUri Optional external app URL registered on the OAuth2 client.
     * @param string $oauthClientId Optional OAuth2 client id, recorded in the login audit.
     * @param string $oauthClientName Optional OAuth2 client name, recorded in the login audit.
     * @param array $options Optional overrides, as sent by the workflow action —
     *                       see sendInvitation() for the supported keys.
     * @return array array('success' => bool, 'error' => string)
     */
    public static function sendPasswordReset($bean, $redirectUri = '', $oauthClientId = '', $oauthClientName = '', array $options = array())
    {
        global $sugar_config;

        if (!$bean || !$bean->id) {
            return array('success' => false, 'error' => 'record not found');
        }

        $username = self::ensurePortalUsername($bean);
        if ($username === '') {
            return array('success' => false, 'error' => 'no portal username available');
        }

        $portalUrl   = rtrim(SticPortalConfigUtils::get('PORTAL_HOME_URL', $sugar_config['site_url']), '/');
        $portalTitle = SticPortalConfigUtils::get('PORTAL_TITLE', 'SinergiaCRM Portal');
        // Workflow override: an explicit template wins; '' (default) uses the
        // template selected in Portal Configuration (same as the UI popup).
        $templateId  = ($options['template_id'] ?? '') !== '' ? (string) $options['template_id'] : SticPortalConfigUtils::get('PORTAL_TMPL_RESET', '');

        // Load the raw template once; record/portal variables are parsed below
        $tmpl        = !empty($templateId) ? BeanFactory::getBean('EmailTemplates', $templateId) : null;
        $hasTemplate = ($tmpl && $tmpl->id);
        $subject     = ($hasTemplate && !empty($tmpl->subject)) ? html_entity_decode($tmpl->subject, ENT_QUOTES) : 'Password Reset';
        $bodyHtml    = $hasTemplate ? SticPortalAuthUtils::getTemplateBodyHtml($tmpl) : '';
        if ($bodyHtml === '') {
            $bodyHtml = '<p>A password reset link has been generated for your account.</p><p>Click here to reset your password: <a href="$portal_reset_link">Reset Password</a></p><p>This link expires in 24 hours.</p>';
        }

        $token = bin2hex(random_bytes(32));
        $bean->stic_portal_reset_token_c   = hash('sha256', $token);
        $bean->stic_portal_reset_expires_c = SticPortalAuthUtils::futureDb(86400); // UTC, consistent with validateResetToken() UTC_TIMESTAMP() comparison
        $bean->stic_portal_enabled_c = 1; // Auto-enable portal when reset request is sent
        $bean->save();

        $link = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
        if (!empty($redirectUri)) $link .= '&redirect_uri=' . urlencode($redirectUri);

        // Portal variables are parsed manually; record variables go through the
        // SinergiaCRM template parser (see SticPortalAuthUtils::parsePortalTemplate()).
        $portalVars = array(
            '$portal_reset_link' => $link,
            '$portal_address'    => $portalUrl,
            '$portal_title'      => $portalTitle,
        );
        $subjectParsed  = SticPortalAuthUtils::parsePortalTemplate($subject, $bean, $portalVars);
        $bodyHtmlParsed = SticPortalAuthUtils::parsePortalTemplate($bodyHtml, $bean, $portalVars);

        // Recipients: workflow-provided addresses, falling back to the record's
        // primary email / portal username (the behavior of the Portal Actions popup).
        $fallbackAddr = SticPortalAuthUtils::getPrimaryEmail($bean) ?: $bean->stic_portal_username_c;
        $toList = !empty($options['to']) ? (array) $options['to'] : array($fallbackAddr);
        $ccList = !empty($options['cc']) ? (array) $options['cc'] : array();
        $bccList = !empty($options['bcc']) ? (array) $options['bcc'] : array();

        $sent = !empty($options['individual'])
            ? self::sendMailEach($subjectParsed, $bodyHtmlParsed, $toList, $ccList, $bccList, $options)
            : self::sendMail($subjectParsed, $bodyHtmlParsed, $toList, $ccList, $bccList, $options);
        if (!$sent) {
            return array('success' => false, 'error' => 'email failed');
        }

        SticPortalAuthUtils::recordLoginAudit($bean, 'RESET_SENT', $bean->stic_portal_username_c,
            $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '', true, null, 'admin_reset', $oauthClientId, $oauthClientName);
        SticPortalAuthUtils::sendSecurityNotification($bean, 'reset_requested');
        return array('success' => true, 'error' => '');
    }

    /**
     * Public convenience entry point: run a portal action on a Contact/Account.
     *
     * Intended for custom code (logic hooks, entry points, schedulers...) that
     * wants to trigger a portal invitation or password reset with one call:
     *
     *   SticPortalActionsUtils::send('invitation', $bean);
     *   SticPortalActionsUtils::send('pwreset', $bean, $oauthClientId);
     *
     * @param string $action 'invitation' (welcome + password setup) or 'pwreset'.
     * @param SugarBean $bean Contact or Account the action applies to.
     * @param string $appId Optional portal OAuth2 client id; its registered
     *                      redirect_url is embedded in the password-setup link
     *                      so the user lands on that app after setting the password.
     * @return array array('success' => bool, 'error' => string)
     */
    public static function send($action, $bean, $appId = '', array $options = array())
    {
        $redirectUri = '';
        $oauthClientId = '';
        $oauthClientName = '';
        if ($appId !== '') {
            $client = BeanFactory::getBean('OAuth2Clients', $appId);
            if ($client && $client->id && $client->deleted != 1 && $client->allowed_grant_type === 'portal_authorization_code') {
                $redirectUri = $client->redirect_url ?? '';
                $oauthClientId = (string) $client->id;
                $oauthClientName = (string) $client->name;
            }
        }
        if ($action === 'pwreset') {
            return self::sendPasswordReset($bean, $redirectUri, $oauthClientId, $oauthClientName, $options);
        }
        return self::sendInvitation($bean, $redirectUri, $oauthClientId, $oauthClientName, $options);
    }

    /**
     * Send an HTML email through the configured outbound account.
     *
     * The sender identity defaults to the system SMTP account (avoids
     * SendAsDenied when the CRM notification address differs from the
     * authenticated outbound mailbox). The workflow action can override the
     * outbound account and sender identity through $options (same parameters as
     * the Send Email workflow action).
     *
     * @param string $subject Parsed email subject.
     * @param string $bodyHtml Parsed HTML body.
     * @param array $toList Recipient addresses.
     * @param array $ccList CC addresses.
     * @param array $bccList BCC addresses.
     * @param array $options Optional mailer overrides: 'output_smtp',
     *                      'from_email', 'from_name', 'reply_to', 'reply_to_name'.
     * @return bool True when the email was accepted by the mailer.
     */
    private static function sendMail($subject, $bodyHtml, $toList, $ccList = array(), $bccList = array(), array $options = array())
    {
        $toList = array_values(array_filter(array_map('trim', (array) $toList)));
        if (empty($toList)) return false;
        $ccList = array_values(array_filter(array_map('trim', (array) $ccList)));
        $bccList = array_values(array_filter(array_map('trim', (array) $bccList)));
        $bodyText = strip_tags(str_replace(array('<br>', '</p>'), array("\n", "\n\n"), $bodyHtml));

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();

        // Outbound account: system default, or the account named by the workflow
        $mailerName = (string) ($options['output_smtp'] ?? '');
        $outboundEmail = new OutboundEmail();
        if ($mailerName === '' || $mailerName === 'system') {
            $outboundEmail = $outboundEmail->getSystemMailerSettings();
        } else {
            $user = new User();
            $user->getSystemUser();
            $outboundEmail = $outboundEmail->getMailerByName($user, $mailerName);
        }

        $mailer = new SugarPHPMailer();
        $mailer->Mailer = 'smtp';
        $mailer->Host = $outboundEmail->mail_smtpserver;
        $mailer->Port = $outboundEmail->mail_smtpport;
        if ($outboundEmail->mail_smtpssl == 1) {
            $mailer->SMTPSecure = 'ssl';
        }
        if ($outboundEmail->mail_smtpssl == 2) {
            $mailer->SMTPSecure = 'tls';
        }
        if ($outboundEmail->mail_smtpauth_req) {
            $mailer->SMTPAuth = true;
            $mailer->Username = $outboundEmail->mail_smtpuser;
            $mailer->Password = $outboundEmail->mail_smtppass;
        }

        $fromEmail = (string) ($options['from_email'] ?? '');
        $fromName  = (string) ($options['from_name'] ?? '');
        $mailer->From = $fromEmail !== '' ? $fromEmail : (!empty($outboundEmail->smtp_from_addr) ? $outboundEmail->smtp_from_addr : ($defaults['email'] ?? ''));
        $mailer->FromName = $fromName !== '' ? $fromName : (!empty($outboundEmail->smtp_from_name) ? $outboundEmail->smtp_from_name : ($defaults['name'] ?? ''));
        isValidEmailAddress($mailer->From);

        $mailer->Subject  = $subject;
        $mailer->Body     = $bodyHtml;
        $mailer->AltBody  = $bodyText;
        $mailer->isHTML(true);

        foreach ($toList as $to) {
            $mailer->addAddress($to);
        }
        foreach ($ccList as $cc) {
            $mailer->addCC($cc);
        }
        foreach ($bccList as $bcc) {
            $mailer->addBCC($bcc);
        }
        $replyTo = (string) ($options['reply_to'] ?? '');
        if ($replyTo !== '') {
            $mailer->addReplyTo($replyTo, (string) ($options['reply_to_name'] ?? ''));
        }

        return $mailer->Send();
    }

    /**
     * "Send Individual Emails": one separate email per recipient, as in the
     * Send Email workflow action (each recipient only sees their own address).
     *
     * @return bool True when every email was accepted by the mailer.
     */
    private static function sendMailEach($subject, $bodyHtml, $toList, $ccList = array(), $bccList = array(), array $options = array())
    {
        $allOk = true;
        foreach ((array) $toList as $toOne) {
            if (!self::sendMail($subject, $bodyHtml, array($toOne), $ccList, $bccList, $options)) {
                $allOk = false;
            }
        }
        return $allOk;
    }
}
