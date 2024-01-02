<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';
require_once 'modules/stic_Bookings/stic_BookingsListViewSmarty.php';

class stic_BookingsViewList extends ViewList
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {

        parent::preDisplay();

        SticViews::preDisplay($this);

        // Use a custom ListViewSmarty to transform the start_date and end_date 
        // depending on the value of the all_day field
        $this->lv = new stic_BookingsListViewSmarty();

        // Write here you custom code

    }

    public function display()
    {

        parent::display();

        SticViews::display($this);
        echo getVersionedScript("modules/stic_Bookings/Utils.js");
        // Write here you custom code
    }

}
