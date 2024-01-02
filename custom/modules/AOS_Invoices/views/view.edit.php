<?php
require_once 'modules/AOS_Invoices/views/view.edit.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewEdit extends AOS_InvoicesViewEdit
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;

        // There is an issue if we enable the following line. The quickcreate will show a double panel duplicating fields.
        // This also happens in the latest non-customized version of SuiteCRM. 16/06/2020 TODO
        // $this->useModuleQuickCreateTemplate = true;

        // Since the suite base modules name the bean in the singular, we configure in the view the name of the module in the plural. This property will be used by the SticViews class to load the language files
        $this->moduleName = 'AOS_Invoices';
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
        echo getVersionedScript("custom/modules/AOS_Invoices/SticUtils.js");
    }

}
