<?php

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_Validation_ResultsViewDetail extends ViewDetail
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {

        parent::preDisplay();
        /**
         * This code is used in the SinergiaCRM-SugarCRM hotfix #150 that allows properly viewing HTML fields without setting DeveloperMode to true.
         *
         * This view will load CustomDetailView in preDisplay in order to enable disableCheckTemplate parameter.
         * This will force the cached view to be rebuilt and show the right values in HTML fields.
         *
         * This code needs to be placed right after the preDisplay of the module
         *
         */
        $metadataFile = $this->getMetaDataFile();
        require_once 'custom/include/DetailView/CustomDetailView.php';
        $this->dv = new CustomDetailView();
        $this->dv->ss = &$this->ss; // ss is the SugarSmarty object
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));

        SticViews::preDisplay($this);

    }

    public function display()
    {
        /**
         * Same code for the hotfix #150 of SinergiaCRM-SugarCRM.
         *
         * This line needs to be placed right before the display of the module
         *
         */
        $this->dv->th->disableCheckTemplate = true;
        parent::display();

        SticViews::display($this);

        // Write here you custom code

    }

}
