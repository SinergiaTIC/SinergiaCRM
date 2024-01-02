<?php

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';
class CustomFP_Event_LocationsViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
        // Since the suite base modules name the bean in the singular, we configure in the view the name of the module in the plural. This property will be used by the SticViews class to load the language files
        $this->moduleName = 'FP_Event_Locations';
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
