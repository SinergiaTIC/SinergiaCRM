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

/**
 * This class extends the default SugarCRM ViewEdit to provide custom
 * functionality for the stic_Signatures module's edit view.
 * It includes logic for populating signer path lists.
 */
require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

#[\AllowDynamicProperties]
class stic_SignaturesViewEdit extends ViewEdit
{
    /**
     * Constructor for stic_SignaturesViewEdit.
     * Initializes the parent constructor and sets properties for subpanel and quick create template usage.
     */
    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    /**
     * Executes logic before displaying the edit view.
     * This method is overridden to include custom pre-display operations
     * such as populating signer path lists.
     *
     * @return void
     */
    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Get emailable related modules and populate the dropdown
        require_once 'modules/stic_Signatures/Utils.php';
        stic_SignaturesUtils::populateSignerPathListString($this->bean->main_module ?? null);
    }

    /**
     * Renders the edit view.
     * This method is overridden to include custom display operations
     * such as calling SticViews::display and including a versioned JavaScript script.
     *
     * @return void
     */
    public function display()
    {
        parent::display();

        SticViews::display($this);

        // Write here your custom code
        echo getVersionedScript("modules/stic_Signatures/Utils.js");
    }
}