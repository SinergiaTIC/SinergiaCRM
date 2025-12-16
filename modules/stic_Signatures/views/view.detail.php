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
 * This class extends the default SugarCRM ViewDetail to provide custom
 * functionality for the stic_Signatures module's detail view.
 * It includes logic for populating signer path lists and redirection.
 */
require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_SignaturesViewDetail extends ViewDetail
{
    /**
     * Executes logic before displaying the detail view.
     * This method is overridden to include custom pre-display operations
     * such as populating signer path lists and handling redirection if `signer_path` is empty.
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

        // Redirect to EditView if any of panel 3 required fields are empty
        $fieldsToCheck = ['signature_mode', 'auth_method', 'pdf_audit_page', 'status', 'type',  'email_template', 'email_template_send_document', 'email_template_otp', 'email_template_otp_sms'];
        foreach ($fieldsToCheck as $field) {
            if (empty($this->bean->$field)) {
                SugarApplication::redirect(
                    "index.php?module=stic_Signatures&action=EditView&return_module=stic_Signatures&return_action=DetailView&record={$this->bean->id}"
                );
            }

        }
    }

    /**
     * Renders the detail view.
     * This method is overridden to include custom display operations
     * such as calling SticViews::display and including a versioned JavaScript script.
     *
     * @return void
     */
    public function display()
    {
        parent::display();

        SticViews::display($this);

        // Include the versioned JavaScript utility file
        echo getVersionedScript("modules/stic_Signatures/Utils.js");

        // Write here your custom code
    }
}
