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
     * Creates periodic booking records based on the parameters received via $_REQUEST
     * and defined in the periodic bookings creation wizard
     *
     * @return void
     */
    public static function createPeriodicBookingsRecords()
    {
        require_once 'modules/stic_Bookings/controller.php';
        // Disable Advanced Open Discovery to avoid slowing down the writing of the records
        global $sugar_config, $app_list_strings, $current_user, $timedate, $db;
        $aodConfig = $sugar_config['aod']['enable_aod'] ?? false;
        $sugar_config['aod']['enable_aod'] = false;
        $startTime = microtime(true);

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
        
        // startDay y finalDay según all_day
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
        
        // Calcular duración
        $duration = strtotime($finalDay) - strtotime($startDay);
        
        // Calculate recurring dates
        $date = [];
        if ($repeat_type == '') {
            // IMPORTANT CHANGE: Redirect to EditView instead of index
            header("Location: index.php?module=stic_Bookings&action=EditView&record=$bookingId");
            return;
        } else {
            // Daily
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
            // Monthly
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
            // Yearly
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
            // Weekly
            if ($repeat_type == 'Weekly') {
                // Create the array of days of the week selected
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
        }

        // Convert dates to database format
        $aux = array();
        $counter = count($date);
        for ($i = 0; $i < $counter; $i++) {
            $aux[$i] = $timedate->to_db($timedate->to_display_date_time($date[$i], true, false, $current_user));
        }

        // Initialize summary structure
        $summary['global'] = array(
            'totalRecordsProcessed' => 0,
            'totalRecordsCreated' => 0,
            'totalRecordsNotCreated' => 0,
        );

        // Get resource names and initialize resources structure
        $resourceNames = array();
        foreach ($resourceIds as $resourceId) {
            $resource = BeanFactory::getBean('stic_Resources', $resourceId);
            if ($resource) {
                $resourceNames[$resourceId] = $resource->name;
            }
        }

        // Nueva estructura para resources - por cada recurso individual
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
        $controller = new stic_BookingsController();

        // Create booking records
        for ($i = 0; $i < $counter; $i++) {
            $summary['global']['totalRecordsProcessed']++;

            // Se calcula la fecha final de cada reserva periódica
            $current_endDate = date('Y-m-d H:i:s', strtotime($aux[$i]) + $duration);

            // Check availability for each resource individually
            $resourceAvailability = array();
            $allResourcesAvailable = true;

            foreach ($resourceIds as $resourceId) {
                // Se pasa la fecha de inicio y final calculadas para esta iteración
                $availability = $controller->checkResourceAvailability($resourceId, $aux[$i], $current_endDate, $bookingId);
                $resourceAvailability[$resourceId] = $availability['resources_allowed'];

                if (!$availability['resources_allowed']) {
                    $allResourcesAvailable = false;
                    break;
                }
            }

            // Update processed count for each resource
            foreach ($resourceIds as $resourceId) {
                $summary['resources'][$resourceId]['numRecordsProcessed']++;
            }

            // Prepare date information for both created and not created records
            $startDate = $timedate->fromDbFormat($aux[$i], TimeDate::DB_DATETIME_FORMAT);
            $startDate = $timedate->asUser($startDate, $current_user);
            $endDateObj = $timedate->fromDbFormat($current_endDate, TimeDate::DB_DATETIME_FORMAT);
            $endDateDisplay = $timedate->asUser($endDateObj, $current_user);

            if ($allResourcesAvailable) {
                // Create the booking
                $bookingBean = BeanFactory::newBean('stic_Bookings');

                if (empty($name)) {
                    $currentNum = $code ?? null;
                    if (!$currentNum) {
                        // Get last assigned code
                        $query = "SELECT code
                        FROM stic_bookings
                        ORDER BY code DESC LIMIT 1";
                        $result = $db->query($query, true);
                        $row = $db->fetchByAssoc($result);
                        $lastNum = $row['code'];
                        if (!isset($lastNum) || empty($lastNum)) {
                            $lastNum = 0;
                        }
                        $currentNum = $lastNum + 1;
                    }
                    // Format code
                    $currentNum = str_pad($currentNum, 5, "0", STR_PAD_LEFT);
                    // Build booking name
                    $mod_strings = return_module_language($current_language, 'stic_Bookings');

                    $bookingBean->name = $mod_strings['LBL_MODULE_NAME_SINGULAR'] . ' ' . $currentNum . ' (' . ($i + 1) . ')';
                } else {
                    $bookingBean->name = $name . ' (' . ($i + 1) . ')';
                }

                $bookingBean->start_date = $aux[$i];
                $bookingBean->end_date = $current_endDate;
                $bookingBean->all_day = $all_day;
                $bookingBean->status = $status;
                $bookingBean->repeat_type = $repeat_type;
                $bookingBean->place_booking = $place_booking;
                $bookingBean->description = $description;

                if (!empty($parentId) && !empty($parentType)) {
                    $bookingBean->parent_id = $parentId;
                    $bookingBean->parent_type = $parentType;
                }

                $bookingBean->assigned_user_id = $current_user->id;
                $createdBookingId = $bookingBean->save();

                // Associate resources with the booking
                if ($bookingBean->load_relationship('stic_resources_stic_bookings')) {
                    foreach ($resourceIds as $resourceId) {
                        $bookingBean->stic_resources_stic_bookings->add($resourceId);
                    }
                }

                $summary['global']['totalRecordsCreated']++;

                // Add record to each resource's created records
                foreach ($resourceIds as $resourceId) {
                    $summary['resources'][$resourceId]['numRecordsCreated']++;
                    $summary['resources'][$resourceId]['recordsCreated'][] = array(
                        'bookingId' => $createdBookingId,
                        'bookingName' => $bookingBean->name,
                        'resourceName' => $resourceNames[$resourceId],
                        'startDate' => $startDate,
                        'endDate' => $endDateDisplay,
                        'status' => $status,
                        'allRequestedResources' => array_values($resourceNames),
                    );
                }

                // Add to consolidated summary
                $recordKey = $startDate . '_' . $endDateDisplay;
                $all_booking_attempts[$recordKey] = array(
                    'status' => 'created',
                    'bookingId' => $createdBookingId,
                    'bookingName' => $bookingBean->name,
                    'startDate' => $startDate,
                    'endDate' => $endDateDisplay,
                    'allRequestedResources' => array_values($resourceNames),
                );

            } else {
                $summary['global']['totalRecordsNotCreated']++;

                // Add record to each resource's not created records (with individual availability info)
                foreach ($resourceIds as $resourceId) {
                    $isThisResourceAvailable = $resourceAvailability[$resourceId];
                    
                    if (!$isThisResourceAvailable) {
                        $summary['resources'][$resourceId]['numRecordsNotCreated']++;
                        
                        // Get unavailable resources from the perspective of this resource
                        $unavailableResources = array();
                        foreach ($resourceAvailability as $resId => $available) {
                            if (!$available) {
                                $unavailableResources[] = $resourceNames[$resId];
                            }
                        }

                        $summary['resources'][$resourceId]['recordsNotCreated'][] = array(
                            'resourceName' => $resourceNames[$resourceId],
                            'startDate' => $startDate,
                            'endDate' => $endDateDisplay,
                            'unavailableResources' => $unavailableResources,
                            'allRequestedResources' => array_values($resourceNames),
                            'thisResourceAvailable' => $isThisResourceAvailable,
                        );
                    }
                }

                // Add to consolidated summary
                $recordKey = $startDate . '_' . $endDateDisplay;
                $unavailableResources = array();
                foreach ($resourceAvailability as $resId => $available) {
                    if (!$available) {
                        $unavailableResources[] = $resourceNames[$resId];
                    }
                }
                $all_booking_attempts[$recordKey] = array(
                    'status' => 'not_created',
                    'startDate' => $startDate,
                    'endDate' => $endDateDisplay,
                    'unavailableResources' => $unavailableResources,
                    'allRequestedResources' => array_values($resourceNames),
                );
            }
        }

        $consolidated_summary = array_values($all_booking_attempts);
        $_SESSION['consolidated_summary'] = $consolidated_summary;
        $_SESSION['summary'] = $summary;
        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Has been created {$summary['global']['totalRecordsCreated']} booking records in $totalTime seconds");

        // Reactivate previous Advanced Open Discovery configuration
        $sugar_config['aod']['enable_aod'] = $aodConfig;
        
        // Always redirect to summary view
        header("Location: index.php?module=stic_Bookings&action=bookingsAssistantSummary");
        exit(); 
    }
}