<?php

require_once 'include/MVC/View/views/view.popup.php';
require_once 'SticInclude/Views.php';

class DocumentsViewPopup extends ViewPopup
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
