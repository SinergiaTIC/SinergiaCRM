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
class stic_Work_CalendarUtils
{
    /**
     * 
     *
     * @return void
     */
    public static function existsRecordsWithIncompatibleType($id, $applicationDate, $type, $assignedUserId)
    {
        global $db;
        $allDayTypes = ["working", "punctual_absence"];

        // Check if there is already a non-work record that takes up the entire day, in that case, it is not posible to create the record
        $query = "SELECT * FROM stic_work_calendar
                WHERE deleted = 0 
                    AND id != '". $id . "' 
                    AND application_date = '".$applicationDate."' 
                    AND assigned_user_id = '" . $assignedUserId . "' 
                    AND type NOT IN ('" .  implode("', '", $allDayTypes) . "');";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);

        if (!is_null($result) && $result->num_rows > 0) {
            return "0";
        } else {
            if (!in_array($type, $allDayTypes)) {
                // Checks if exist a record that does not occupy the entire day, in that case, since the record to be created is an all-day record, it is not possible to create the record.
                $query = "SELECT * FROM stic_work_calendar
                WHERE deleted = 0 
                    AND id != '". $id . "' 
                    AND application_date = '".$applicationDate."' 
                    AND assigned_user_id = '" . $assignedUserId . "' 
                    AND type IN ('" .  implode("', '", $allDayTypes) . "');";
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
                $result = $db->query($query);

                if (!is_null($result) && $result->num_rows > 0) {
                    return "0";
                } else {
                    return "1";
                }
            } else {
                return "1";
            }
        }
    }

}
