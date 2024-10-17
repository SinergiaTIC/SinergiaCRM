<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

require_once 'modules/Contacts/views/view.detail.php';
require_once 'SticInclude/Views.php';
require_once 'modules/stic_Messages/Checkstic_Messages.php';

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

        require_once('modules/stic_Messages/Utils.php');
        stic_MessagesUtils::echoIsMessagesModuleActive();

        // Write here you custom code
    }

}
