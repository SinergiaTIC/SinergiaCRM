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

/**
 * Utility class for handling operations related to stic_Signers module.
 * This includes functionality for sending signature requests via email.
 */
class stic_SignersUtils
{
    /**
     * Sends a signature request email to the specified signer.
     * This function retrieves signer details, constructs an email with a unique
     * signing link, and sends it using SugarCRM's mailer.
     *
     * @param string $signerId The ID of the signer to whom the email should be sent.
     * @throws Exception If the signer ID is empty or the destination email address is invalid.
     * @return void
     */
    public static function sendToSign($signerId)
    {
        global $sugar_config, $current_user, $mod_strings;

        // Validate signer ID
        if (empty($signerId)) {
            throw new Exception("Signer ID cannot be empty.");
        }

        // Retrieve the signer bean
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);

        // Get the destination email address for the signer
        $destAddress = $signerBean->email_address ?? '';

        // Prepare mailer
        require_once 'include/SugarPHPMailer.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();

        // Set From and FromName
        $fromEmail = $current_user->email1;
        if (!$fromEmail) {
            $fromEmail = $defaults['email'];
        }
        $mail->From = $fromEmail;

        $fromName = $current_user->name;
        if (!$fromName) {
            $fromName = $defaults['name'];
        }
        $mail->FromName = $fromName;

        // Add recipient
        if (empty($destAddress)) {
            // If no destination address, return false (or handle error appropriately)
            echo json_encode(false);
            die();
        }
        $mail->AddAddress($destAddress);

        // Set the email subject
        $subject = $mod_strings['LBL_SIGNER_EMAIL_SUBJECT'];
        $mail->Subject = $subject;

        // Construct the unique signing URL
        // Determine the base URI for constructing URLs (to handle different server setups)
        // $uri = str_replace('index.php', '', $_SERVER['DOCUMENT_URI']) ?? '';

        $signURL = "{$sugar_config['site_url']}/index.php?entryPoint=sticSign&signerId={$signerId}";

        // Prepare the complete HTML body of the email
        $completeHTML = "<html>
                            <head>
                                <title>{$subject}</title>
                            </head>
                            <body style=\"font-family: Arial, sans-serif; font-size: 14px; color: #333;\">

                            {$mod_strings['LBL_SIGNER_EMAIL_BODY']}

                            <p style=\"margin-top: 20px;\">Para completar el proceso, por favor, firma el documento haciendo clic en el siguiente botón:</p>

                            <table role=\"presentation\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"margin: auto;\">
                                <tr>
                                    <td style=\"border-radius: 5px; background: #007BFF;\" align=\"center\">
                                        <a href=\"{$signURL}\" target=\"_blank\" style=\"font-size: 16px; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; padding: 12px 20px; border: 1px solid #007BFF; display: inline-block; font-weight: bold;\">
                                            {$mod_strings['LBL_SIGNER_EMAIL_BUTTON_TEXT']}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style=\"margin-top: 20px; font-size: 12px; color: #777;\">{$mod_strings['LBL_SIGNER_EMAIL_LINK_PROBLEM']}: <br> <a href=\"{$signURL}\" target=\"_blank\" style=\"color: #007BFF; text-decoration: underline;\">{$signURL}</a></p>

                            </body>
                        </html>";
        $mail->Body = from_html($completeHTML);
        $mail->isHtml(true);
        $mail->prepForOutbound();

        // Attempt to send the email and log the result
        if (!$mail->Send()) {
            SugarApplication::appendErrorMessage("<p class='label label-error'>" . $mod_strings['LBL_SIGNER_EMAIL_ERROR'] . ".</p>");
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": There was an error sending the email to {$destAddress}. Mailer Error: " . $mail->ErrorInfo);
        } else {
            SugarApplication::appendSuccessMessage("<p class='label label-success'>" . $mod_strings['LBL_SIGNER_EMAIL_SUCCESS'] . ".</p>");
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Email sent successfully to {$destAddress}.");
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('EMAIL_SENT', $signerId, 'SIGNER', $destAddress);
        }

    }

    /**
     * Sends a One-Time Password (OTP) to the signer via email.
     * This function retrieves signer details, generates an OTP if not already present,
     * constructs an email with the OTP, and sends it using SugarCRM's mailer.
     *
     * @param object $signerBean The bean object of the signer to whom the OTP should be sent.
     * @throws Exception If the signer ID is empty or the destination email address is invalid.
     * @return bool True if the email was sent successfully, false otherwise.
     */
    public static function sendOtpEmailToSigner($signerBean, $otpCode)
    {
        global $sugar_config, $current_user, $mod_strings;
        $signerId = $signerBean->id;
        $signerStrings = return_module_language($GLOBALS['current_language'], 'stic_Signers');
        // Validate signer ID
        if (empty($signerId)) {
            throw new Exception("Signer ID cannot be empty.");
        }

        // Retrieve the signer bean
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);

        // Get the destination email address for the signer
        $destAddress = $signerBean->email_address ?? '';

        // Prepare mailer
        require_once 'include/SugarPHPMailer.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();

        // Set From and FromName
        $fromEmail = $current_user->email1;
        if (!$fromEmail) {
            $fromEmail = $defaults['email'];
        }
        $mail->From = $fromEmail;

        $fromName = $current_user->name;
        if (!$fromName) {
            $fromName = $defaults['name'];
        }
        $mail->FromName = $fromName;

        // Add recipient
        if (empty($destAddress)) {
            // If no destination address, return false (or handle error appropriately)
            echo json_encode(false);
            die();
        }
        $mail->AddAddress($destAddress);

        // Set the email subject
        $subject = $signerStrings['LBL_SIGNER_EMAIL_OTP_SUBJECT'];
        $mail->Subject = $subject;

        // // Construct the unique signing URL
        // $signURL = "{$sugar_config['site_url']}/index.php?entryPoint=sticSign&signerId={$signerId}";

        // Prepare the complete HTML body of the email
        $completeHTML = "<html>
                            <head>
                                <title>{$subject}</title>
                            </head>
                            <body style=\"font-family: Arial, sans-serif; font-size: 14px; color: #333;\">

                            <h2>El código de verificación es {$signerBean->otp}</h2>
                            <p style=\"margin-top: 20px;\">Este código es válido por 10 minutos.</p>
                            <p style=\"margin-top: 20px;\">Para completar el proceso, por favor, vuelve al portal de firmas e introduce el código en el campo correspondiente
                            </body>
                        </html>";
        $mail->Body = from_html($completeHTML);
        $mail->isHtml(true);
        $mail->prepForOutbound();

        // Attempt to send the email and log the result
        if (!$mail->Send()) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": There was an error sending OTP the email to {$destAddress}. Mailer Error: " . $mail->ErrorInfo);
            return false;
        } else {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": OTP Email sent successfully to {$destAddress}.");
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('OTP_SENT', $signerId, 'SIGNER', $destAddress);
            $_SESSION['otp_signed_sent'][$signerId] = [
                'method' => 'email',
                'time' => time(),
                'email' => $destAddress,
            ];
            return true;
        }

    }

    /**
     * Sends a One-Time Password (OTP) to the signer via phone (SMS).
     * This function retrieves signer details, generates an OTP if not already present,
     * constructs an SMS message with the OTP, and sends it using an SMS gateway.
     *
     * @param object $signerBean The bean object of the signer to whom the OTP should be sent.
     * @throws Exception If the signer ID is empty or the destination phone number is invalid.
     * @return bool True if the SMS was sent successfully, false otherwise.
     */
    public static function sendOtpPhoneMessageToSigner($signerBean, $otpCode)
    {
        $mod_strings = return_module_language($GLOBALS['current_language'], 'stic_Signers');
        $signerId = $signerBean->id;
        $destPhone = $signerBean->phone ?? '';

        if (empty($destPhone)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": No phone number available for signer ID {$signerId}.");
            return false;
        }

        require_once 'modules/stic_Settings/Utils.php';
        $sender = stic_SettingsUtils::getSetting('MESSAGES_SENDER') ?? 'SinergiaCRM Signature Portal';

        $templateMark = $signerBean->parent_type == 'Contacts' ? '$contact_first_name' : '$contact_user_first_name';

        $messageBean = BeanFactory::newBean('stic_Messages');
        $messageBean->phone = $signerBean->phone;
        $messageBean->parent_type = $signerBean->parent_type;
        $messageBean->parent_id = $signerBean->parent_id;
        $messageBean->message = $mod_strings['LBL_SIGNER_OTP_SMS_BODY_1'] . ' ' . ($templateMark) . ', ' . $mod_strings['LBL_SIGNER_OTP_SMS_BODY_2'] . ' ' . $otpCode;
        $messageBean->sender = $sender;
        $messageBean->status = 'sent';
        $messageBean->type = 'SevenSmsHelper';
        $messageBean->save();

        if (!is_string($messageBean->id) || $messageBean->status !== 'sent') {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to create message record for OTP via phone to signer ID {$signerId}.");
            return false;

        }

        // Log the OTP sending action
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": OTP sent via phone to {$destPhone} for signer ID {$signerId}.");

        require_once 'modules/stic_Signature_Log/Utils.php';
        stic_SignatureLogUtils::logSignatureAction('OTP_SENT_PHONE', $signerId, 'SIGNER', $destPhone);

        $_SESSION['otp_signed_sent'][$signerId] = [
            'method' => 'phone',
            'time' => time(),
            'phone' => $destPhone,
        ];

        return true;
    }

/**
 * Get stic_Signers records for a specific contact
 *
 * @param array $params Parameters from subpanel configuration
 * @return array Query result for subpanel
 */
    public static function getSticSignersForContacts()
    {
        $contact_id = $_REQUEST['record'];
        if (empty($contact_id)) {
            return array();
        }

        $query = "

        SELECT
                stic_signers.id,
                stic_signers.name,
                stic_signers.date_entered,
                stic_signers.date_modified,
                stic_signers.modified_user_id,
                stic_signers.created_by,
                stic_signers.description,
                stic_signers.deleted,
                stic_signers.assigned_user_id,
                stic_signers.status,
                stic_signers.signature_date,
                stic_signers.parent_type,
                stic_signers.parent_id
            FROM stic_signers
            WHERE parent_type = 'Contacts'
                AND parent_id = '{$contact_id}'
                AND status in ('pending','signed')
                AND deleted = 0
            ORDER BY date_modified DESC
        ";
        return $query;
    }
/**
 * Get stic_Signers records for a specific user
 *
 * @param array $params Parameters from subpanel configuration
 * @return array Query result for subpanel
 */
    public static function getSticSignersForUsers()
    {
        $user_id = $_REQUEST['record'];
        if (empty($user_id)) {
            return array();
        }

        $query = "

        SELECT
                stic_signers.id,
                stic_signers.name,
                stic_signers.date_entered,
                stic_signers.date_modified,
                stic_signers.modified_user_id,
                stic_signers.created_by,
                stic_signers.description,
                stic_signers.deleted,
                stic_signers.assigned_user_id,
                stic_signers.status,
                stic_signers.signature_date,
                stic_signers.parent_type,
                stic_signers.parent_id
            FROM stic_signers
            WHERE parent_type = 'Users'
                AND parent_id = '{$user_id}'
                AND status in ('pending','signed')
                AND deleted = 0
            ORDER BY date_modified DESC
        ";
        return $query;
    }

    public static function deactivateOtherSignersForSameSignature($signerBean)
    {
        if ($signerBean->fetched_row['status'] != 'pending' || $signerBean->status != 'signed') {
            // Only proceed if the signer's status has changed from 'pending' to 'signed'
            return;
        }

        require_once 'SticInclude/Utils.php';
        $signatureBean = SticUtils::getRelatedBeanObject($signerBean, 'stic_signatures_stic_signers');
        if ($signatureBean->on_behalf_of == 1);

        // Deactivate other signers for the same Signature
        $otherSigners = $signatureBean->get_linked_beans('stic_signatures_stic_signers', 'stic_Signers', '', 0, 0, 0, " stic_signers.id <> '{$signerBean->id}' AND stic_signers.status = 'pending' AND stic_signers.on_behalf_of_id = '{$signerBean->on_behalf_of_id}'");
        foreach ($otherSigners as $otherSigner) {
            $otherSigner->status = 'unnecessary';
            $otherSigner->save();
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Deactivated signer {$otherSigner->id} for signature {$signatureBean->id} because signature was completed by $signerBean->name.");
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('SIGNATURE_NOT_NEEDED', $otherSigner->id, 'SIGNER', "{$mod_strings['LBL_SIGNATURE_COMPLETED_BY']} {$signerBean->parent_name}.");
        }
    }
}
