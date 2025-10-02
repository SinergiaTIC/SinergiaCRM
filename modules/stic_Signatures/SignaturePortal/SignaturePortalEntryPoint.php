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
 * Entry point for handling signature-related actions and displaying the signature portal.
 * This script processes various actions such as saving signatures, resending OTP codes,
 * sending signed PDFs via email, accepting documents, and downloading signed PDFs.
 * If no specific action is provided, it displays the signature portal view.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

if (empty($_REQUEST['signerId'])
    || !is_string($_REQUEST['signerId'])

) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Invalid or missing signerId');
    sugar_die('Not A Valid Entry Point');
}

global $sugar_config;

if (!empty($_REQUEST['signatureAction'])) {

    require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';

    // Handle different actions based on the 'signatureAction' parameter
    switch ($_REQUEST['signatureAction']) {
        
        case 'saveSignature':
        // Save the signature data for the specified signer    
            if (!empty($_REQUEST['signerId']) && !empty($_REQUEST['signatureData'])) {
                $result = stic_SignaturePortalUtils::saveSignature($_REQUEST);
            } else {
                $result = [
                    'success' => false,
                    'message' => 'No data provided',
                ];
            }
            echo json_encode($result);
            die();
            break;

        case 'resendOtpCode':
            // Resend the OTP code to the signer
            $signerId = $_REQUEST['signerId'] ?? '';
            $signerBean = BeanFactory::getBean('stic_Signers', $signerId);
            $result = stic_SignaturePortalUtils::forceSendOtpToSigner($signerBean, 'email', true);
            echo json_encode($result);
            die();
            break;
        case 'sendSignedPdfByEmail':
            // Send the signed PDF to the signer via email
            $signerId = $_REQUEST['signerId'] ?? '';
            $result = stic_SignaturePortalUtils::sendSignedPdfByEmail($signerId);
            echo json_encode($result);
            die();
            break;
        case 'acceptDocument':
            // Accept the document 
            if (!empty($_REQUEST['signerId'])) {
                $result = stic_SignaturePortalUtils::acceptDocument($_REQUEST);
            } else {
                $result = [
                    'success' => false,
                    'message' => 'No data provided',
                ];
            }
            echo json_encode($result);
            die();
            break;
        case 'downloadSignedPdf':
            // Download the signed PDF for the specified signer
            $signerId = $_REQUEST['signerId'];
            $signerBean = BeanFactory::getBean('stic_Signers', $signerId);
            if (!$signerBean) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Signer with ID $signerId not found");
                sugar_die('Signer not found');
            }

            // Construct the filename using the signer's ID
            $fileName = "{$signerId}_signed.pdf";
            $filePath = $sugar_config['upload_dir'] . $fileName;

            // Check if the file exists and is readable
            if (!file_exists($filePath) || !is_readable($filePath)) {
                sugar_die('File not found or is not accessible.');
            }

            require_once 'modules/stic_Signature_Log/Utils.php';
            stic_SignatureLogUtils::logSignatureAction('SIGNED_PDF_DOWNLOADED', $signerBean->id, 'SIGNER', "Signed PDF downloaded for signer {$signerBean->name} (ID: {$signerBean->name})");

            // Set headers for file download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $signerBean->name . '.pdf"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            // Clear output buffer and read the file
            ob_clean();
            flush();
            readfile($filePath);
            exit;
            break;
        default:
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Unknown action ' . $_REQUEST['action']);
            sugar_die('Unknown action');
    }
} else {
    // If no action is specified, display the signature portal view

    // Include necessary files to load the view class
    require_once 'include/MVC/View/SugarView.php';
    require_once 'modules/stic_Signatures/views/view.signatureportal.php'; // Ensure this path is correct

    // Initialize an instance of the view class
    $view = new stic_SignaturePortal();

    // Call the display() method of the view
    $view->display();
}
// Terminate script execution to ensure no additional SuiteCRM framework elements are loaded
sugar_die('');
