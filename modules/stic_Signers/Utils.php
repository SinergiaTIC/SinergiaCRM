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
            SugarApplication::appendErrorMessage("<p class='msg-error'>" . $mod_strings['LBL_SIGNER_EMAIL_ERROR'] . ".</p>");
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": There was an error sending the email to {$destAddress}. Mailer Error: " . $mail->ErrorInfo);
        } else {
            SugarApplication::appendSuccessMessage("<p class='msg-success'>" . $mod_strings['LBL_SIGNER_EMAIL_SUCCESS'] . ".</p>");
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Email sent successfully to {$destAddress}.");
        }

        // Redirect back to the signer's detail view
        SugarApplication::redirect('index.php?module=stic_Signers&action=DetailView&record=' . $signerId);
    }

    public static function sendOTPToSign($signerBean)
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
                            
                            <h2>El código de verificación es {$signerBean->verification_code}</h2>
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
            return true;
        }

        
    }
}