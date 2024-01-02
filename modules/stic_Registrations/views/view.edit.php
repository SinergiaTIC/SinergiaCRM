<?php

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

class stic_RegistrationsViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
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

        echo getVersionedScript("modules/stic_Registrations/Utils.js");
    }

}
