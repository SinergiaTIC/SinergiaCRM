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

        $otpCode = rand(100000, 999999);
        $signerBean->otp = $otpCode;
        $signerBean->otp_expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $signerBean->save();
        // send email
        require_once 'modules/stic_Signers/Utils.php';

        if ($method === 'email') {
            $result = stic_SignersUtils::sendOtpEmailToSigner($signerBean, $otpCode);
        } elseif ($method === 'phone') {
            $result = stic_SignersUtils::sendOtpPhoneMessageToSigner($signerBean, $otpCode);
        }

        if ($result === true) {
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

    /**
     * Verifies the provided OTP code against the signer's stored OTP and expiration.
     *
     * @param object $signerBean The signer bean object.
     * @param string $otpCode The OTP code to verify.
     * @return bool True if the OTP code is valid and not expired, false otherwise.
     */
    public static function verifyOtpCode($signerBean, $otpCode)
    {
        // Check if the signer has already been authenticated in the session
        if ($_SESSION['stic_authenticated_signers'][$signerBean->id] ?? false === true) {
            return true;
        }

        $expireDatetime = $signerBean->db->getOne("SELECT otp_expiration FROM stic_signers WHERE id = '{$signerBean->id}'");
        if ($signerBean->otp === $otpCode && $expireDatetime >= date('Y-m-d H:i:s')) {
            $_SESSION['stic_authenticated_signers'][$signerBean->id] = true;
            // remove OTP info in database after successful validation
            $signerBean->otp = '';
            $signerBean->otp_expiration = '';
            $signerBean->save();
            return true;
        } else {
            $_SESSION['stic_authenticated_signers'][$signerBean->id] = false;
            return false;
        }
    }

    /**
     * Verifies the provided field value against the signer's expected value based on the authentication method.
     *
     * @param object $signerBean The signer bean object.
     * @param string $fieldValue The field value to verify.
     * @return bool True if the field value matches the expected value, false otherwise.
     */
    public static function verifyFieldValidation($signerBean, $fieldValue)
    {

        // Check if the signer has already been authenticated in the session
        if ($_SESSION['stic_authenticated_signers'][$signerBean->id] === true) {
            return true;
        }

        $fieldValue = strtoupper(trim($fieldValue));
        $signatureBean = SticUtils::getRelatedBeanObject($signerBean, 'stic_signatures_stic_signers');
        // Get the related Users or Contacts bean based on signer_path
        $userOrContactsModule = explode(':', $signatureBean->signer_path)[0];
        if ($userOrContactsModule === 'Users') {
            $userOrContactsBean = BeanFactory::getBean('Users', $signerBean->parent_id);
        } else {
            $userOrContactsBean = BeanFactory::getBean('Contacts', $signerBean->parent_id);
        }

        if (!$userOrContactsBean || empty($userOrContactsBean->id)) {
            return false;
        }

        // Determine the expected value based on the authentication method
        $expectedValue = '';
        switch ($signatureBean->auth_method) {
            case 'phone':
                $expectedValue = $signerBean->phone;
                break;
            case 'identification_number':
                // remove spaces and dashes for comparison and set in uppercase
                $expectedValue = strtoupper(str_replace([' ', '-'], '', $userOrContactsBean->stic_identification_number_c));
                break;
            case 'birthdate':
                // Format the birthdate to match the expected format (DD/MM/YYYY)
                $expectedValue = date('d/m/Y', strtotime($userOrContactsBean->birthdate));
                break;
            default:
                break;
        }

        if ($expectedValue === $fieldValue) {
            $_SESSION['stic_authenticated_signers'][$signerBean->id] = true;
            return true;
        } else {
            $_SESSION['stic_authenticated_signers'][$signerBean->id] = false;
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

        if ($signerBean && !empty($signerBean->id) && $signerBean->status === 'pending') {
            $currentDate = gmdate("Y-m-d H:i:s");
            $signerBean->signature_image = $data['signatureData'];
            $signerBean->status = 'signed';
            $signerBean->signature_date = $currentDate;
            $signerBean->save();

            // Reopen the signer bean to verify saved data
            $signerBean = BeanFactory::getBean('stic_Signers', $data['signerId'] ?? '');
            if ($signerBean->status != 'signed' || empty($signerBean->signature_image)) {
                return ['success' => false, 'message' => 'Failed to save signature data.'];
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Failed to save signature data for Signer ID: {$signerBean->id}");
            } else {
                require_once 'modules/stic_Signature_Log/Utils.php';
                stic_SignatureLogUtils::logSignatureAction('SIGNED_HANDWRITTEN_MODE', $signerBean->id, 'SIGNER');

                require_once 'modules/stic_Signatures/sticGenerateSignedPdf.php';
                // Generate the signed PDF after saving the signature
                $savedFile = sticGenerateSignedPdf::generateSignaturePdf('handwritten');
                $signerBean->verification_code = stic_SignaturesUtils::getVerificationCodeForSignedPdf($savedFile);
                $signerBean->save();
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signature data saved for Signer ID: {$signerBean->id}");
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

        require_once 'SticInclude/Utils.php';

        $mod_strings = return_module_language($GLOBALS['current_language'], 'stic_Signatures');

        // Validate signer ID
        if (empty($signerId)) {
            throw new Exception("Signer ID cannot be empty.");
        }

        // Retrieve the signer bean
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);
        if (empty($signerBean) || empty($signerBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signer not found for ID: {$signerId}");
            return [
                'success' => false,
                'message' => 'Signer not found.',
            ];
        }

        $signatureBean = SticUtils::getRelatedBeanObject($signerBean, 'stic_signatures_stic_signers');
        if (empty($signatureBean) || empty($signatureBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Signature not found for Signer ID: {$signerId}");
            return [
                'success' => false,
                'message' => 'Signature not found for signer.',
            ];
        }

        $userOrContactsBean = BeanFactory::getBean($signerBean->parent_type, $signerBean->parent_id);
        if (empty($userOrContactsBean) || empty($userOrContactsBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Related user/contact not found for Signer ID: {$signerId}");
            return [
                'success' => false,
                'message' => 'Related user/contact not found for signer.',
            ];
        }

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

        // Parse the email template using all relevant beans
        $templateId = empty($signatureBean->emailtemplatesenddocument_id_c) ? '000005f1-2e4e-3b11-051f-68e3c9e70331' : $signatureBean->emailtemplatesenddocument_id_c;
        $parsedTemplate = SticUtils::parseEmailTemplate($templateId, [
            $signerBean,
            $signatureBean,
            $userOrContactsBean,
        ]);

        // Set the email subject
        $subject = $parsedTemplate['subject'];
        $mail->Subject = $subject;

        // Generate the email body using the template
        $completeHTML = $parsedTemplate['body_html'];

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

        if ($signerBean && !empty($signerBean->id) && $signerBean->status === 'pending') {
            $signerBean->status = 'signed';
            $signerBean->signature_image = ''; // No signature image in button mode
            $signerBean->signature_date = gmdate("Y-m-d H:i:s");
            $signerBean->save();
            if ($signerBean->status != 'signed' || !empty($signerBean->signature_image)) {
                return ['success' => false, 'message' => 'Failed to accept document.'];
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Failed to accept document for Signer ID: {$signerBean->id}");
            } else {
                require_once 'modules/stic_Signature_Log/Utils.php';
                stic_SignatureLogUtils::logSignatureAction('SIGNED_BUTTON_MODE', $signerBean->id, 'SIGNER');

                // Generate the signed PDF after saving the signature
                require_once 'modules/stic_Signatures/sticGenerateSignedPdf.php';
                $savedFile = sticGenerateSignedPdf::generateSignaturePdf('button');

                $signerBean->verification_code = stic_SignaturesUtils::getVerificationCodeForSignedPdf($savedFile);
                $signerBean->save();

                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . " Document accepted for Signer ID: {$signerBean->id}");
                return ['success' => true, 'message' => 'Document accepted successfully.'];
            }
        }
        return ['success' => false, 'message' => 'Error accepting document.'];
    }
}
