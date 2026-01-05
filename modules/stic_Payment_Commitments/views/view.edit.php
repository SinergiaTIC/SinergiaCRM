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
class stic_Payment_CommitmentsViewEdit extends ViewEdit
{

    public function __construct()
    {
        global $app_list_strings;

        $app_list_strings['stic_payments_types_list'] = self::generatePaymentTypeOptionsFromUser();
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {
        global $app_list_strings;
        parent::display();

        SticViews::display($this);

        // Write here you custom code

        echo getVersionedScript("modules/stic_Payment_Commitments/Utils.js");
    }

    private static function generatePaymentTypeOptionsFromUser() {
        global $app_list_strings;
        require_once 'modules/stic_Payments/Utils.php';
        $orgKeyArray = stic_PaymentsUtils::getM182IssuingOrganizationKeyForCurrentUser();
        if (count($orgKeyArray) == 0 || (count($orgKeyArray) == 1 && $orgKeyArray[0] === '')) {
            return $app_list_strings['stic_payments_types_list'];
        }
            
        include_once "modules/stic_Remittances/Utils.php";
        stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);   
        $movementClassList = $app_list_strings['stic_payments_types_list'];
        require_once 'modules/stic_Payments/Utils.php';
        $filteredMovementClassList = array("" => "");
        foreach ($orgKeyArray as $orgKey) {
            if ($orgKey === '__default__') {
                $filteredMovementClassList = array_merge($filteredMovementClassList, stic_PaymentsUtils::filterMovementClassListForDefaultOrg($movementClassList, $app_list_strings['dynamic_issuing_organization_list']));
                continue;
            }
            $filteredMovementClassList = array_merge($filteredMovementClassList, stic_PaymentsUtils::filterMovementClassListForSelectedOrg($movementClassList, $orgKey));
        }

        return $filteredMovementClassList;
    }

}
