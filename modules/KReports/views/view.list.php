<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

// STIC-Custom 20211108 AAM - Adding Stic customizations to the view list. So the Selectize would load and other features that we might include.
// STIC#469

class KReportsViewList extends ViewList
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
