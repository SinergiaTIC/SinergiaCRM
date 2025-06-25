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
class stic_Bookings_Places_CalendarUtils
{
    /**
     * Returns all the existing resources with the necessary information to display them in the calendar
     *
     * @return void
     */
    public static function getAllPlaces()
    {
        global $app_list_strings, $current_language, $sugar_config;
        // In order to display the Calendar in the Dashlet, we need to retrieve the mod_strings manually from 
        // the module using this function
        $mod_strings = return_module_language($current_language, 'stic_Bookings_Calendar');
        return array();
    }
}
