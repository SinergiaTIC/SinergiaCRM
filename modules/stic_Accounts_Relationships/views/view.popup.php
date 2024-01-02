<?php

require_once('include/MVC/View/views/view.quickcreate.php');
require_once 'SticInclude/Views.php';

class stic_Accounts_RelationshipsViewPopup extends ViewPopup {

    function preDisplay() {
        
        parent::preDisplay();
		
		SticViews::preDisplay($this);
        
	}
    function display() {
        
        parent::display();
		
		SticViews::display($this);
        
        echo getVersionedScript("modules/stic_Accounts_Relationships/Utils.js");        
        
	}

}
