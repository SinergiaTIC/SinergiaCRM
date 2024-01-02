<?php

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

class stic_Job_OffersViewPopup extends ViewPopup
{

    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {

        parent::preDisplay();

        SticViews::preDisplay($this);

    }
    public function display()
    {

        parent::display();

        SticViews::display($this);

        // We need to add manually to the frontend the required Incorpora fields
        require_once 'modules/stic_Incorpora/utils/FieldsDef.php';
        $incorporaRequiredFieldsArray = json_encode(array_filter($offerDef, function ($var) {return $var['required'];}));
        echo <<<SCRIPT
        <script>STIC.incorporaRequiredFieldsArray = $incorporaRequiredFieldsArray;</script>
    SCRIPT;

        echo getVersionedScript("modules/stic_Job_Offers/Utils.js");

    }

}
