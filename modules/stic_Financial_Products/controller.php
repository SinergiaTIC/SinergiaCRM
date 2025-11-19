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

class stic_Financial_ProductsController extends SugarController
{
    /**
     * Validate if IBAN is correct when is not empty, calling to main checkIBAN function. This action is for use in javascript ajax calls
     *
     * @return Boolean json_encoded, for use in ajax response
     */
    public function action_checkIBAN()
    {
        require_once 'SticInclude/Utils.php';

        $iban = isset($_REQUEST['iban']) ? trim($_REQUEST['iban']) : '';

        // If the field is empty, we consider it valid
        if (empty($iban)) {
            echo json_encode(true);
            exit;
        }

        // Call the IBAN validation if not empty
        $resp = SticUtils::checkIBAN($iban);
        echo json_encode($resp);
        exit;
    }

}
