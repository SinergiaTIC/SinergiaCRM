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
        global $app_list_strings, $app_strings, $sugar_config;

        // Determine the base URI for constructing URLs (to handle different server setups)
        // $uri = str_replace('index.php', '', $_SERVER['DOCUMENT_URI']) ?? '';
        $uri = rtrim($sugar_config['site_url'], '/') . '/';

        require_once 'modules/stic_Settings/Utils.php';
        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
        require_once 'modules/stic_Signers/Utils.php';

        $documentHtmlContent = '';

        // Create an instance of the utility class
        $stic_SignaturePortalUtils = new stic_SignaturePortalUtils();

        // Get beans
        $signatureBean = $stic_SignaturePortalUtils->getSignatureBeans()['signature'];
        $signerBean = $stic_SignaturePortalUtils->getSignatureBeans()['signer'];
        $mod_strings = return_module_language($GLOBALS['current_language'], 'stic_Signatures');
        $this->ss->assign('MODS', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('APP_LIST_STRINGS', $app_list_strings);

        $this->ss->assign('SIGNER_ID', $signerBean->id);

        $this->ss->assign('STATUS', $signerBean->status);
        $this->ss->assign('SIGNATURE_STATUS', $signatureBean->status);

        // Assign signed PDF URL and download URL if signed
        if ($signerBean->status === 'signed') {
            $signedPdfUrl = $uri . $sugar_config['upload_dir'] . '/' . $signerBean->id . '_signed.pdf';
            $dowwnloadPdfUrl = "{$uri}/index.php?entryPoint=sticSign&signatureAction=downloadSignedPdf&signerId={$signerBean->id}";
            $this->ss->assign('SIGNED_PDF_URL', $signedPdfUrl);
            $this->ss->assign('DOWNLOAD_URL', $dowwnloadPdfUrl);
        }

        if ($signerBean->status === 'unnecessary') {
            $this->ss->assign('UNNECESSARY_TEXT', true);
        }

        $this->ss->assign('SIGNATURE_MODE', $signatureBean->signature_mode ?? 'handwritten');

        // Get authentication mode
        $authMode = $signatureBean->auth_method ?? 'unique_link';

        $this->ss->assign('AUTH_MODE', $authMode);
        $this->ss->assign('NO_EMAIL_ALERT', empty($signerBean->email_address) ? "<i class='bi bi-exclamation-diamond' style='color:red;' title='{$mod_strings['LBL_PORTAL_OTP_NO_EMAIL_AVAILABLE']}'></i>" : '');
        $this->ss->assign('NO_PHONE_ALERT', empty($signerBean->phone) ? "<i class='bi bi-exclamation-diamond' style='color:red;' title='{$mod_strings['LBL_PORTAL_OTP_NO_PHONE_AVAILABLE']}'></i>" : '');

        $passed = false;
        $errorMsg = '';

        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
        $signerStrings = return_module_language($GLOBALS['current_language'], 'stic_Signers');

        global $timedate, $current_user;
        $user = BeanFactory::getBean('Users', '1');

        // Check if signature is expired
        $isExpired = stic_SignersUtils::checkExpiredStatus($signatureBean) && $signerBean->status === 'pending' ? true : false;
        $this->ss->assign('IS_EXPIRED', $isExpired);
        if ($isExpired === true) {
            $expirationDate = $timedate->to_display_date_time($signatureBean->fetched_row['expiration_date'], true, true, $user);
            $expirationMsg = "{$mod_strings['LBL_PORTAL_SIGNATURE_EXPIRED_MESSAGE']} {$expirationDate}";
            $this->ss->assign('EXPIRATION_MSG', $expirationMsg);

        }

        // Check if signature is activated
        $isActivated = stic_SignersUtils::checkActivatedStatus($signatureBean);
        $this->ss->assign('IS_ACTIVATED', $isActivated);
        if ($isActivated === false) {
            $activationDate = $timedate->to_display_date_time($signatureBean->fetched_row['activation_date'], true, true, $user);
            $activationMsg = "{$mod_strings['LBL_PORTAL_SIGNATURE_NOT_ACTIVATED_MESSAGE']} {$activationDate}";
            $this->ss->assign('ACTIVATION_MSG', $activationMsg);
        }

        $isClosed = in_array($signatureBean->status, ['open', 'permanent']) ? false : true;
        $this->ss->assign('IS_CLOSED', $isClosed);
        if ($isClosed === true) {
            $this->ss->assign('CLOSED_MSG', $mod_strings['LBL_PORTAL_SIGNATURE_CLOSED_MESSAGE']);
        }


        // Proceed with authentication only if not expired and activated
        if ($isExpired === false && $isActivated === true) {
            // Validate authentication mode
            switch ($authMode) {
                case 'unique_link':
                    $passed = true;
                    break;
                case 'otp':
                case 'otp_email':
                case 'otp_phone_message':
                    $this->ss->assign('OTP_SENT', ($_SESSION['otp_signed_sent'][$signerBean->id] ?? false) ? true : false);
                    $maskedEmail = preg_replace('/(?<=.).(?=[^@]*?@)/', '*', $signerBean->email_address);
                    $this->ss->assign('OTP_MASKED_EMAIL', $maskedEmail);
                    $maskedPhone = preg_replace('/.(?=.{2})/', '*', $signerBean->phone);
                    $this->ss->assign('OTP_MASKED_PHONE', $maskedPhone);

                    // Check if OTP code is provided in REQUEST and valid
                    if ($stic_SignaturePortalUtils::verifyOtpCode($signerBean, $_REQUEST['otp-code'] ?? '')) {
                        $passed = true;
                    } else {
                        if (isset($_REQUEST['otp-code'])) {
                            $errorMsg = $mod_strings['LBL_PORTAL_INVALID_OTP_CODE'];
                            $this->ss->assign('OTP_ERROR_MSG', $errorMsg);
                        }
                        $this->ss->assign('OTP_REQUIRED', ($isExpired === false && $isActivated === true) ? true : false);

                    }
                    break;
                case 'phone':
                case 'identification_number':
                case 'birthdate':
                    $this->ss->assign('FIELD_VALIDATION_REQUIRED', true);
                    if (isset($_POST['validation_field_value']) && $stic_SignaturePortalUtils::verifyFieldValidation($signerBean, $_POST['validation_field_value'])) {
                        $passed = true;
                    } else {
                        if (isset($_POST['validation_field_value'])) {
                            $errorMsg = $mod_strings['LBL_PORTAL_INVALID_FIELD_VALUE'];
                            $this->ss->assign('FIELD_ERROR_MSG', $errorMsg);
                            $this->ss->assign('PREVIOUS_FIELD_VALUE', $_POST['validation_field_value']);
                        }
                        $this->ss->assign('FIELD_VALIDATION_LABEL', $mod_strings['LBL_PORTAL_FIELD_VALIDATION_LABEL_' . strtoupper($signatureBean->auth_method)]);
                        $this->ss->assign('FIELD_VALIDATION_LABEL_FORMAT', $mod_strings['LBL_PORTAL_FIELD_VALIDATION_LABEL_FORMAT_' . strtoupper($signatureBean->auth_method)]);
                        $this->ss->assign('FIELD_REQUIRED', true);

                        if ($signatureBean->auth_method === 'birthdate') {
                            $this->ss->assign('FIELD_VALIDATION_REGEXP', '^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/[0-9]{4}$');
                        } elseif ($signatureBean->auth_method === 'identification_number') {
                            $this->ss->assign('FIELD_VALIDATION_REGEXP', '^[A-Za-z0-9]*$');
                        } elseif ($signatureBean->auth_method === 'phone') {
                            $this->ss->assign('FIELD_VALIDATION_REGEXP', '[0-9]{9}');
                        }
                    }
                    break;

                default:
                    $errorMsg = $mod_strings['LBL_PORTAL_UNKNOWN_AUTHENTICATION_MODE'];
                    $this->ss->assign('ERROR_MSG', $errorMsg);
                    break;
            }
        }

        // If authentication passed, get document HTML content
        if ($passed === true) {
            $documentHtmlContent = $stic_SignaturePortalUtils->getHtmlFromSigner();
            $this->ss->assign('SHOW_PORTAL', true);
            $this->ss->assign('SIGNER_NAME', $signerBean->parent_name);

            $this->ss->assign('SIGNER_VERIFICATION_CODE', $signerBean->verification_code);

            // Log the portal opening action
            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('OPEN_PORTAL_BEFORE_SIGN', $signerBean->id, 'SIGNER');

            // Show logs only if signature is not closed or if signer has signed
            if (
                (
                    $isClosed === false
                    || ($isClosed === true && $signerBean->status === 'signed')
                )
            ) {
                // Retrieve and format signer logs
                $signerLog = stic_SignatureLogUtils::getSignatureLogActions($signerBean->id, 'SIGNER', ['OPEN_PORTAL_BEFORE_SIGN']);
                if (!empty($signerLog)) {
                    foreach ($signerLog as $index => $logEntry) {
                        $signerLog[$index]['action'] = $app_list_strings['stic_signature_log_actions'][$logEntry['action']] ?? $logEntry['action'];
                        $signerLog[$index]['date'] = (new DateTime($logEntry['date'], new DateTimeZone('UTC')))->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('d/m/Y H:i:s');
                    }
                    $this->ss->assign('SHOW_LOGS', true);
                    $this->ss->assign('SIGNER_LOG', $signerLog);
                }
            }
        }        // Customizations for header
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
        $this->ss->assign('JAVASCRIPT_VALIDATION', '<script src="modules/stic_Signatures/SignaturePortal/SignaturePortalValidation.js"></script>');

        // Load the Smarty template
        // The path should be relative to the SuiteCRM base directory
        echo $this->ss->fetch('modules/stic_Signatures/SignaturePortal/SignaturePortal.tpl');
    }
}
