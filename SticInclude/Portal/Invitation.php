<?php
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
$templateKey = ($module === 'Accounts') ? 'PORTAL_TMPL_CRED_ACCOUNTS' : 'PORTAL_TMPL_CRED_CONTACTS';
$templateId  = $allConfig[$templateKey] ?? '';

$sentCount = 0;
$errors    = array();

foreach ($idList as $id) {
    if (empty($id)) continue;

    $bean = BeanFactory::getBean($module, $id);
    if (!$bean || !$bean->id) { $errors[] = "$id: not found"; continue; }

    $usernameField = 'stic_portal_username_c';
    if (empty($bean->$usernameField)) { $errors[] = "$id: no username"; continue; }

    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 86400);
    $bean->stic_portal_reset_token_c   = hash('sha256', $token);
    $bean->stic_portal_reset_expires_c = $expires;
    $bean->save();

    $resetLink = $portalUrl . '/index.php?entryPoint=sticPortalResetConfirm&token=' . urlencode($token) . '&id=' . urlencode($bean->id);
        $redirectUri = $_REQUEST['redirect_uri'] ?? '';
        if (!empty($redirectUri)) $resetLink .= '&redirect_uri=' . urlencode($redirectUri);

    $emailAddr = SticPortalAuthUtils::getPrimaryEmail($bean);
    if (empty($emailAddr)) { $errors[] = "$id: no email"; continue; }

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
    $subject  = "$portalTitle - Access your portal";
    $bodyHtml = "<p>Hello {$firstName},</p><p>Your portal account is ready.</p><p>Access: <a href=\"{$portalUrl}\">{$portalUrl}</a></p><p>Username: {$bean->$usernameField}</p><p>Click here to set your password: <a href=\"{$resetLink}\">Set Password</a></p><p>This link expires in 24 hours.</p>";

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
        '{$portal_address}'                   => $portalUrl,
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
if ($sentCount > 0) $params[] = 'msg=' . urlencode("$sentCount invitation(s) sent");
if (!empty($errors)) $params[] = 'error=' . urlencode(implode('; ', $errors));
if ($params) $redirectUrl .= '&' . implode('&', $params);
SugarApplication::redirect($redirectUrl);
