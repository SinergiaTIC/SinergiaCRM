<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_Personal_EnvironmentViewList extends ViewList
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

        echo getVersionedScript("modules/stic_Personal_Environment/Utils.js");

        // Write here you custom code
    }

}
