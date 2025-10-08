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
 * Controller for the stic_Signatures module.
 * This class handles various AJAX actions related to signature templates,
 * previewing documents, retrieving module relationships, and fetching signers.
 */
class stic_SignaturesController extends SugarController
{
    /**
     * Action to parse a signature template and return its HTML content.
     * This action is typically used for debugging or internal testing purposes
     * with a hardcoded signer ID.
     *
     * @return void
     */
    public function action_parseTemplate()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $signerId = '00000b06-3aa0-2b29-db5c-6879efaf8c9d'; // Hardcoded signer ID for testing
        $html = stic_SignaturesUtils::getParsedTemplate($signerId);
        die($html);
    }

    /**
     * Action to get a preview of a document for a given signer ID.
     * It retrieves the parsed HTML template and echoes it.
     *
     * @return void
     */
    public function action_getPreview()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        $signerId = $_REQUEST['signerId'] ?? '';
        $html = stic_SignaturesUtils::getParsedTemplate($signerId);
        if (!empty($html)) {
            echo $html;
        } else {
            echo '<p class="text-red-500 border-red-500 text-center">No se encontró contenido para el firmante especificado.</p>';
        }
        die();
    }

    /**
     * Action to retrieve module relationships.
     * It uses the `getModuleRelationships` utility function and outputs the result
     * for a specified module and format (raw or JSON).
     *
     * @return void
     */
    public function action_getRelationships()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        var_dump(stic_SignaturesUtils::getModuleRelationships($_REQUEST['getmodule'], $_REQUEST['format'] ?? 'raw'));
        die();
    }

    

}
