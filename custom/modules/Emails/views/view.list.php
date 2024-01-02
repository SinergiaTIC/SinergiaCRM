<?php

require_once 'modules/Emails/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomEmailsViewList extends EmailsViewList
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
    }
}
