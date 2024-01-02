<?php

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

class stic_Sepe_IncidentsViewPopup extends ViewPopup
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

        echo getVersionedScript("modules/stic_Sepe_Incidents/Utils.js");
    }

}
