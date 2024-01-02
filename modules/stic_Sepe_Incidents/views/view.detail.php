<?php

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_Sepe_IncidentsViewDetail extends ViewDetail
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

        echo getVersionedScript("modules/stic_Sepe_Incidents/Utils.js");
    }

}
