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

class stic_BookingsLogicHooks {
    
    public function formatAllDayDates($bean, $event, $arguments) {
        // Only apply this logic when we are in the subpanel, to avoid affecting the date display in the main view and edit view of the booking, where we want to show the real start and end date with time, even for all day events. If you want to apply this logic everywhere, just remove this IF.
        if (isset($_REQUEST['action']) && $_REQUEST['action'] !== 'SubPanelViewer') {
            return;
        }

        global $timedate, $current_user;

        if (isset($bean->all_day) && $bean->all_day == '1') {
            
            if (!empty($bean->start_date)) {
                $startDate = $timedate->fromUser($bean->start_date, $current_user);
                $startDateDb = $timedate->asDb($startDate);
                $parts = explode(' ', $startDateDb);
                
                if ($parts[1] > "12:00") {
                    $dateObj = new DateTime($parts[0]);
                    $dateObj->modify("next day");
                    $bean->start_date = $timedate->asUserDate($dateObj, false, $current_user);
                } else {
                    $dateObj = new DateTime($parts[0]);
                    $bean->start_date = $timedate->asUserDate($dateObj, false, $current_user);
                }
            }

            if (!empty($bean->end_date)) {
                $endDate = $timedate->fromUser($bean->end_date, $current_user);
                $endDateDb = $timedate->asDb($endDate);
                $parts = explode(' ', $endDateDb);
                
                if ($parts[1] > "12:00") {
                    $dateObj = new DateTime($parts[0]);
                    $bean->end_date = $timedate->asUserDate($dateObj, false, $current_user);
                } else {
                    $dateObj = new DateTime($parts[0]);
                    $dateObj->modify("previous day");
                    $bean->end_date = $timedate->asUserDate($dateObj, false, $current_user);
                }
            }
        }
    }
}