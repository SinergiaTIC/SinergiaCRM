<?php

require_once 'modules/Documents/views/view.edit.php';
require_once 'SticInclude/Views.php';

class CustomDocumentsViewEdit extends DocumentsViewEdit {
    public function __construct() {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;

        // Since the suite base modules name the bean in the singular, we configure in the view the name of the module in the plural. This property will be used by the SticViews class to load the language files
        $this->moduleName = 'Documents';
    }

    public function preDisplay() {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code
    }

    public function display() {
        parent::display();

        SticViews::display($this);

        // Write here you custom code
    }
}
