<?php

require_once 'modules/Opportunities/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomOpportunitiesViewDetail extends OpportunitiesViewDetail
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
