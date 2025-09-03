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
     * Retrieves the parsed HTML content for the current signer.
     *
     * @return string The HTML content to be displayed for signing, or an error message if not found.
     */
    public function getHtmlFromSigner()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $html = stic_SignaturesUtils::getParsedTemplate($this->signerId);
        if (!empty($html)) {
            return $html;
        } else {
            $GLOBALS['log']->error("There is no HTML content for the signer with ID: {$this->signerId}");
            return '<p class="text-red-500 border-red-500 text-center">No se encontró contenido para el firmante especificado.</p>';
        }
    }
}