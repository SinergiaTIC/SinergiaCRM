<?php

/**
 * This file is used in the SinergiaCRM hotfix #150 that allows properly viewing HTML fields without setting DeveloperMode to true.
 * 
 * CustomDisplayView overrides the setup generic function in order to set the CustomTemplateHandler for the view and 
 * be able to access the invalidateParameter from the view.detail.php 
 * 
 */

require_once('include/DetailView/DetailView2.php');

class CustomDetailView extends DetailView2
{

    /**
     * Override setup
     * @see DetailView2::setup()
     */
    public function setup(
        $module, 
        $focus = null, 
        $metadataFile = null, 
        $tpl = 'include/DetailView/DetailView.tpl', 
        $createFocus = true, 
        $metadataFileName = 'detailviewdefs'
        )
    {
        parent::setup($module, $focus, $metadataFile, $tpl);
        require_once 'custom/include/TemplateHandler/CustomTemplateHandler.php';
        $this->th = new CustomTemplateHandler();
        $this->th->ss = $this->ss; // ss is the Sugar Smarty object
    }
}
