<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';
class CustomFP_Event_LocationsViewList extends ViewList
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
        // It is not included because the file does not exist in this module.
        // echo getVersionedScript("custom/modules/FP_Event_Locations/SticUtils.js");

        // Write here you custom code
    }
}
