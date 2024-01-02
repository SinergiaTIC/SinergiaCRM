<?php

require_once 'modules/AOS_Invoices/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewDetail extends AOS_InvoicesViewDetail
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

        // Write here the SinergiaCRM code that must be executed for this module and view
        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }
}
