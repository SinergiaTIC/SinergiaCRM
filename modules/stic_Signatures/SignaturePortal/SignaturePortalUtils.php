<?php

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
