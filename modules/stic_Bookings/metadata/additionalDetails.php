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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "include/utils/additional_details.php";

function additionalDetailsstic_Bookings($fields, ?SugarBean $bean = null, $params = array())
{
    if (file_exists('custom/modules/' . $bean->module_name . '/metadata/customAdditionalDetails.php')) {
        $additionalDetailsFile = 'custom/modules/' . $bean->module_name . '/metadata/customAdditionalDetails.php';
        require_once($additionalDetailsFile);
        
        $mod_strings = return_module_language($current_language, $bean->module_name);
        return customAdditionalDetails::customAdditionalDetailsstic_Bookings($fields, $bean, $mod_strings);

    } else {
        global $current_language,$timedate, $current_user;
        $fields['RESOURCE_NAME'] = $_REQUEST['resource_name'] ?? '';
        $fields['RESOURCE_ID'] = $_REQUEST['resource_id'] ?? '';
    
        if ($bean->load_relationship('stic_resources_stic_bookings')) {
            $resourcesBeans = $bean->stic_resources_stic_bookings->getBeans();
            $fields['RESOURCE_COUNT'] = count($resourcesBeans);
            $fields['SHOW_BUTTONS'] = true;
            if (!$fields['RESOURCE_NAME'] || !$fields['RESOURCE_ID']) {
                $fields['SHOW_BUTTONS'] = false;
                $fields['RESOURCES_LIST'] = array();
                foreach ($resourcesBeans as $resourceBean) {
                    $fields['RESOURCES_LIST'][] = array('name' => $resourceBean->name, 'id' => $resourceBean->id);
                }
            }
        }
        $fields['USER_START_DATE'] = $fields['START_DATE'];
        $fields['USER_END_DATE'] = $fields['END_DATE'];

        if ($bean->all_day == '1') {
            if (!empty($fields['USER_START_DATE'])) {
                $startDate = $timedate->fromUser($fields['USER_START_DATE'], $current_user);
                if ($startDate) {
                    $fields['USER_START_DATE'] = $timedate->asUserDate($startDate, false, $current_user);
                }
            }

            if (!empty($fields['USER_END_DATE'])) {
                $endDate = $timedate->fromUser($fields['USER_END_DATE'], $current_user);
                if ($endDate) {
                    $endDate->modify('previous day');
                    $fields['USER_END_DATE'] = $timedate->asUserDate($endDate, false, $current_user);
                }
            }
        }
       
        $mod_strings = return_module_language($current_language, $bean->module_name);
        return additional_details($fields, $bean, $mod_strings);
    }    
}