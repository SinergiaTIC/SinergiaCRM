<?php

require_once 'modules/Accounts/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomAccountsViewList extends AccountsViewList
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
        echo getVersionedScript("custom/modules/Accounts/SticUtils.js");

        // Write here you custom code
    }
}
