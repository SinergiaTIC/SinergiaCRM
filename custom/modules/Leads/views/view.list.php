<?php

require_once 'modules/Leads/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomLeadsViewList extends LeadsViewList
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
        echo getVersionedScript("custom/modules/Leads/SticUtils.js");

        // Write here you custom code
    }
}
