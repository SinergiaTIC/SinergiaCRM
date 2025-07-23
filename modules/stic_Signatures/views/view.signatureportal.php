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
 * This class handles the display logic for the electronic signature portal.
 * It prepares the HTML content, manages stylesheets and JavaScript,
 * and handles basic authentication mode validation.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_SignaturePortal extends SugarView
{

    /**
     * Constructor for stic_SignaturePortal.
     * Initializes a new Smarty instance.
     */
    public function __construct()
    {
        $this->ss = new Sugar_Smarty();
    }

    /**
     * Displays the electronic signature portal.
     * This method retrieves document content, validates authentication,
     * assigns variables to the Smarty template, and renders the portal HTML.
     *
     * @return void
     */
    public function display()
    {
        global $smarty;
        global $mod_strings; // Load module-specific language strings if available
        global $app_strings; // General application language strings

        $documentHtmlContent = '
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Acuerdo de Confidencialidad</h2>
        ';

        require_once 'modules/stic_Signatures/SignaturePortal/SignaturePortalUtils.php';
        // Create an instance of the utility class
        $stic_SignaturePortalUtils = new stic_SignaturePortalUtils();

        // Get signatureBean
        $signatureBean = $stic_SignaturePortalUtils->signatureBean ?? null;

        // Get authentication mode
        $authMode = $signatureBean->auth_method ?? 'unique_link';

        $passed = false;
        $errorMsg = '';

        // Validate authentication mode
        switch ($authMode) {
            case 'unique_link':
                $passed = true;
                break;
            default:
                $errorMsg = 'El modo de autenticación no es válido.';
                $this->ss->assign('ERROR_MSG', $errorMsg);
                break;
        }

        // Get parsed template HTML content only if authentication passed
        if ($passed === true) {
            $documentHtmlContent = $stic_SignaturePortalUtils->getHtmlFromSigner();
        }

        // Assign variables to Smarty
        $this->ss->assign('DOCUMENT_HTML_CONTENT', $documentHtmlContent);
        $this->ss->assign('CURRENT_DATE_TIME', date('d/m/Y H:i:s'));
        $this->ss->assign('CURRENT_DATE_MINUS_5_MINS', date('d/m/Y H:i:s', strtotime('-5 minutes')));

        // Include CSS and JS
        // It's best to load assets this way for proper SuiteCRM management.
        // Ensure files exist in the correct directories.
        $this->ss->assign('STYLESHEETS', '<link rel="stylesheet" href="modules/stic_Signatures/SignaturePortal/SignaturePortal.css">');
        $this->ss->assign('JAVASCRIPT', '<script src="modules/stic_Signatures/SignaturePortal/SignaturePortal.js"></script>');
        $this->ss->assign('TAILWIND_SCRIPT', '
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: [\'Inter\', \'sans-serif\'],
                            },
                        }
                    }
                }
            </script>
        ');

        // Load the Smarty template
        // The path should be relative to the SuiteCRM base directory
        echo $this->ss->fetch('modules/stic_Signatures/SignaturePortal/SignaturePortal.html');
    }
}