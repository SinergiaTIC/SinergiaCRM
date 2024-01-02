<?php

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

class CustomAccountsViewPopup extends ViewPopup
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        // Write here you custom code

        // We need to add manually to the frontend the required Incorpora fields
        require_once('modules/stic_Incorpora/utils/FieldsDef.php');
        $incorporaRequiredFieldsArray = json_encode(array_filter($accountDef, function ($var) { return $var['required']; }));
        $incorporaAgreementRequiredFieldsArray = json_encode(array_filter($accountDef, function ($var) { return $var['agreementRequired']; }));

        echo <<<SCRIPT
        <script>
            STIC.incorporaRequiredFieldsArray = $incorporaRequiredFieldsArray;
            STIC.incorporaAgreementRequiredFieldsArray = $incorporaAgreementRequiredFieldsArray;
        </script>
    SCRIPT;

        echo getVersionedScript("custom/modules/Accounts/SticUtils.js");
    }

}
