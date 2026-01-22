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
     * Action to send a signature request email to one or more signers.
     * It checks for the presence of signer IDs in the request and calls
     * the utility function to send the emails.
     *
     * @param string|null $signerId Optional single signer ID. If not provided, it will look for 'uid' in the request.
     * @throws Exception If no signer ID is provided or if the signer ID type is invalid.
     * @return void
     */
    public function action_sendToSign($signerId = null)
    {
        // Determine the signer IDs to process
        if ($_REQUEST['signerId'] === null) {
            if (empty($_REQUEST['uid'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'No signer ID provided in request.');
                throw new Exception("No signer ID provided.");
            }
            $signersIds = explode(',', $_REQUEST['uid']);
        } else {
            if (!is_string($_REQUEST['signerId'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Invalid signer ID type. Expected string.');
                throw new Exception("Invalid signer ID type.");
            }
            $signersIds = [$_REQUEST['signerId']];
        }

        // Get the number of signers to determine redirection logic
        $lenght = count($signersIds);

        require_once 'modules/stic_Signers/Utils.php';

        // Loop through each signer ID and send the signature email
        foreach ($signersIds as $signerId) {
            if (!empty($signerId)) {
                // Call the utility function to send the signature email
                stic_SignersUtils::sendToSign($signerId);
            }

        }
        if ($lenght == 1) {
            SugarApplication::redirect('index.php?module=stic_Signers&action=DetailView&record=' . $signerId);
        } else {
            SugarApplication::redirect('index.php?module=stic_Signers&action=index');
        }
    }

    /**
     * Action to download the signature document for one or more signers.
     * It checks for the presence of signer IDs in the request and calls
     * the utility function to handle the document download.
     *
     * @return void
     */
    public function action_downloadDocument()
    {
        // Determine the signer IDs to process
        if ($_REQUEST['signerId'] === null) {
            if (empty($_REQUEST['uid'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'No signer ID provided in request.');
            }
            $signersIds = explode(',', $_REQUEST['uid']);
        } else {
            if (!is_string($_REQUEST['signerId'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Invalid signer ID type. Expected string.');
            }
            $signersIds = [$_REQUEST['signerId']];
        }

        // Get the number of signers to determine redirection logic
        $lenght = count($signersIds);
        
        
        require_once 'modules/stic_Signers/Utils.php';
        if (!empty($signersIds)) {
            stic_SignersUtils::downloadDocuments($signersIds);
        } else {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'No signer ID provided in request.');
            sugar_die("No signer ID provided.");
        }
    }

}
