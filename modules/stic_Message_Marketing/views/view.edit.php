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

require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';
require_once 'modules/stic_Messages/Utils.php';
require_once('modules/stic_Settings/Utils.php');

class stic_Message_MarketingViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    public function preDisplay()
    {
        require_once 'modules/stic_Settings/Utils.php';

        parent::preDisplay();

        SticViews::preDisplay($this);



    }

    public function display()
    {
        if (!isset($this->bean->sender) || empty($this->bean->sender) ){
            $this->bean->sender = stic_SettingsUtils::getSetting('MESSAGES_SENDER');
            $this->ev->focus->sender = stic_SettingsUtils::getSetting('MESSAGES_SENDER');
        }
        stic_MessagesUtils::fillDynamicListMessageTemplate();
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Message_Marketing/Utils.js");
        
        // Write here you custom code

    }

}
