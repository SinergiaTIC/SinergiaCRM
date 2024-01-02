<?php

require_once 'modules/stic_Web_Forms/Assistant/Include/views/view.stepdownload.php';

class ViewDonationstepdownload extends stic_Web_FormsViewStepDownload
{
    protected $downloadFileName = 'webformtodonation.html'; // Name of the file to download
    protected $downloadLabel = 'LBL_WEBFORMS_DOWNLOAD_LABEL'; // Label of the file to download

    /**
     * Includes the javascript code necessary for the operation of the form
     * @return String
     */
    public function prepareJS()
    {
        require_once "modules/stic_Settings/Utils.php";

        if (stic_SettingsUtils::getSetting('GENERAL_IBAN_VALIDATION') == '1') {
            $fileToIncludeInForm = 'WebFormPC.js';
        } else {
            $fileToIncludeInForm = 'WebFormPCInt.js';
        }

        // Generate the necessary javascript code
        $jsForm = parent::prepareJS();
        ob_start();
        require_once "{$this->javascriptDir}/{$fileToIncludeInForm}";
        require_once "modules/stic_Web_Forms/Assistant/Donation/javascript/ExtendedWebFormPC.js";
        $jsForm .= ob_get_contents();
        ob_end_clean();

        return $jsForm;
    }

    /**
     * It includes the necessary language variables
     * @return Array
     */
    public function prepareLangVars()
    {
        $langVars = parent::prepareLangVars();
        $langVars['stic_Payment_Commitments_LBL_IBAN_NOT_VALID'] = translate('LBL_IBAN_NOT_VALID', 'stic_Web_Forms');
        $langVars['stic_Payment_Commitments_LBL_PERIODICITY_PUNCTUAL'] = translate('LBL_PERIODICITY_PUNCTUAL', 'stic_Web_Forms');
        $langVars['stic_Payment_Commitments_LBL_PAYMENT_TYPE_PUNCTUAL'] = translate('LBL_PAYMENT_TYPE_PUNCTUAL', 'stic_Web_Forms');

        return $langVars;
    }
}
