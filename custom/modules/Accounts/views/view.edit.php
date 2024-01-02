<?php

require_once 'modules/Accounts/views/view.edit.php';
require_once 'SticInclude/Views.php';

class CustomAccountsViewEdit extends AccountsViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
        // Since the suite base modules name the bean in the singular, we configure in the view the name of the module in the plural. This property will be used by the SticViews class to load the language files
        $this->moduleName = 'Accounts';
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
