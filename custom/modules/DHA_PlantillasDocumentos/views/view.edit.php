<?php

require_once 'modules/DHA_PlantillasDocumentos/views/view.edit.php';
require_once 'SticInclude/Views.php';

class CustomDHA_PlantillasDocumentosViewEdit extends DHA_PlantillasDocumentosViewEdit
{
    public function __construct()
    {
        parent::DHA_PlantillasDocumentosViewEdit();
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
