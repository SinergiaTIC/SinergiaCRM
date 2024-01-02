<?php

require_once 'modules/stic_Web_Forms/Assistant/AssistantView.php';

class ViewEventinscriptionstep1 extends stic_Web_FormsAssistantView
{
    /**
     * Do what is needed before showing the view
     */
    public function preDisplay()
    {
        parent::preDisplay();

        $this->templateDir = "modules/stic_Web_Forms/Assistant/EventInscription/tpls";
        $this->tpl = "Step1.tpl";

        $persistent = $this->view_object_map['PERSISTENT_DATA'];
        $this->ss->assign('FP_CHECKED', !empty($persistent['include_payment_commitment']) ? 'checked' : '');
        $this->ss->assign('ORG_CHECKED', !empty($persistent['include_organization']) ? 'checked' : '');
        $this->ss->assign('CIF_CHECKED', !empty($persistent['account_code_mandatory']) ? 'checked' : '');
        $this->ss->assign('INS_CHECKED', !empty($persistent['include_registration']) ? 'checked' : '');
        $this->ss->assign('NAME_ORG_CHECKED', !empty($persistent['account_name_optional']) ? 'checked' : '');
    }
}
