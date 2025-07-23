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
 * This script serves as the entry point for the electronic signature portal.
 * It initializes and displays the signature portal view.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Include necessary files to load the view class
require_once('include/MVC/View/SugarView.php');
require_once('modules/stic_Signatures/views/view.signatureportal.php'); // Ensure this path is correct

// Initialize an instance of the view class
$view = new stic_SignaturePortal();

// Call the display() method of the view
$view->display();

// Terminate script execution to ensure no additional SuiteCRM framework elements are loaded
sugar_die('');