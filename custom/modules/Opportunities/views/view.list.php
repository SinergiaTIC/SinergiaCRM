<?php

require_once 'modules/Opportunities/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomOpportunitiesViewList extends OpportunitiesViewList
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
