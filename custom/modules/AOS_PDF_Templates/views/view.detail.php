<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/AOS_PDF_Templates/views/view.detail.php';
require_once 'SticInclude/Views.php';
class CustomAOS_PDF_TemplatesViewDetail extends AOS_PDF_TemplatesViewDetail
{
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
        // SuiteCRM modules use singular form for bean names. Plural form is set in SticViews class in order to load the language files
        $this->moduleName = 'AOS_PDF_Templates';
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
