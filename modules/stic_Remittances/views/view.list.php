<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_RemittancesViewList extends ViewList
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
        echo getVersionedScript("modules/stic_Remittances/Utils.js");

        // Write here you custom code
    }

}
