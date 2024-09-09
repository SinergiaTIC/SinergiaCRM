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

//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_Bookings_Places_CalendarController extends SugarController
{
    /**
     * This action is called by the FullCalendar and retrieves a collection of
     * booked or available resources (depending on user's settings) in a certain time range.
     *
     * @return void
     */
    public function action_getResources()
    {
        global $current_user, $timedate;

        // Parse the timeZone parameter if it is present.
        $userTimeZone = $current_user->getPreference('timezone');
        if (empty($userTimeZone)) {
            if (isset($_GET['timeZone'])) {
                $userTimeZone = new DateTimeZone($_GET['timeZone']);
            } else {
                $tz = substr($_GET['start'], -6, 6);
                $userTimeZone = new DateTimeZone($tz);
            }
        } else {
            $userTimeZone = new DateTimeZone($userTimeZone);
        }

        // Parse the dates that arrive from the FullCalendar
        $startDate = new DateTime($_GET['start']);
        $startDate = $timedate->to_display_date_time(date_format($startDate, 'Y-m-d H:i:s'), false, false, $current_user);
        $startDate = $timedate->fromUser($startDate, $current_user);
        $startDate = $startDate->asDb();

        $endDate = new DateTime($_GET['end']);
        $endDate = $timedate->to_display_date_time(date_format($endDate, 'Y-m-d H:i:s'), false, false, $current_user);
        $endDate = $timedate->fromUser($endDate, $current_user);
        $endDate = $endDate->asDb();

        $range_start = parseDateTime($startDate);
        $range_end = parseDateTime($endDate);

        // Get configuration params from the user configuration
        $userBean = new UserPreference($current_user);

        // stic_bookings_calendar_availability_mode: sets if the user will see resources availability or existing bookings
        if (!$availabilityMode = $_REQUEST['availabilityMode']) {
            $availabilityMode = $userBean->getPreference('stic_bookings_calendar_availability_mode');
        }

        // stic_bookings_calendar_filtered_resources: the default list of resources the user wants to see
        if (!isset($_REQUEST['filteredResources']) || empty($_REQUEST['filteredResources'])) {
            $filteredResources = $userBean->getPreference('stic_bookings_calendar_filtered_resources');
        } else {
            $filteredResources = explode(',', $_REQUEST['filteredResources']);
        }

        // Get the data to be displayed in the calendar according to stic_bookings_calendar_availability_mode param
        $calendarItems = array();
        if ($availabilityMode == "true") {
            $calendarItems = $this->getResourcesAvailability($startDate, $endDate, $filteredResources);
        } else {
            $calendarItems = $this->getBookedResources($startDate, $endDate, $filteredResources);
        }

        // Convert calendar items into calendar printable objects.
        $calendarObjects = array();
        foreach ($calendarItems as $calendarItem) {
            $calendarObject = new CalendarObject($calendarItem, $userTimeZone);
            // If the CalendarObject is in-bounds, add it to the output.
            // Probably we don't need this here because we are filtering the bookings before, but we leave it just in case.
            if ($calendarObject->isWithinDayRange($range_start, $range_end)) {
                $calendarObjects[] = $calendarObject->toArray();
            }
        }
        echo json_encode($calendarObjects);

        // Stop the code here to avoid sending unnecessary headers to the request
        die();
    }

    /**
     * Action used to save the user preferences set by the user related to the Bookings Calendar module
     *
     * @return void
     */
    public function action_saveUserPreferences()
    {
        global $current_user;
        if (!$userPreference = $_POST['user_preference']) {
            echo false;
            die();
        }
        if (!isset($_POST['preference_value'])) {
            $_POST['preference_value'] = '';
        }
        $preferenceValue = $_POST['preference_value'];

        require_once 'modules/UserPreferences/UserPreference.php';
        $userBean = new UserPreference($current_user);
        $userBean->setPreference($userPreference, $preferenceValue);
        echo true;
        die();
    }

    /**
     * Returns the booked resources and their details
     *
     * @param String $start_date
     * @param String $end_date
     * @param Array $filteredResources
     * @return void
     */
    // private function getBookedResources($start_date, $end_date, $filteredResources)
    // {
    //     global $current_user;
    //     $resourcesBean = BeanFactory::getBean('stic_Resources');
    //     $resources = $resourcesBean->get_full_list('name');
    //     $bookedResources = array();
    //     $query = "stic_bookings.end_date >= '$start_date' AND stic_bookings.start_date <= '$end_date' AND stic_bookings.status != 'cancelled'";

    //     foreach ($resources as $resource) {
    //         if (!$filteredResources || in_array($resource->id, $filteredResources)) {
    //             $relBeans = $resource->get_linked_beans(
    //                 'stic_resources_stic_bookings',
    //                 '',
    //                 '',
    //                 0,
    //                 0,
    //                 0,
    //                 $query,
    //             );
    //             foreach ($relBeans as $relBean) {
    //                 $status = $relBean->status;
    //                 $bookedResources[] = array(
    //                     'title' => $resource->name . ' - ' . str_pad($relBean->code, 5, "0", STR_PAD_LEFT),
    //                     'resourceName' => $resource->name,
    //                     'module' => $relBean->module_name,
    //                     'id' => $relBean->id,
    //                     'recordId' => $relBean->id,
    //                     'resourceId' => $resource->id,
    //                     'resourcePlaceType' => $resource->place_type,
    //                     'resourcePlaceUserType' => $resource->user_type,
    //                     'resourcePlaceGenderType' => $resource->gender,
    //                     'resourceType' => $resource->type,
    //                     'resourceCenterName' => $resource->stic_resources_stic_centers_name,
    //                     'resourceCenterId' => $resource->stic_resources_stic_centersstic_centers_ida,

    //                     // 'backgroundColor' => $resource->color,
    //                     // 'borderColor' => $resource->color,
    //                     'allDay' => $relBean->all_day,
    //                     'start' => $relBean->fetched_row['start_date'],
    //                     'end' => $relBean->fetched_row['end_date'],
    //                     // Classname is defined this way in order to paint each calendar object using the resource's color
    //                     'className' => 'id-' . $resource->id,
    //                 );
    //             }
    //         }
    //     }
    //     return $bookedResources;
    // }
    private function getBookedResources($start_date, $end_date, $filteredResources)
    {
        global $current_user, $db;
        $resourcesBean = BeanFactory::getBean('stic_Resources');
        $bookedResources = array();
        $query = "
            SELECT
                sr.id AS resource_id,
                sr.name AS resource_name,
                sr.place_type,
                sr.user_type,
                sr.gender,
                sr.type,
                sb.id AS booking_id,
                sb.code AS booking_code,
                sb.name AS booking_name,
                sb.start_date,
                sb.end_date,
                sb.all_day,
                sc.id AS center_id,
                sc.name AS center_name
            FROM stic_resources sr
            JOIN stic_resources_stic_bookings_c srsb ON sr.id = srsb.stic_resources_stic_bookingsstic_resources_ida
            JOIN stic_bookings sb ON srsb.stic_resources_stic_bookingsstic_bookings_idb = sb.id
            LEFT JOIN stic_resources_stic_centers_c srsc ON sr.id = srsc.stic_resources_stic_centersstic_resources_idb
            LEFT JOIN stic_centers sc ON srsc.stic_resources_stic_centersstic_centers_ida = sc.id
            WHERE sb.end_date >= '$start_date'
            AND sb.start_date <= '$end_date'
            AND sb.status != 'cancelled'
            AND sb.deleted = 0
            AND sr.deleted = 0
            AND srsb.deleted = 0
        ";

        if (!empty($filteredResources)) {
            $filteredResourcesStr = implode("','", $filteredResources);
            $query .= " AND sr.id IN ('$filteredResourcesStr')";
        }

        $result = $db->query($query);

        while ($row = $db->fetchByAssoc($result)) {
            $bookedResources[] = array(
                'title' => $row['booking_name'] . ' - ' . str_pad($row['booking_code'], 5, "0", STR_PAD_LEFT),
                'resourceName' => $row['resource_name'],
                'module' => 'stic_Bookings',
                'id' => $row['booking_id'],
                'recordId' => $row['booking_id'],
                'resourceId' => $row['resource_id'],
                'resourcePlaceType' => $row['place_type'],
                'resourcePlaceUserType' => $row['user_type'],
                'resourcePlaceGenderType' => $row['gender'],
                'resourceType' => $row['type'],
                'resourceCenterName' => $row['center_name'],
                'resourceCenterId' => $row['center_id'],
                'allDay' => $row['all_day'],
                'start' => $row['start_date'],
                'end' => $row['end_date'],
                'className' => 'id-' . $row['resource_id'],
            );
        }

        return $bookedResources;
    }
    /**
     *  Returns the availability of the existing resources.
     *
     * @param String $start_date
     * @param String $end_date
     * @param Array $filteredResources
     * @return array()
     */
    private function getResourcesAvailability($start_date, $end_date, $filteredResources)
    {
        $resourcesAvailability = array();

        foreach ($filteredResources as $resourceId) {
            $resourceBean = BeanFactory::getBean('stic_Resources', $resourceId);

            $query =
                "SELECT DISTINCT
                date_availability,
                GROUP_CONCAT(DISTINCT availability ORDER BY availability) as superavail
            FROM
            (SELECT
                date_availability,
                if(date_availability BETWEEN start_date AND end_date - INTERVAL 15 MINUTE, 1, 0) as availability
            FROM
            (SELECT
                date('$start_date') + interval (seq * 15) Minute as date_availability,
                booked.start_date,
                booked.end_date
            FROM
                seq_0_to_12000
            LEFT JOIN
                (
                select
                    name,
                    start_date,
                    end_date
                from
                    stic_bookings sb
                join stic_resources_stic_bookings_c srsbc on
                    srsbc.stic_resources_stic_bookingsstic_bookings_idb = sb.id
                WHERE
                    srsbc.stic_resources_stic_bookingsstic_resources_ida = '$resourceBean->id'
                    AND srsbc.deleted = 0
                    AND sb.deleted = 0
                    AND sb.end_date >= '$start_date'
                    AND sb.start_date <= '$end_date'
                    AND sb.status != 'cancelled') booked
            ON
                1 = 1) main) supermain
            GROUP BY date_availability
            HAVING superavail = '0';";
            $db = DBManagerFactory::getInstance();
            $res = $db->query($query);
            $row = $db->fetchByAssoc($res);
            $startDate = $row['date_availability'];

            $lastDate = $row['date_availability'];
            while ($row = $db->fetchByAssoc($res)) {
                if (strtotime($row['date_availability']) - strtotime($lastDate) > 15 * 60) {
                    // 15 mins has passed
                    $resourcesAvailability[] = array(
                        'title' => $resourceBean->name,
                        'resourceName' => $resourceBean->name,
                        'module' => $resourceBean->module_name,
                        'recordId' => $resourceBean->id,
                        'resourceId' => $resourceBean->id,
                        'start' => $startDate,
                        'end' => $lastDate,
                        // Classname is defined this way in order to paint each calendar object using the resource's color
                        'className' => 'id-' . $resourceBean->id,
                    );
                    $startDate = $row['date_availability'];
                    $lastDate = $row['date_availability'];

                } else {
                    $lastDate = $row['date_availability'];
                }
            }
            if ($startDate != $lastDate) {
                $resourcesAvailability[] = array(
                    'title' => $resourceBean->name,
                    'resourceName' => $resourceBean->name,
                    'module' => $resourceBean->module_name,
                    'recordId' => $resourceBean->id,
                    'resourceId' => $resourceBean->id,
                    'start' => $startDate,
                    'end' => $lastDate,
                    // Classname is defined this way in order to paint each calendar object using the resource's color
                    'className' => 'id-' . $resourceBean->id,
                );
            }

        }
        return $resourcesAvailability;
    }

    public function action_get_places_availability_data()
    {
        global $current_user, $timedate;
        $savedFilters = json_decode($current_user->getPreference('stic_bookings_places_calendar_filters'), true) ?? [];

        if (!isset($_POST['start']) || !isset($_POST['end'])) {
            echo json_encode(array('error' => 'Please provide start and end dates.'));
            die();
        }

        $startDate = $_POST['start'];
        $endDate = $_POST['end'];

        if (!$this->validateDate($startDate, 'Y-m-d') || !$this->validateDate($endDate, 'Y-m-d')) {
            echo json_encode(array('error' => 'Invalid date format. Please use YYYY-MM-DD.'));
            die();
        }

        $sticPlacesUser = $_POST['stic_resources_places_users_list'] ?? $savedFilters['stic_resources_places_users_list'] ?? [];
        $sticPlacesType = $_POST['stic_resources_places_type_list'] ?? $savedFilters['stic_resources_places_type_list'] ?? [];
        $sticPlacesGender = $_POST['stic_resources_places_gender_list'] ?? $savedFilters['stic_resources_places_gender_list'] ?? [];

        $filteredResources = $this->getFilteredResources($sticPlacesUser, $sticPlacesType, $sticPlacesGender);

        $bookedResources = $this->getBookedResources($startDate, $endDate, $filteredResources);
        $availableResources = $this->getPlacesAvailability($startDate, $endDate, $filteredResources);

        $result = array();
        $dates = $this->getDatesArray($startDate, $endDate);

        foreach ($dates as $date) {
            $result[$date] = array(
                'occupied' => array(),
                'available' => count($availableResources[$date]),
            );
        }

        foreach ($bookedResources as $resource) {
            $resourceStart = max($startDate, $resource['start']);
            $resourceEnd = min($endDate, $resource['end']);
            $currentDate = $resourceStart;

            if ($resource['resourceType'] == 'places' && in_array($resource['resourceId'], $filteredResources)) {
                while ($currentDate <= $resourceEnd) {
                    $dateKey = date('Y-m-d', strtotime($currentDate));
                    if (isset($result[$dateKey])) {
                        if (!isset($result[$dateKey]['occupied'][$resource['title']])) {
                            $result[$dateKey]['occupied'][$resource['title']] = array();
                        }
                        if (!isset($result[$dateKey]['occupied'][$resource['title']][$resource['resourceCenterName']])) {
                            $result[$dateKey]['occupied'][$resource['title']][$resource['resourceCenterName']] = array();
                        }
                        $result[$dateKey]['occupied'][$resource['title']][$resource['resourceCenterName']][] = $resource['resourceName'];
                    }
                    $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                }
            }
        }

        echo json_encode($result);
        die();
    }
    private function getPlacesAvailability($start_date, $end_date, $filteredResources = null)
    {
        global $db;

        $availablePlaces = array();

        $query = "SELECT stic_resources.id AS resource_id, stic_resources.name AS resource_name, stic_resources.type AS resource_type, stic_centers.id AS center_id,
        stic_centers.name AS center_name
                FROM
                    stic_resources
                JOIN  stic_resources_stic_centers_c
                ON    stic_resources.id = stic_resources_stic_centers_c.stic_resources_stic_centersstic_resources_idb
                JOIN
                    stic_centers
                ON    stic_centers.id = stic_resources_stic_centers_c.stic_resources_stic_centersstic_centers_ida
                WHERE
                    stic_resources.deleted = 0 AND stic_resources.type = 'places'";
        // Filters are added
        $query .= " AND stic_resources.id IN ('" . implode("','", $filteredResources) . "')";

        $result = $db->query($query);

        $allResources = array();
        while ($row = $db->fetchByAssoc($result)) {
            $allResources[$row['resource_id']] = array(
                'name' => $row['resource_name'],
                'type' => $row['resource_type'],
                'center_id' => $row['center_id'],
                'center_name' => $row['center_name'],
            );
        }

        // Obtener las reservas existentes
        $query = "SELECT stic_resources_stic_bookingsstic_resources_ida as resource_id,
                     start_date, end_date
              FROM stic_bookings
              JOIN stic_resources_stic_bookings_c ON stic_resources_stic_bookingsstic_bookings_idb = stic_bookings.id
              WHERE stic_bookings.deleted = 0
                AND stic_resources_stic_bookings_c.deleted = 0
                AND start_date <= '$end_date'
                AND end_date >= '$start_date'";
        $result = $db->query($query);

        $bookedResources = array();
        while ($row = $db->fetchByAssoc($result)) {
            $resourceId = $row['resource_id'];
            $startDate = $row['start_date'];
            $endDate = $row['end_date'];

            $currentDate = $startDate;
            while ($currentDate <= $endDate && $currentDate <= $end_date) {
                $dateKey = date('Y-m-d', strtotime($currentDate));
                if (!isset($bookedResources[$dateKey])) {
                    $bookedResources[$dateKey] = array();
                }
                $bookedResources[$dateKey][] = $resourceId;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            }
        }

        // Calcular los recursos disponibles para cada día
        $currentDate = $start_date;
        while ($currentDate <= $end_date) {
            $dateKey = date('Y-m-d', strtotime($currentDate));
            $availablePlaces[$dateKey] = array();

            foreach ($allResources as $resourceId => $resourceInfo) {
                if (!isset($bookedResources[$dateKey]) || !in_array($resourceId, $bookedResources[$dateKey])) {
                    $availablePlaces[$dateKey][] = array(
                        'id' => $resourceId,
                        'name' => $resourceInfo['name'],
                        'type' => $resourceInfo['type'],
                        'center_id' => $resourceInfo['center_id'],
                        'center_name' => $resourceInfo['center_name'],
                    );
                }
            }
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        return $availablePlaces;
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function getDatesArray($startDate, $endDate)
    {
        $dates = array();
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
    public function action_SaveFilters()
    {
        global $current_user, $timedate;

        require_once 'modules/UserPreferences/UserPreference.php';
        $filters = array(
            'stic_center_id' => $_POST['stic_center_id'] ?? '',
            'stic_center_name' => $_POST['stic_center_name'] ?? '',
            'stic_resources_places_users_list' => $_POST['stic_resources_places_users_list'] ?? [],
            'stic_resources_places_type_list' => $_POST['stic_resources_places_type_list'] ?? [],
            'stic_resources_places_gender_list' => $_POST['stic_resources_places_gender_list'] ?? []
        );
        $current_user->setPreference('stic_bookings_places_calendar_filters', json_encode($filters));

        if (isset($_REQUEST['day']) && !empty($_REQUEST['day'])) {
            header("Location: index.php?module=stic_Bookings_Places_Calendar&action=index&view=" . $_REQUEST['view'] . "&hour=0&day=" . $_REQUEST['day'] . "&month=" . $_REQUEST['month'] . "&year=" . $_REQUEST['year']);
        } else {
            header("Location: index.php?module=stic_Bookings_Places_Calendar&action=index");
        }
        exit;

    }
    private function getFilteredResources($users, $types, $gender)
    {
        global $db;

        $query = "SELECT id FROM stic_resources WHERE deleted = 0 AND type = 'places'";

        if (!empty($users)) {
            $quotedUsers = array_map(array($db, 'quote'), $users);
            // Convertir el array en una cadena separada por comas
            $usersStr = implode("','", $quotedUsers);
            $query .= " AND user_type IN ('$usersStr')";
        }

        if (!empty($types)) {
            $quotedTypes = array_map(array($db, 'quote'), $types);
            // Convertir el array en una cadena separada por comas
            $typesStr = implode("','", $quotedTypes);
            $query .= " AND place_type IN ('$typesStr')";
        }

        if (!empty($gender)) {
            $quotedGender = array_map(array($db, 'quote'), $gender);
            // Convertir el array en una cadena separada por comas
            $genderStr = implode("','", $quotedGender);
            $query .= " AND gender IN ('$genderStr')";
        }

        $result = $db->query($query);
        $filteredResources = array();
        while ($row = $db->fetchByAssoc($result)) {
            $filteredResources[] = $row['id'];
        }

        return $filteredResources;
    }
}
