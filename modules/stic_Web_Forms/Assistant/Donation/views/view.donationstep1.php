<?php

require_once 'modules/stic_Web_Forms/Assistant/AssistantView.php';

class ViewDonationstep1 extends stic_Web_FormsAssistantView
{
    /**
     * Do what is needed before showing the view
     */
    public function preDisplay()
    {
        parent::preDisplay();
        $this->templateDir = "modules/stic_Web_Forms/Assistant/Donation/tpls";
        $this->tpl = "Step1.tpl";
    }
}
