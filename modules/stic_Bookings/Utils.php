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
class stic_BookingsUtils
{
    /**
     * Prepares periodic booking data for a confirmation step, storing it in the session without saving records.
     * This function replaces the initial record creation logic.
     *
     * @return void
     */
    public static function preparePeriodicBookingsForConfirmation()
    {
        require_once 'modules/stic_Bookings/controller.php';
        global $sugar_config, $app_list_strings, $current_user, $timedate, $db;

        // Get the data from the form
        $repeat_type = $_REQUEST['repeat_type'] ?: null;
        $interval = isset($_REQUEST['repeat_interval']) ? $_REQUEST['repeat_interval'] : '1';
        $count = isset($_REQUEST['repeat_count']) ? $_REQUEST['repeat_count'] : null;
        $until = isset($_REQUEST['repeat_until']) ? $_REQUEST['repeat_until'] : null;
        $startDay = $_REQUEST['start_date'];
        $finalDay = $_REQUEST['end_date'];
        $all_day = isset($_REQUEST['all_day']) ? $_REQUEST['all_day'] : '0';
        $place_booking = isset($_REQUEST['place_booking']) ? $_REQUEST['place_booking'] : '0';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
        $until = isset($_REQUEST['repeat_until']) ? $_REQUEST['repeat_until'] : null;
        $name = $_REQUEST['name'];
        $description = $_REQUEST['description'] ?? '';
        $resourceIds = isset($_REQUEST['resource_id']) ? $_REQUEST['resource_id'] : array();
        $parentId = $_REQUEST['parent_id'] ?? '';
        $parentType = $_REQUEST['parent_type'] ?? '';
        $bookingId = $_REQUEST['record'] ?? '0';

        $until = str_replace('/', '-', $until);
        $until = date('Y-m-d H:i:s', strtotime($until));
        
        if (!empty($all_day) && $all_day == 1) {
            $startDay = str_replace('/', '-', $startDay);
            $startDay = date('Y-m-d 00:00:00', strtotime($startDay));
        
            $finalDay = str_replace('/', '-', $finalDay);
            $finalDay = date('Y-m-d 23:59:59', strtotime($finalDay));
        } else {
            $startDay = str_replace('/', '-', $startDay);
            $startDay = date('Y-m-d H:i:s', strtotime($startDay));
        
            $finalDay = str_replace('/', '-', $finalDay);
            $finalDay = date('Y-m-d H:i:s', strtotime($finalDay));
        }
        
        $duration = strtotime($finalDay) - strtotime($startDay);
        
        $date = [];
        if ($repeat_type == 'Daily') {
            $firstDay = $startDay;
            if ($count != '' && $count != '0') {
                for ($i = 0; $i < $count; $i++) {
                    $date[$i] = $firstDay;
                    $firstDay = date('Y-m-d H:i:s', strtotime($firstDay . " + $interval days"));
                }
            } else if ($until != '') {
                $first_d = date("Y-m-d", strtotime($firstDay));
                for ($i = 0; strtotime($first_d) <= strtotime($until); $i++) {
                    $date[$i] = $firstDay;
                    $firstDay = date('Y-m-d H:i:s', strtotime($firstDay . " + $interval days"));
                    $first_d = date("Y-m-d", strtotime($firstDay));
                }
            }
        }
        if ($repeat_type == 'Monthly') {
            $firstMonth = $startDay;
            if ($count != '' && $count != '0') {
                for ($i = 0; $i < $count; $i++) {
                    $date[$i] = $firstMonth;
                    $firstMonth = date('Y-m-d H:i:s', strtotime($firstMonth . " + $interval months"));
                }
            } else if ($until != '') {
                $firstM = date("Y-m-d", strtotime($firstMonth));
                for ($i = 0; strtotime($firstM) <= strtotime($until); $i++) {
                    $date[$i] = $firstMonth;
                    $firstMonth = date('Y-m-d H:i:s', strtotime($firstMonth . " + $interval months"));
                    $firstM = date("Y-m-d", strtotime($firstMonth));
                }
            }
        }
        if ($repeat_type == 'Yearly') {
            $firstYear = $startDay;
            if ($count != '' && $count != '0') {
                for ($i = 0; $i < $count; $i++) {
                    $date[$i] = $firstYear;
                    $firstYear = date('Y-m-d H:i:s', strtotime($firstYear . " + $interval years"));
                }
            } else if ($until != '') {
                $firstY = date("Y-m-d", strtotime($firstYear));
                for ($i = 0; strtotime($firstY) <= strtotime($until); $i++) {
                    $date[$i] = $firstYear;
                    $firstYear = date('Y-m-d H:i:s', strtotime($firstYear . " + $interval years"));
                    $firstY = date("Y-m-d", strtotime($firstYear));
                }
            }
        }
        if ($repeat_type == 'Weekly') {
            $times = 0;
            for ($i = 1; $i < 7; $i++) {
                $dow[$i] = $_REQUEST['repeat_dow_' . $i] ?? '';
                if ($dow[$i] == 'on') {
                    $times = $times + 1;
                    $dow[$i] = 1;
                } else {
                    $dow[$i] = 0;
                }
            }
            $zero = 0;
            $dow[7] = $_REQUEST['repeat_dow_' . $zero] ?? '';
            if ($dow[7] == 'on') {
                $times = $times + 1;
                $dow[7] = 1;
            } else {
                $dow[7] = 0;
            }

            if ($times > '0') {
                $days = array('1', '2', '3', '4', '5', '6', '7');
                $firstWeek = $startDay;
                $week = 1;
                $i = 0;
                if ($count != '' && $count != '0') {
                    while ($i < $count) {
                        if ($week == '1') {
                            $firstDate = $firstWeek;
                            $weekDay = $days[date('w', strtotime($firstDate))];
                            $weekDay2 = $weekDay;
                            if ($weekDay == '1') {
                                $weekDay = '7';
                            } else {
                                $weekDay = $weekDay - 1;
                            }
                            for ($x = $weekDay; $x < 8; $x++) {
                                if ($dow[$x] == 1) {
                                    $adder = $x - $weekDay;
                                    $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $adder days"));
                                    $date[$i] = $firstWeek;
                                    $i = $i + 1;
                                    if ($i == $count) {
                                        $x = 8;
                                    }
                                }
                            }
                            $week = '2';
                            if ($interval > 1) {
                                $x = $interval - 1;
                            } else {
                                $x = '0';
                            }
                            if ($weekDay2 == '1') {
                                $weekDay2 = '8';
                                $x = 0;
                            }
                            $differenceDay = 8 - $weekDay2;
                            $firstWeek = date('Y-m-d H:i:s', strtotime($startDay . " + $x weeks + $differenceDay days"));
                        }

                        if ($week == '2' && $i < $count) {
                            $newWeek = $firstWeek;
                            for ($x = 1; $x < 8; $x++) {
                                if ($dow[$x] == 1) {
                                    $addDays = $x;
                                    $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $addDays days"));
                                    $date[$i] = $firstWeek;
                                    $i = $i + 1;
                                    if ($i == $count) {
                                        $x = 8;
                                    }
                                }
                            }
                            $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $interval weeks"));
                        }
                    }
                } else if ($until != '') {
                    $firstWeek = $startDay;
                    $week = 1;
                    $i = 0;

                    while (strtotime($firstWeek) <= strtotime($until)) {
                        if ($week == '1') {
                            $startDay = $timedate->asDb($timedate->fromString($startDay), $current_user);
                            $finalDay = $timedate->asDb($timedate->fromString($finalDay), $current_user);
                            $firstDate = $firstWeek;
                            $weekDay = $days[date('w', strtotime($firstDate))];
                            $weekDay2 = $weekDay;
                            if ($weekDay == '1') {
                                $weekDay = '7';
                            } else {
                                $weekDay = $weekDay - 1;
                            }
                            for ($x = $weekDay; $x < 8; $x++) {
                                if ($dow[$x] == 1) {
                                    $adder = $x - $weekDay;
                                    $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $adder days"));
                                    $date[$i] = $firstWeek;
                                    $i = $i + 1;
                                    if (strtotime($firstWeek) == strtotime($until)) {
                                        $x = 8;
                                    }
                                }
                            }
                            $week = '2';
                            if ($interval > 1) {
                                $x = $interval - 1;
                            } else {
                                $x = '0';
                            }
                            if ($weekDay2 == '1') {
                                $weekDay2 = '8';
                                $x = 0;
                            }
                            $differenceDay = 8 - $weekDay2;
                            $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $x weeks + $differenceDay days"));
                        }
                        if ($week == '2' && strtotime($firstWeek) <= strtotime($until)) {
                            $newWeek = $firstWeek;
                            for ($x = 1; $x < 8; $x++) {
                                if ($dow[$x] == 1) {
                                    $addDays = $x;
                                    $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $addDays days"));

                                    if ($until >= substr($firstWeek, 0, 10)) {
                                        $date[$i] = $firstWeek;
                                    }

                                    $i = $i + 1;
                                    if (strtotime($firstWeek) == strtotime($until)) {
                                        $x = 8;
                                    }
                                }
                            }

                            $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $interval weeks"));
                        }
                    }
                }
            } else {
                $firstWeek = $startDay;
                if ($count != '' && $count != '0') {
                    for ($i = 0; $i < $count; $i++) {
                        $date[$i] = $firstWeek;
                        $firstWeek = date('Y-m-d H:i:s', strtotime($firstWeek . " + $interval weeks"));
                    }
                } else if ($until != '') {
                    $firstW = date("Y-m-d", strtotime($firstWeek));
                    for ($i = 0; strtotime($firstW) <= strtotime($until); $i++) {
                        $date[$i] = $firstWeek;
                        $firstWeek = date('Y-m-d H:i:s', strtotime($firstWeek . " + $interval weeks"));
                        $firstW = date("Y-m-d", strtotime($firstWeek));
                    }
                }
            }
        }

        $aux = array();
        $counter = count($date);
        for ($i = 0; $i < $counter; $i++) {
            $aux[$i] = $timedate->to_db($timedate->to_display_date_time($date[$i], true, false, $current_user));
        }

        $summary['global'] = array(
            'totalRecordsProcessed' => 0,
            'totalRecordsCreated' => 0,
            'totalRecordsNotCreated' => 0,
        );

        $resourceNames = array();
        foreach ($resourceIds as $resourceId) {
            $resource = BeanFactory::getBean('stic_Resources', $resourceId);
            if ($resource) {
                $resourceNames[$resourceId] = $resource->name;
            }
        }

        $summary['resources'] = array();
        foreach ($resourceIds as $resourceId) {
            $summary['resources'][$resourceId] = array(
                'name' => $resourceNames[$resourceId] ?? "Recurso {$resourceId}",
                'numRecordsProcessed' => 0,
                'numRecordsCreated' => 0,
                'numRecordsNotCreated' => 0,
                'recordsCreated' => array(),
                'recordsNotCreated' => array(),
            );
        }

        $all_booking_attempts = array();
        $bookingsToConfirm = array(); 
        $controller = new stic_BookingsController();

        $code = null;
        $bookingCounter = 0; 
        
        $availableResourcesInThisBatch = [];

        for ($i = 0; $i < $counter; $i++) {
            $summary['global']['totalRecordsProcessed']++;

            $current_start_date_php = $date[$i];
            $current_end_date_php = date('Y-m-d H:i:s', strtotime($current_start_date_php) + $duration);
            
            $resourceAvailability = array();
            $allResourcesAvailable = true;

            foreach ($resourceIds as $resourceId) {
                $availabilityDb = $controller->checkResourceAvailability($resourceId, $current_start_date_php, $current_end_date_php, $bookingId);
                
                $availabilityBatch = true;
                if (isset($availableResourcesInThisBatch[$resourceId])) {
                    foreach ($availableResourcesInThisBatch[$resourceId] as $bookingTime) {
                        $start = $bookingTime['start'];
                        $end = $bookingTime['end'];
                        
                        if (strtotime($current_start_date_php) < strtotime($end) && strtotime($current_end_date_php) > strtotime($start)) {
                            $availabilityBatch = false;
                            break;
                        }
                    }
                }
                
                $isResourceAvailable = $availabilityDb['resources_allowed'] && $availabilityBatch;
                $resourceAvailability[$resourceId] = $isResourceAvailable;

                if (!$isResourceAvailable) {
                    $allResourcesAvailable = false;
                    break;
                }
            }
            
            if ($allResourcesAvailable) {
                foreach ($resourceIds as $resourceId) {
                    if (!isset($availableResourcesInThisBatch[$resourceId])) {
                        $availableResourcesInThisBatch[$resourceId] = [];
                    }
                    $availableResourcesInThisBatch[$resourceId][] = ['start' => $current_start_date_php, 'end' => $current_end_date_php];
                }
            }

            $startDate = $timedate->fromDbFormat($aux[$i], TimeDate::DB_DATETIME_FORMAT);
            $endDateObj = $timedate->fromDbFormat(date('Y-m-d H:i:s', strtotime($aux[$i]) + $duration), TimeDate::DB_DATETIME_FORMAT);

            if ($startDate === false || $endDateObj === false) {
                 continue;
            }
             
            $startDateDisplay = $timedate->asUser($startDate, $current_user);
            $endDateDisplay = $timedate->asUser($endDateObj, $current_user);
            
            $bookingName = '';
            if ($allResourcesAvailable) {
                $bookingCounter++;
                if (empty($name)) {
                    if (!$code) {
                        $query = "SELECT code FROM stic_bookings ORDER BY code DESC LIMIT 1";
                        $result = $db->query($query, true);
                        $row = $db->fetchByAssoc($result);
                        $lastNum = $row['code'] ?? 0;
                        $code = $lastNum;
                    }
                    $code++;
                    $mod_strings = return_module_language($GLOBALS['current_language'], 'stic_Bookings');
                    $bookingName = $mod_strings['LBL_MODULE_NAME_SINGULAR'] . ' ' . str_pad($code, 5, "0", STR_PAD_LEFT) . ' (' . $bookingCounter . ')';
                } else {
                    $bookingName = $name . ' (' . $bookingCounter . ')';
                }
            } else {
                $bookingName = empty($name) ? 'Booking no disponible' : $name;
            }
            
            $bookingRecord = array(
                'start_date_db' => $aux[$i],
                'end_date_db' => date('Y-m-d H:i:s', strtotime($aux[$i]) + $duration),
                'start_date_display' => $startDateDisplay,
                'end_date_display' => $endDateDisplay,
                'all_day' => $all_day,
                'status' => $status,
                'repeat_type' => $repeat_type,
                'place_booking' => $place_booking,
                'description' => $description,
                'parent_id' => $parentId,
                'parent_type' => $parentType,
                'resourceIds' => $resourceIds,
                'allResourcesAvailable' => $allResourcesAvailable,
                'name' => $bookingName,
                'resource_names' => array_values($resourceNames)
            );

            if ($allResourcesAvailable) {
                $summary['global']['totalRecordsCreated']++;

                foreach ($resourceIds as $resourceId) {
                    $summary['resources'][$resourceId]['numRecordsCreated']++;
                    $summary['resources'][$resourceId]['recordsCreated'][] = array(
                        'bookingName' => $bookingName,
                        'resourceName' => $resourceNames[$resourceId],
                        'startDate' => $startDateDisplay,
                        'endDate' => $endDateDisplay,
                        'status' => $status,
                        'allRequestedResources' => array_values($resourceNames),
                    );
                }

                $recordKey = $startDateDisplay . '_' . $endDateDisplay;
                $all_booking_attempts[$recordKey] = array(
                    'status' => 'created',
                    'bookingName' => $bookingName,
                    'startDate' => $startDateDisplay,
                    'endDate' => $endDateDisplay,
                    'allRequestedResources' => array_values($resourceNames),
                );
                
            } else {
                $summary['global']['totalRecordsNotCreated']++;

                foreach ($resourceIds as $resourceId) {
                    $isThisResourceAvailable = $resourceAvailability[$resourceId];
                    
                    if (!$isThisResourceAvailable) {
                        $summary['resources'][$resourceId]['numRecordsNotCreated']++;
                        
                        $unavailableResources = array();
                        foreach ($resourceAvailability as $resId => $available) {
                            if (!$available) {
                                $unavailableResources[] = $resourceNames[$resId];
                            }
                        }

                        $summary['resources'][$resourceId]['recordsNotCreated'][] = array(
                            'resourceName' => $resourceNames[$resourceId],
                            'startDate' => $startDateDisplay,
                            'endDate' => $endDateDisplay,
                            'unavailableResources' => $unavailableResources,
                            'allRequestedResources' => array_values($resourceNames),
                            'thisResourceAvailable' => $isThisResourceAvailable,
                        );
                    }
                }
                $recordKey = $startDateDisplay . '_' . $endDateDisplay;
                $unavailableResources = array();
                foreach ($resourceAvailability as $resId => $available) {
                    if (!$available) {
                        $unavailableResources[] = $resourceNames[$resId];
                    }
                }
                $all_booking_attempts[$recordKey] = array(
                    'status' => 'not_created',
                    'startDate' => $startDateDisplay,
                    'endDate' => $endDateDisplay,
                    'unavailableResources' => $unavailableResources,
                    'allRequestedResources' => array_values($resourceNames),
                );
            }
            
            $bookingsToConfirm[] = $bookingRecord;
        }

        $_SESSION['bookings_to_confirm'] = $bookingsToConfirm;
        $_SESSION['consolidated_summary'] = array_values($all_booking_attempts);
        $_SESSION['summary'] = $summary;
    }

    /**
     * Creates periodic booking records from the data stored in the session after confirmation.
     *
     * @return void
     */
    public static function savePeriodicBookingsFromConfirmation()
    {
        global $sugar_config, $current_user, $timedate;
        $aodConfig = $sugar_config['aod']['enable_aod'] ?? false;
        $sugar_config['aod']['enable_aod'] = false;

        $bookings_to_create = $_SESSION['bookings_to_confirm'] ?? [];
        if (empty($bookings_to_create)) {
            return;
        }

        foreach ($bookings_to_create as $booking_info) {
            // Only save the records that were marked as available
            if ($booking_info['allResourcesAvailable']) {
                $bookingBean = BeanFactory::newBean('stic_Bookings');

                $bookingBean->name = $booking_info['name'];
                $bookingBean->start_date = $booking_info['start_date_db'];
                $bookingBean->end_date = $booking_info['end_date_db'];
                $bookingBean->all_day = $booking_info['all_day'];
                $bookingBean->status = $booking_info['status'];
                $bookingBean->repeat_type = $booking_info['repeat_type'];
                $bookingBean->place_booking = $booking_info['place_booking'];
                $bookingBean->description = $booking_info['description'];

                if (!empty($booking_info['parent_id']) && !empty($booking_info['parent_type'])) {
                    $bookingBean->parent_id = $booking_info['parent_id'];
                    $bookingBean->parent_type = $booking_info['parent_type'];
                }

                $bookingBean->assigned_user_id = $current_user->id;
                $createdBookingId = $bookingBean->save();
                
                if ($bookingBean->load_relationship('stic_resources_stic_bookings')) {
                    foreach ($booking_info['resourceIds'] as $resourceId) {
                        $bookingBean->stic_resources_stic_bookings->add($resourceId);
                    }
                }
            }
        }

        $sugar_config['aod']['enable_aod'] = $aodConfig;
        
        // After saving, clean up the session data.
        unset($_SESSION['bookings_to_confirm']);
    }

    /**
     * This is the original function. It now acts as a redirector to the new confirmation flow.
     */
    public static function createPeriodicBookingsRecords()
    {
        global $sugar_config;
        $aodConfig = $sugar_config['aod']['enable_aod'] ?? false;
        $sugar_config['aod']['enable_aod'] = false;
        $startTime = microtime(true);
        
        // Call the new function to prepare data for confirmation
        self::preparePeriodicBookingsForConfirmation();

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Has prepared the booking records in $totalTime seconds, waiting for confirmation");

        $sugar_config['aod']['enable_aod'] = $aodConfig;
        
        header("Location: index.php?module=stic_Bookings&action=bookingsAssistantSummary");
        exit();
    }
}