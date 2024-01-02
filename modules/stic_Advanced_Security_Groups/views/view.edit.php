<?php

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

class stic_Advanced_Security_GroupsViewEdit extends ViewEdit
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

        require_once 'modules/stic_Advanced_Security_Groups/Utils.php';
        
        // We load the list of security groups
        stic_Advanced_Security_GroupsUtils::setCustomSecurityGroupList();
        
        // Charge relate modules list
        stic_Advanced_Security_GroupsUtils::setCustomRelatedModuleList($this->bean);
        
        // Charge filtered modules list
        stic_Advanced_Security_GroupsUtils::setCustomFilteredModuleList();

        


    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        // Write here you custom code

        echo getVersionedScript("modules/stic_Advanced_Security_Groups/Utils.js");
    }
}
