<?php

require_once 'modules/Contacts/views/view.detail.php';
require_once 'SticInclude/Views.php';

class CustomContactsViewDetail extends ContactsViewDetail
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();

        $this->dv->defs['templateMeta']['form']['buttons']['SEND_CONFIRM_OPT_IN_EMAIL'] = EmailAddress::getSendConfirmOptInEmailActionLinkDefs('Contacts');


        SticViews::preDisplay($this);

        // Write here you custom code
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);
        echo getVersionedScript("custom/modules/Contacts/SticUtils.js");

        // Write here you custom code
    }

}
