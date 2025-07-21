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

class stic_SignersUtils
{

public static function sendToSign($signerId)
    {
        global $sugar_config, $current_user, $mod_strings;
        // Logic to send the signer to sign
        // This is a placeholder for the actual implementation
        // You can add your logic here to handle the sending process
        if (empty($signerId)) {
            throw new Exception("Signer ID cannot be empty.");
        }

        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);

        $destAddress = $signerBean->email_address ?? '';

        // Prepare mail
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
        if (!$destAddress) {
            echo json_encode(false);
            die();
        }
        $mail->AddAddress($destAddress);

        // Set the subject
        $subject = $mod_strings['LBL_SIGNER_EMAIL_SUBJECT'];
        $mail->Subject = $subject;
        $signURL = "{$sugar_config['site_url']}/index.php?entryPoint=sticSign&signerId={$signerId}";
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
                            
                            <p style=\"margin-top: 20px; font-size: 12px; color: #777;\">Si tienes problemas para acceder al enlace, cópialo y pégalo en tu navegador: <br> <a href=\"{$signURL}\" target=\"_blank\" style=\"color: #007BFF; text-decoration: underline;\">{$signURL}</a></p>

                            </body>
                        </html>";
        $mail->Body = from_html($completeHTML);
        $mail->isHtml(true);
        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ":  There was an error sending the email.");
            SugarApplication::appendErrorMessage("<p class='msg-error'>" . $mod_strings['LBL_SIGNER_EMAIL_ERROR'] . ".</p>");
            
        } else{
            SugarApplication::appendSuccessMessage("<p class='msg-success'><strong>{$okCounter}</strong> " . $mod_strings['LBL_SIGNER_EMAIL_SUCCESS'] . ".</p>");
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Email sent successfully to {$destAddress}.");

        }
        SugarApplication::redirect('index.php?module=stic_Signers&action=DetailView&record=' . $signerId );
        

    }




}