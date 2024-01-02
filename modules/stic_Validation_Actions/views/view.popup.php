<?php

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

class stic_Validation_ActionsViewPopup extends ViewPopup
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

        // Uncoment this line & create Utils.js if module require JS validations

        // echo getVersionedScript("modules/stic_Validation_Actions/Utils.js");

    }

}
