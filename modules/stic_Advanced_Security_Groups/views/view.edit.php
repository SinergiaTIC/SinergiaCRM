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
        global $sugar_config;
        
        parent::preDisplay();

        SticViews::preDisplay($this);

        echo "<script>let stic_advanced_security_groups_enabled = '{$sugar_config['stic_advanced_security_groups_enabled']}' </script>";

        require_once 'modules/stic_Advanced_Security_Groups/Utils.php';
        
        // We load the list of security groups
        stic_Advanced_Security_GroupsUtils::setCustomSecurityGroupList();
        
        // Charge relate modules list
        stic_Advanced_Security_GroupsUtils::setCustomRelatedModuleList($this->bean->name);
        
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
