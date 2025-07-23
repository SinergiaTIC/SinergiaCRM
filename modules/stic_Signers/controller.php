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
 * Controller for the stic_Signers module.
 * This class handles actions related to signer management, such as sending signature requests.
 */
class stic_SignersController extends SugarController
{
    /**
     * Handles the 'sendToSign' action.
     * This action retrieves the signer ID from the request and
     * calls the utility function to send the signature request email.
     *
     * @return void
     */
    public function action_sendToSign()
    {
        require_once 'modules/stic_Signers/Utils.php';
        $signerId = $_REQUEST['signerId'] ?? '';
        if (!empty($signerId)) {
            // Call the utility function to send the signature email
            stic_SignersUtils::sendToSign($signerId);
        }
        // Terminate script execution after sending or if signerId is empty
        die();
    }
}