<?php

require_once 'modules/Leads/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomLeadsViewDetail extends LeadsViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here the SinergiaCRM code that must be executed for this module and view
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);
        echo getVersionedScript("custom/modules/Leads/SticUtils.js");

        // Write here the SinergiaCRM code that must be executed for this module and view
    }
}
