<?php

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomNotesViewDetail extends ViewDetail
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
