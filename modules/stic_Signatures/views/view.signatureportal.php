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
 * This class handles the display logic for the electronic signature portal.
 * It prepares the HTML content, manages stylesheets and JavaScript,
 * and handles basic authentication mode validation.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_SignaturePortal extends SugarView
{

    /**
     * Constructor for stic_SignaturePortal.
     * Initializes a new Smarty instance.
     */
    public function __construct()
    {
        $this->ss = new Sugar_Smarty();
    }

    /**
     * Displays the electronic signature portal.
     * This method retrieves document content, validates authentication,
     * assigns variables to the Smarty template, and renders the portal HTML.
     *
     * @return void
     */
    public function display()
    {
        global $smarty;
        global $mod_strings; // Load module-specific language strings if available
        global $app_list_strings; // General application language strings
        global $sugar_config;


        // Determine the base URI for constructing URLs (to handle different server setups) 
        $uri = str_replace('index.php', '', $_SERVER['DOCUMENT_URI']) ?? '';

        require_once 'modules/stic_Settings/Utils.php';

        $documentHtmlContent = '
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Acuerdo de Confidencialidad</h2>
        ';

        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';

        // Create an instance of the utility class
        $stic_SignaturePortalUtils = new stic_SignaturePortalUtils();

        // Get beans
        $signatureBean = $stic_SignaturePortalUtils->getSignatureBeans()['signature'];
        $signerBean = $stic_SignaturePortalUtils->getSignatureBeans()['signer'];
        // $pdfTemplateBean = $stic_SignaturePortalUtils->getSignatureBeans()['pdfTemplate'];
        // $sourceModuleBean = $stic_SignaturePortalUtils->getSignatureBeans()['sourceModule'];

        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('APP_LIST_STRINGS', $app_list_strings);

        $this->ss->assign('SIGNER_ID', $signerBean->id);

        $this->ss->assign('STATUS', $signerBean->status);

        // Assign signed PDF URL and download URL if signed
        if ($signerBean->status === 'signed') {
            $signedPdfUrl = $uri . $sugar_config['upload_dir'] . '/' . $signerBean->id . '_signed.pdf';
            $dowwnloadPdfUrl = "{$uri}/index.php?entryPoint=sticDownloadSignedPdf&signerId={$signerBean->id}";
            $this->ss->assign('SIGNED_PDF_URL', $signedPdfUrl);
            $this->ss->assign('DOWNLOAD_URL', $dowwnloadPdfUrl);
        }

        $this->ss->assign('SIGNATURE_MODE', $signatureBean->signature_mode ?? 'handwritten');

        // Get authentication mode
        $authMode = $signatureBean->auth_method ?? 'unique_link';

        $passed = false;
        $errorMsg = '';

        // Validate authentication mode
        switch ($authMode) {
            case 'unique_link':
                $passed = true;
                break;
            case 'otp':
                // Check if OTP code is provided in REQUEST and valid
                if ($stic_SignaturePortalUtils::verifyOtpCode($signerBean, $_REQUEST['otp-code'] ?? '')) {
                    $passed = true;
                } else {
                    if (isset($_REQUEST['otp-code'])) {
                        $errorMsg = 'El código OTP proporcionado no es válido. Por favor, inténtalo de nuevo.';
                        $this->ss->assign('OTP_ERROR_MSG', $errorMsg);
                    }
                    $this->ss->assign('OTP_REQUIRED', true);
                    require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
                    $signerStrings = return_module_language($GLOBALS['current_language'], 'stic_Signers');
                    // Send OTP
                    $sendResult = stic_SignaturePortalUtils::sendOtpToSigner($signerBean);
                    if (($sendResult['success'] ?? null) === true) {
                        $this->ss->assign('OTP_SENT_SUCCESS', $signerStrings['LBL_OTP_SENT_SUCCESS']);
                        $this->ss->assign('OTP_MASKED_EMAIL', $sendResult['maskedEmail']);
                    } else {
                        $this->ss->assign('OTP_SENT_ERROR', $signerStrings['LBL_OTP_SENT_ERROR']);
                    }

                }
                break;
            default:
                $errorMsg = 'El modo de autenticación no es válido.';
                $this->ss->assign('ERROR_MSG', $errorMsg);
                break;
        }

        // If authentication passed, get document HTML content
        if ($passed === true) {
            $documentHtmlContent = $stic_SignaturePortalUtils->getHtmlFromSigner();
            $this->ss->assign('SHOW_PORTAL', true);
            $this->ss->assign('SIGNER_NAME', $signerBean->parent_name);
            
            $this->ss->assign('SIGNER_VERIFICATION_CODE', $signerBean->verification_code);
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('OPEN_PORTAL_BEFORE_SIGN', $signerBean->id, 'SIGNER');

            // Fetch and assign logs related to the signer
            $signerLog = stic_SignatureLogUtils::getSignatureLogActions($signerBean->id, 'SIGNER', ['OPEN_PORTAL_BEFORE_SIGN']);
            if (!empty($signerLog)) {
                foreach ($signerLog as $index => $logEntry) {
                    $signerLog[$index]['action'] = $app_list_strings['stic_signature_log_actions'][$logEntry['action']] ?? $logEntry['action'];
                    $signerLog[$index]['date'] = (new DateTime($logEntry['date'], new DateTimeZone('UTC')))->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('d/m/Y H:i:s');
                }
                $this->ss->assign('SIGNER_LOG', $signerLog);
                $this->ss->assign('SHOW_LOGS', true);
            }
        }
        // Customizations for header
        $color = stic_SettingsUtils::getSetting('GENERAL_CUSTOM_THEME_COLOR') ?? '#b5bc31';
        $nameTitle = stic_SettingsUtils::getSetting('GENERAL_ORGANIZATION_NAME') ?? 'SinergiaCRM';
        $this->ss->assign('HEADER_COLOR', $color);
        $this->ss->assign('LOGO_URL', "{$uri}custom/themes/default/images/company_logo.png");
        $this->ss->assign('ORGANIZATION_NAME', $nameTitle);

        // Assign variables to Smarty
        $this->ss->assign('DOCUMENT_HTML_CONTENT', $documentHtmlContent);
        $this->ss->assign('CURRENT_DATE_TIME', date('d/m/Y H:i:s'));
        $this->ss->assign('CURRENT_DATE_MINUS_5_MINS', date('d/m/Y H:i:s', strtotime('-5 minutes')));

        // Include CSS and JS
        // It's best to load assets this way for proper SuiteCRM management.
        // Ensure files exist in the correct directories.
        $this->ss->assign('BOOTSTRAP_CSS', '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">');
        $this->ss->assign('BOOTSTRAP_JS', '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>');
        $this->ss->assign('BOOTSTRAP_ICONS', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
');

        $this->ss->assign('STYLESHEETS', '<link rel="stylesheet" href="modules/stic_Signatures/SignaturePortal/SignaturePortal.css">');
        $this->ss->assign('JAVASCRIPT', '<script src="modules/stic_Signatures/SignaturePortal/SignaturePortal.js"></script>');
        $this->ss->assign('JAVASCRIPT_OTP', '<script src="modules/stic_Signatures/SignaturePortal/SignaturePortalOtp.js"></script>');

        // Load the Smarty template
        // The path should be relative to the SuiteCRM base directory
        echo $this->ss->fetch('modules/stic_Signatures/SignaturePortal/SignaturePortal.tpl');
    }
}
