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

class stic_SignaturePortalUtils
{

    private $signerId = '';
    private $signerBean, $signatureBean, $pdfTemplateBean, $sourceModuleBean;

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

    public function getHtmlFromSigner()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $html = stic_SignaturesUtils::getParsedTemplate($this->signerId);
        if (!empty($html)) {
            return $html;

        } else {
            $GLOBALS['log']->error("There is no HTML content for the signer with ID: {$this->signerId}");
            $html = '<p class="text-red-500 border-red-500 text-center">No se encontró contenido para el firmante especificado.</p>';
        }

    }

}
