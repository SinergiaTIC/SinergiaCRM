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

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_SignersViewList extends ViewList
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {
        global $current_user;
        parent::preDisplay();
        $userPreferenceOrder = $current_user->getPreference('listviewOrder', 'stic_Signers2_STIC_SIGNERS');
        
        SticViews::preDisplay($this);
        
        // Force default order by date_modified DESC if no order is set
        if (empty($userPreferenceOrder['orderBy'])) {
            $_REQUEST['orderBy'] = 'date_modified';
            $_REQUEST['sortOrder'] = 'DESC';
        }

    }

    public function display()
    {
        parent::display();

        SticViews::display($this);
        echo getVersionedScript("modules/stic_Signers/Utils.js");

        // Write here you custom code
    }

}
