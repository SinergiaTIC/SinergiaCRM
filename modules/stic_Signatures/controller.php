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
 * Controller for the stic_Signatures module.
 * This class handles various AJAX actions related to signature templates,
 * previewing documents, retrieving module relationships, and fetching signers.
 */
class stic_SignaturesController extends SugarController
{
    /**
     * Action to parse a signature template and return its HTML content.
     * This action is typically used for debugging or internal testing purposes
     * with a hardcoded signer ID.
     *
     * @return void
     */
    public function action_parseTemplate()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $signerId = '00000b06-3aa0-2b29-db5c-6879efaf8c9d'; // Hardcoded signer ID for testing
        $html = stic_SignaturesUtils::getParsedTemplate($signerId);
        die($html);
    }

    /**
     * Action to get a preview of a document for a given signer ID.
     * It retrieves the parsed HTML template and echoes it.
     *
     * @return void
     */
    public function action_getPreview()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $signerId = $_REQUEST['signerId'] ?? '';
        $html = stic_SignaturesUtils::getParsedTemplate($signerId);
        if (!empty($html)) {
            echo $html;
        } else {
            echo '<p class="text-red-500 border-red-500 text-center">No se encontró contenido para el firmante especificado.</p>';
        }
        die();
    }

    /**
     * Action to retrieve module relationships.
     * It uses the `getModuleRelationships` utility function and outputs the result
     * for a specified module and format (raw or JSON).
     *
     * @return void
     */
    public function action_getRelationships()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        var_dump(stic_SignaturesUtils::getModuleRelationships($_REQUEST['getmodule'], $_REQUEST['format'] ?? 'raw'));
        die();
    }

    /**
     * Action to get a list of signers associated with a specific signature process.
     * This action includes commented-out examples for different signature and module IDs,
     * demonstrating its usage.
     *
     * @return void
     */
    public function action_getSignatureSigners()
    {
        require_once 'modules/stic_Signatures/Utils.php';

        // Example signature and main module IDs (commented out for production use)
        // $signatureId = '00000167-b5f5-f931-0432-6875fd393f8d';
        // $mainModuleIds = ['7932e3c3-c5fc-8942-95f5-63106d62940f','176e0992-a61b-26cf-c58d-63106ba8c3b1','d9db7680-1a78-4b4a-70ba-63106d2771c2'];

        // $signatureIds = '00000616-100c-bcdb-4020-68763261a51a';
        // $mainModuleId = ['7932e3c3-c5fc-8942-95f5-63106d62940f','176e0992-a61b-26cf-c58d-63106ba8c3b1','d9db7680-1a78-4b4a-70ba-63106d2771c2'];

        // Sessions example
        $signatureId = '0000070f-664a-4c6d-14c3-68763b7509c5';
        $mainModuleIds = '8d89183c-58c8-4aa0-2e0f-63106cc1aa5f';

        // Meetings example
        $signatureId = '000003fb-a3c6-f802-becd-68765156ce82';
        $mainModuleIds = '00000978-be6b-07b9-6f16-6847d3a2d799';

        $signerPathList = stic_SignaturesUtils::getSignatureSigners($signatureId, $mainModuleIds);

        var_dump($signerPathList);
        die();
    }

    /**
     * Action to resend an OTP code to the signer via email.
     * This action forces the sending of a new OTP code, even if the previous one hasn't expired.
     *
     * @return void
     */
    public function action_resendOtpCode()
    {
        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
        $signerId = $_REQUEST['signerId'] ?? '';
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);
        $result = stic_SignaturePortalUtils::forceSendOtpToSigner($signerBean, 'email', true);
        echo json_encode($result);
        die();
    }

    /**
     * Action to save the signature data provided by the signer in handwritten mode.
     * It checks for the presence of necessary data in the request and calls
     * the utility function to save the signature.
     *
     * @return void
     */
    public function action_saveSignature()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        if (!empty($_REQUEST['signerId']) && !empty($_REQUEST['signatureData'])) {
            $result = stic_SignaturesUtils::saveSignature($_REQUEST);
        } else {
            $result = [
                'success' => false,
                'message' => 'No data provided',
            ];
        }
        echo json_encode($result);
        die();
    }

    /**
     * Action to accept the document by the signer in button mode.
     * It checks for the presence of the signer ID in the request and calls
     * the utility function to process the acceptance.
     *
     * @return void
     */
    public function action_acceptDocument()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        if (!empty($_REQUEST['signerId'])) {
            $result = stic_SignaturesUtils::acceptDocument($_REQUEST);
        } else {
            $result = [
                'success' => false,
                'message' => 'No data provided',
            ];
        }
        echo json_encode($result);
        die();
    }

    public function action_showPortal()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $id=$GLOBALS['db']->getOne("SELECT id FROM stic_signers where deleted=0 LIMIT 1");
        SugarApplication::redirect("index.php?entryPoint=sticSign&signerId=$id");
        die();
    }

 
    
}
