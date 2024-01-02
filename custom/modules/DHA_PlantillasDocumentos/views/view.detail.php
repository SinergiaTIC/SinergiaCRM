<?php

require_once 'modules/DHA_PlantillasDocumentos/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomDHA_PlantillasDocumentosViewDetail extends DHA_PlantillasDocumentosViewDetail
{
    protected function _displayJavascript() {  
        parent::_displayJavascript(); 
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
