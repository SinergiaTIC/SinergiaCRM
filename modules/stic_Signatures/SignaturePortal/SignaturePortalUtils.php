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
 * Utility class for handling electronic signature portal operations.
 * This class manages the retrieval of signer, signature, PDF template, and source module beans,
 * and provides functionality to get HTML content for signing.
 */
class stic_SignaturePortalUtils
{
    private $signerId = '';
    private $signerBean;
    private $signatureBean;
    private $pdfTemplateBean;
    private $sourceModuleBean;

    /**
     * Constructor for stic_SignaturePortalUtils.
     * Initializes signer, signature, PDF template, and source module beans based on the provided signer ID.
     */
    public function __construct()
    {
        require_once 'SticInclude/Utils.php';

        if (isset($_REQUEST['signerId']) && !empty($_REQUEST['signerId'])) {
            $this->signerId = $_REQUEST['signerId'];
        }

        $this->signerBean = BeanFactory::getBean('stic_Signers', $this->signerId);
        $this->signatureBean = SticUtils::getRelatedBeanObject($this->signerBean, 'stic_signatures_stic_signers');
        $this->pdfTemplateBean = BeanFactory::getBean('AOS_PDF_Templates', $this->signatureBean->pdftemplate_id_c ?? '');
        $this->sourceModuleBean = BeanFactory::getBean($this->signatureBean->main_module ?? '', $this->signerBean->record_id ?? '');
    }

    /* *
     * Retrieves the beans associated with the current signer.
     *
     * @return array An associative array containing the signer, signature, PDF template, and source module beans.
     */
    public function getSignatureBeans()
    {
        return [
            'signer' => $this->signerBean,
            'signature' => $this->signatureBean,
            'pdfTemplate' => $this->pdfTemplateBean,
            'sourceModule' => $this->sourceModuleBean,
        ];
    }

    /**
     * Sends an OTP code to the signer via email, forcing a new code to be sent.
     *
     * @param object $signerBean The signer bean object.
     * @param string $method The method of sending the OTP (default is 'email').
     * @return array An associative array indicating success or failure and relevant messages.
     */
    public static function forceSendOtpToSigner($signerBean, $method = 'email', $forceSend = true)
    {
        return self::sendOtpToSigner($signerBean, $method, true);
    }

    /**
     * Sends an OTP code to the signer via email.
     * If the last sent OTP has not expired, it will not send another one unless $forceSend is true.
     *
     * @param object $signerBean The signer bean object.
     * @param string $method The method of sending the OTP (default is 'email').
     * @param bool $forceSend Whether to force sending a new OTP even if the last one hasn't expired.
     * @return array An associative array indicating success or failure and relevant messages.
     */
    public static function sendOtpToSigner($signerBean, $method = 'email', $forceSend = false)
    {

        $otpDatetime = $signerBean->db->getOne("SELECT otp_expiration FROM stic_signers WHERE id = '{$signerBean->id}'");

        // if OTP was sent and not expired yet, do not send another one
        if (!$forceSend && !empty($signerBean->otp_expiration) && $otpDatetime > date('Y-m-d H:i:s')) {
            return ['success' => false, 'message' => 'OTP code already sent and not expired yet'];
        }

        if ($method !== 'email') {
            return false; // Only email method is supported for now
        }
        $email = $signerBean->email_address;

        $maskedEmail = preg_replace('/(?<=.).(?=[^@]*?@)/', '*', $email);
        $otpCode = rand(100000, 999999);
        $signerBean->otp = $otpCode;
        $signerBean->otp_expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $signerBean->save();
        // send email
        require_once 'modules/stic_Signers/Utils.php';
        if (stic_SignersUtils::sendOTPToSign($signerBean) === true) {
            return ['success' => true, 'maskedEmail' => "{$maskedEmail}"];
        } else {
            return ['success' => false];
        }
    }

    /**
     * Retrieves the parsed HTML content for the current signer.
     *
     * @return string The HTML content to be displayed for signing, or an error message if not found.
     */
    public function getHtmlFromSigner()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $parsedText = stic_SignaturesUtils::getParsedTemplate($this->signerId);
        $html = "{$parsedText['header']}{$parsedText['converted']}{$parsedText['footer']}";
        if (!empty($html)) {
            return $html;
        } else {
            $GLOBALS['log']->error("There is no HTML content for the signer with ID: {$this->signerId}");
            return '<p class="text-red-500 border-red-500 text-center">No se encontró contenido para el firmante especificado.</p>';
        }
    }

    public static function verifyOtpCode($signerBean, $otpCode)
    {

        $expireDatetime = $signerBean->db->getOne("SELECT otp_expiration FROM stic_signers WHERE id = '{$signerBean->id}'");
        if ($signerBean->otp === $otpCode && $expireDatetime >= date('Y-m-d H:i:s')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Saves the signature data for a given signer.
     *
     * @param array $data An associative array containing 'signerId' and 'signatureData'.
     * @return array An associative array indicating success or failure and relevant messages.
     */
    public static function saveSignature($data = '')
    {
        $signerBean = BeanFactory::getBean('stic_Signers', $data['signerId'] ?? '');

        if ($signerBean && !empty($signerBean->id)) {
            $signerBean->signature_image = $data['signatureData'];
            $signerBean->status = 'signed';
            $signerBean->signature_date = gmdate("Y-m-d H:i:s");
            if (!$signerBean->save()) {
                return ['success' => false, 'message' => 'Failed to save signature data.'];
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Failed to save signature data for Signer ID: {$signerBean->id}");
            } else {
                require_once 'modules/stic_Signatures/sticGenerateSignedPdf.php';
                // Generate the signed PDF after saving the signature
                $savedFile = sticGenerateSignedPdf::generateSignedPdf('handwritten');
                $signerBean->verification_code = stic_SignaturesUtils::getVerificationCodeForSignedPdf($savedFile);
                $signerBean->save();
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signature data saved for Signer ID: {$signerBean->id}");
                require_once 'modules/stic_Signature_Log/Utils.php';
                stic_SignatureLogUtils::logSignatureAction('SIGNED_HANDWRITTEN_MODE', $signerBean->id, 'SIGNER');
                return ['success' => true, 'message' => 'Signature data saved successfully.'];
            }
        }
        return ['success' => false, 'message' => 'Error saving signature data.'];
    }

    /**
     * Sends the signed PDF document to the signer via email.
     * This function retrieves signer details, constructs an email with the signed PDF attached,
     * and sends it using SugarCRM's mailer.
     *
     * @param string $signerId The ID of the signer to whom the signed PDF should be sent.
     * @throws Exception If the signer ID is empty or the destination email address is invalid.
     * @return array An associative array indicating success or failure and a message.
     */
    public static function sendSignedPdfByEmail($signerId)
    {
        global $sugar_config, $current_user;

        $mod_strings = return_module_language($GLOBALS['current_language'], 'stic_Signatures');

        // Validate signer ID
        if (empty($signerId)) {
            throw new Exception("Signer ID cannot be empty.");
        }

        // Retrieve the signer bean
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);

        // Get the destination email address for the signer
        $destAddress = $signerBean->email_address ?? '';
        if (empty($destAddress)) {
            return [
                'success' => false,
                'message' => 'No destination email address.',
            ];
        }

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
            return [
                'success' => false,
                'message' => 'No destination email address.',
            ];
        }
        $mail->AddAddress($destAddress);

        // Set the email subject
        $subject = $mod_strings['LBL_SIGNED_PDF_EMAIL_SUBJECT'];
        $mail->Subject = $subject;

        // Construct the signed PDF URL
        // Determine the base URI for constructing URLs (to handle different server setups)
        $signURL = "{$sugar_config['site_url']}/index.php?entryPoint=sticSign&signerId={$signerId}&otp-code={$signerBean->otp}";

        // Prepare the complete HTML body of the email
        $completeHTML = "<html>
                            <head>
                                <title>{$subject}</title>
                            </head>
                            <body style=\"font-family: Arial, sans-serif; font-size: 14px; color: #333;\">
                            <h1>    {$signerBean->name}    </h1>
                            {$mod_strings['LBL_SIGNED_PDF_EMAIL_BODY']}.
                            <pre>{$signerBean->verification_code}</pre>
                            <br>
                            {$mod_strings['LBL_SIGNED_PDF_REOPEN_PORTAL']}
                            <br><a href=\"{$signURL}\" target=\"_blank\" style=\"font-size: 16px; width: auto; font-family: Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; padding: 12px 20px; background-color: #007BFF; border: 1px solid #007BFF; display: inline-block; font-weight: bold;\">
                                            {$mod_strings['LBL_SIGNER_REDIRECT_TO_PORTAL']}
                                        </a>


                            </body>
                        </html>";
        $mail->Body = from_html($completeHTML);
        $mail->isHtml(true);
        $mail->prepForOutbound();
        // Attach the signed PDF
        $pdfPath = "{$sugar_config['upload_dir']}/{$signerBean->id}_signed.pdf";
        if (file_exists($pdfPath)) {

            $atachmentName = "{$signerBean->name}.pdf";
            $mail->AddAttachment($pdfPath, $atachmentName);
        } else {
            return [
                'success' => false,
                'message' => 'Signed PDF file does not exist.',
            ];
        }
        // Attempt to send the email and log the result
        if (!$mail->Send()) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": There was an error sending the signed PDF email to {$destAddress}. Mailer Error: " . $mail->ErrorInfo);
            return [
                'success' => false,
                'message' => 'Error sending email: ' . $mail->ErrorInfo,
            ];
        } else {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signed PDF Email sent successfully to {$destAddress}.");
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('SIGNED_PDF_SENT', $signerId, 'SIGNER', $destAddress);
            return [
                'success' => true,
                'message' => 'Signed PDF email sent successfully.',
            ];
        }
    }

    /**
     * Accepts the document for a given signer in button mode.
     *
     * @param array $data An associative array containing 'signerId'.
     * @return array An associative array indicating success or failure and relevant messages.
     */
    public static function acceptDocument($data = '')
    {
        $signerBean = BeanFactory::getBean('stic_Signers', $data['signerId'] ?? '');

        if ($signerBean && !empty($signerBean->id)) {
            $signerBean->status = 'signed';
            $signerBean->signature_image = ''; // No signature image in button mode
            $signerBean->signature_date = gmdate("Y-m-d H:i:s");
            if (!$signerBean->save()) {
                return ['success' => false, 'message' => 'Failed to accept document.'];
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Failed to accept document for Signer ID: {$signerBean->id}");
            } else {
                require_once 'modules/stic_Signatures/sticGenerateSignedPdf.php';
                // Generate the signed PDF after saving the signature
                sticGenerateSignedPdf::generateSignedPdf('button');
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Document accepted for Signer ID: {$signerBean->id}");
                require_once 'modules/stic_Signature_Log/Utils.php';
                stic_SignatureLogUtils::logSignatureAction('SIGNED_BUTTON_MODE', $signerBean->id, 'SIGNER');
                return ['success' => true, 'message' => 'Document accepted successfully.'];
            }
        }
        return ['success' => false, 'message' => 'Error accepting document.'];
    }
}
