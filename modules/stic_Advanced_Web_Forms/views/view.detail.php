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
require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_Advanced_Web_FormsViewDetail extends ViewDetail
{

    public function __construct()
    {
        parent::__construct();

    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        echo '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>';
        echo '<script src="//unpkg.com/alpinejs" defer></script>';
    }

    public function display()
    {
        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Advanced_Web_Forms/wizard/js/wizard.js");
        echo "<link rel='stylesheet' href='". getVersionedPath("modules/stic_Advanced_Web_Forms/wizard/css/wizard.css") ."'>";

        // DetailView: Same as EditView, but readOnly
        $this->ss->assign('readOnly', true); 
        $this->ss->assign('title', $this->getModuleTitle(false));

        echo $this->ss->fetch('modules/stic_Advanced_Web_Forms/wizard/tpl/wizard.tpl');
    }

}
