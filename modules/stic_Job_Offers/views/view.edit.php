<?php

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

class stic_Job_OffersViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
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
        require_once 'modules/stic_Incorpora/utils/FieldsDef.php';
        $incorporaRequiredFieldsArray = json_encode(array_filter($offerDef, function ($var) {return $var['required'];}));
        echo <<<SCRIPT
        <script>STIC.incorporaRequiredFieldsArray = $incorporaRequiredFieldsArray;</script>
    SCRIPT;

        echo getVersionedScript("modules/stic_Job_Offers/Utils.js");
    }
}
