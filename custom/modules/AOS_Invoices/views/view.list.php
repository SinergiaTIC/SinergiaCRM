<?php
require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewList extends ViewList
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
        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }
}
