<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_SignaturePortal extends SugarView
{
    public function __construct()
    {
        $this->ss = new Sugar_Smarty();
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $smarty;
        global $mod_strings; // Cargar las cadenas de idioma de tu módulo si las tienes
        global $app_strings; // Cadenas de idioma de la aplicación general

        $documentHtmlContent = '
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Acuerdo de Confidencialidad</h2>
        ';

        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
        // create an instance of the utility class
        $stic_SignaturePortalUtils = new stic_SignaturePortalUtils();

        // Get signatureBean
        $signatureBean = $stic_SignaturePortalUtils->signatureBean ?? null;

        // Get authentication mode
        $authMode=$signatureBean->auth_method ?? 'unique_link';

       
        switch ($authMode) {
            case 'unique_link':
                $passed = true;
                break;

            default:
                $passed = false;
                $errorMsg = 'El modo de autenticación no es válido.';
                $this->ss->assign('ERROR_MSG', $errorMsg);
            }

        
        
        
        // Get parsed template HTML content
        if($passed == true)
        {
            $documentHtmlContent = $stic_SignaturePortalUtils->getHtmlFromSigner();
        }

        
        
        






        // Asignar variables a Smarty
        $this->ss->assign('DOCUMENT_HTML_CONTENT', $documentHtmlContent);
        $this->ss->assign('CURRENT_DATE_TIME', date('d/m/Y H:i:s'));
        $this->ss->assign('CURRENT_DATE_MINUS_5_MINS', date('d/m/Y H:i:s', strtotime('-5 minutes')));

        // Incluir CSS y JS
        // Es mejor cargar los assets de esta manera para que SuiteCRM los gestione correctamente.
        // Asegúrate de que los archivos existan en los directorios correctos.
        $this->ss->assign('STYLESHEETS', '<link rel="stylesheet" href="modules/stic_Signatures/SignaturePortal/SignaturePortal.css">');
        $this->ss->assign('JAVASCRIPT', '<script src="modules/stic_Signatures/SignaturePortal/SignaturePortal.js"></script>');
        $this->ss->assign('TAILWIND_SCRIPT', '
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: [\'Inter\', \'sans-serif\'],
                            },
                        }
                    }
                }
            </script>
        ');

        // Cargar la plantilla Smarty
        // La ruta debe ser relativa al directorio base de SuiteCRM
        echo $this->ss->fetch('modules/stic_Signatures/SignaturePortal/SignaturePortal.html');
    }
}
