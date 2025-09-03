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

#[\AllowDynamicProperties]
class stic_Advanced_Web_FormsViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        // parent::preDisplay();

        SticViews::preDisplay($this);

        // Tailwindcss
        // echo '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>';

        // Bootstrap (modified with scoped classes: do not crash current layout!)
        echo "<link rel='stylesheet' href='SticInclude/vendor/bootstrap/bootstrap.scoped.min.css'>";
        echo '<script src="SticInclude/vendor/bootstrap/bootstrap.min.js"></script>';

        // Alpinejs
        echo '<script src="//unpkg.com/alpinejs" defer></script>';

        // jstree
        echo '<link rel="stylesheet" href="SticInclude/vendor/jstree/themes/default/style.min.css" />';
        echo getVersionedScript("SticInclude/vendor/jstree/jstree.min.js");

        // Wizard
        echo getVersionedScript("modules/stic_Advanced_Web_Forms/wizard/js/wizard.js");
        echo "<link rel='stylesheet' href='". getVersionedPath("modules/stic_Advanced_Web_Forms/wizard/css/wizard.css") ."'>";
    }

    public function display()
    {
        // parent::display();

        SticViews::display($this);

        $this->ss->assign('title', $this->getModuleTitle(false));

        require_once "modules/stic_Advanced_Web_Forms/Utils.php";
        $this->ss->assign('enabledStudioModules', json_encode(getInitialModules()));
        $this->ss->assign('enabledModules', json_encode(getEnabledModules()));

        echo $this->ss->fetch('modules/stic_Advanced_Web_Forms/wizard/tpl/wizard.tpl');
    }

}
