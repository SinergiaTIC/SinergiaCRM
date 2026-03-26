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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SticPrivateAreaUtils
{
    /**
     * Prepare private area credentials when AP is enabled
     *
     * @param SugarBean $bean
     * @return void
     */
    public static function processBeforeSave($bean)
    {
        // Skip credential logic when Private Area is disabled
        if (!self::isPrivateAreaEnabledNow($bean)) {
            return;
        }

        // Default username to primary email when empty
        if (empty($bean->stic_pa_username_c)) {
            $primaryEmail = self::getPrimaryEmail($bean);
            if (!empty($primaryEmail)) {
                $bean->stic_pa_username_c = $primaryEmail;
            }
        }

        // Keep plaintext in-memory only for template parsing after save
        if (empty($bean->stic_pa_password_c)) {
            $plainPassword = self::generateRandomPassword();
            $bean->stic_pa_password_c = $plainPassword;
            $bean->_stic_plain_pa_password = $plainPassword;
        } else {
            $bean->_stic_plain_pa_password = $bean->stic_pa_password_c;
        }

        // Prevent duplicate AP usernames across Accounts and Contacts
        self::assertUniquePrivateAreaUsername($bean);
    }

    /**
     * Send credentials email after enabling private area
     *
     * @param SugarBean $bean
     * @return void
     */
    public static function processAfterSave($bean)
    {
        // Send only when AP has just been enabled (0 -> 1)
        if (!self::isPrivateAreaBeingEnabled($bean)) {
            return;
        }

        require_once 'modules/stic_Settings/Utils.php';

        // Feature toggle from settings
        $isEnabled = ((string)stic_SettingsUtils::getSetting('PRIVATE_AREA_SEND_CREDENTIALS_ON_ENABLE') === '1');
        if (!$isEnabled) {
            return;
        }

        // Resolve and validate template
        $templateId = self::getTemplateIdForModule($bean->module_dir ?? '');
        if (empty($templateId)) {
            $GLOBALS['log']->info(__METHOD__ . ': Template setting is empty. Skipping credentials email.');
            return;
        }

        $templateBean = BeanFactory::getBean('EmailTemplates', $templateId);
        if (empty($templateBean) || empty($templateBean->id) || !empty($templateBean->deleted)) {
            $GLOBALS['log']->error(__METHOD__ . ': Invalid credentials template id: ' . $templateId);
            return;
        }

        // Resolve destination address
        $destAddress = self::getPrimaryEmail($bean);
        if (empty($destAddress)) {
            $GLOBALS['log']->info(__METHOD__ . ': Destination email is empty. Skipping credentials email.');
            return;
        }

        // Make plaintext password available for merge fields
        if (!empty($bean->_stic_plain_pa_password)) {
            $bean->stic_pa_password_c = $bean->_stic_plain_pa_password;
        }

        require_once 'SticInclude/Utils.php';
        $parsedMailArray = SticUtils::parseEmailTemplate($templateBean->id, array($bean));

        $subject = $parsedMailArray['subject'] ?? '';
        $bodyHtml = $parsedMailArray['body_html'] ?? '';
        $bodyText = $parsedMailArray['body'] ?? '';

        if (empty($subject) || (empty($bodyHtml) && empty($bodyText))) {
            $GLOBALS['log']->error(__METHOD__ . ': Parsed credentials template is empty.');
            return;
        }

        require_once 'include/SugarPHPMailer.php';
        require_once 'modules/Emails/Email.php';

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();

        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'] ?? '';
        $mail->FromName = $defaults['name'] ?? '';
        $mail->AddAddress($destAddress, self::getRecipientName($bean));
        $mail->Subject = $subject;

        if (!empty($bodyHtml)) {
            $mail->Body = from_html($bodyHtml);
            $mail->isHtml(true);
            $mail->AltBody = from_html($bodyText);
        } else {
            $mail->Body = $bodyText;
            $mail->isHtml(false);
        }

        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->error(__METHOD__ . ': Error sending credentials email: ' . $mail->ErrorInfo);
            return;
        }

        $GLOBALS['log']->info(__METHOD__ . ': Credentials email sent to ' . $destAddress);
    }

    /**
     * Detect transition stic_pa_enable_c from false to true
     *
     * @param SugarBean $bean
     * @return bool
     */
    protected static function isPrivateAreaBeingEnabled($bean)
    {
        $current = (bool)($bean->stic_pa_enable_c ?? false);
        $previous = (bool)($bean->fetched_row['stic_pa_enable_c'] ?? false);

        return $current && !$previous;
    }

    /**
     * Check if AP is enabled in current bean values
     *
     * @param SugarBean $bean
     * @return bool
     */
    protected static function isPrivateAreaEnabledNow($bean)
    {
        return (bool)($bean->stic_pa_enable_c ?? false);
    }

    /**
     * Ensure AP username is unique across Contacts and Accounts
     *
     * @param SugarBean $bean
     * @return void
     * @throws RuntimeException
     */
    protected static function assertUniquePrivateAreaUsername($bean)
    {
        $username = trim((string)($bean->stic_pa_username_c ?? ''));
        if (empty($username)) {
            return;
        }

        if (!self::hasPrivateAreaUsernameDuplicate($username, $bean->id ?? '')) {
            return;
        }

        $message = "Private Area username '{$username}' is already in use by another enabled record. Please use a unique username.";
        $GLOBALS['log']->error(__METHOD__ . ': ' . $message);
        throw new \RuntimeException($message);
    }

    /**
     * Check username collisions in enabled Contacts and Accounts
     *
     * @param string $username
     * @param string $currentId
     * @return bool
     */
    protected static function hasPrivateAreaUsernameDuplicate($username, $currentId = '')
    {
        global $db;

        $usernameQuoted = $db->quote($username);
        $currentIdQuoted = $db->quote((string)$currentId);

        $sql = "
            SELECT 1
            FROM (
                                SELECT c.id AS id
                FROM contacts c
                INNER JOIN contacts_cstm cc ON cc.id_c = c.id
                WHERE c.deleted = 0
                  AND cc.stic_pa_enable_c = 1
                  AND cc.stic_pa_username_c = '{$usernameQuoted}'

                UNION ALL

                                SELECT a.id AS id
                FROM accounts a
                INNER JOIN accounts_cstm ac ON ac.id_c = a.id
                WHERE a.deleted = 0
                  AND ac.stic_pa_enable_c = 1
                  AND ac.stic_pa_username_c = '{$usernameQuoted}'
            ) x
            WHERE x.id <> '{$currentIdQuoted}'
            LIMIT 1
        ";

        return !empty($db->getOne($sql));
    }

    /**
     * Get credentials template setting depending on module
     *
     * @param string $module
     * @return string|null
     */
    protected static function getTemplateIdForModule($module)
    {
        require_once 'modules/stic_Settings/Utils.php';

        if ($module === 'Contacts') {
            return stic_SettingsUtils::getSetting('PRIVATE_AREA_CREDENTIALS_TEMPLATE_CONTACTS') ?: null;
        }

        if ($module === 'Accounts') {
            return stic_SettingsUtils::getSetting('PRIVATE_AREA_CREDENTIALS_TEMPLATE_ACCOUNTS') ?: null;
        }

        return null;
    }

    /**
     * Get primary email address from bean
     *
     * @param SugarBean $bean
     * @return string
     */
    protected static function getPrimaryEmail($bean)
    {
        if (!empty($bean->email1)) {
            return $bean->email1;
        }

        if (!empty($bean->emailAddress)) {
            $email = $bean->emailAddress->getPrimaryAddress($bean);
            if (!empty($email)) {
                return $email;
            }
        }

        return '';
    }

    /**
     * Get recipient display name
     *
     * @param SugarBean $bean
     * @return string
     */
    protected static function getRecipientName($bean)
    {
        if (($bean->module_dir ?? '') === 'Contacts') {
            $fullName = trim(($bean->first_name ?? '') . ' ' . ($bean->last_name ?? ''));
            return !empty($fullName) ? $fullName : ($bean->name ?? '');
        }

        return $bean->name ?? '';
    }

    /**
     * Generate random password
     *
     * @return string
     */
    protected static function generateRandomPassword()
    {
        try {
            return bin2hex(random_bytes(6));
        } catch (\Exception $e) {
            return substr(md5(uniqid((string)mt_rand(), true)), 0, 12);
        }
    }
}
