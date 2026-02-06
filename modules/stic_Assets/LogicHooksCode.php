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

class stic_AssetsLogicHooks
{

    public function before_save(&$bean, $event, $arguments)
    {
        global $app_list_strings;
        
        // Set the code if it's a new record
        if (!$bean->fetched_row) {
            $query = "SELECT MAX(CAST(code AS UNSIGNED)) FROM {$bean->table_name} WHERE deleted = 0";
            $result = intval($bean->db->getOne($query));
            $bean->code = str_pad($result + 1, 4, '0', STR_PAD_LEFT);
        }
        
        // Set the name field if it's empty
        if (empty($bean->name)) {
            require_once 'SticInclude/Utils.php';
            // Get the subject name
            $contactBean = SticUtils::getRelatedBeanObject($bean, 'stic_assets_contacts');
            $contactName = !empty($bean->stic_assets_contactscontacts_ida) ? $contactBean->first_name . ' ' . $contactBean->last_name : '';

            // $contactName = $contactBean ? $contactBean->full_name : '';
            $bean->name = "{$contactName} - {$app_list_strings['stic_asset_managment_types_list'][$bean->type]} - {$bean->code}";
        }

    }

}
