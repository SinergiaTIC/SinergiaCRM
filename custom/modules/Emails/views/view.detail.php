<?php

require_once 'modules/Emails/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomEmailsViewDetail extends EmailsViewDetail
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
    }
}
