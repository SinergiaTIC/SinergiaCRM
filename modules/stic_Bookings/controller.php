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
class stic_BookingsController extends SugarController
{
    /**
     * Action that validates if a booking has all its resources available in the booking dates.
     * It can also check only a specific resource, if the resource id is provided.
     *
     * Returns an object with two properties:
     * 1) success: True if the action completed successfully
     * 2) resources_allowed: True if all the given resources are available
     *
     * @return void
     */
    public function action_isResourceAvailable()
    {

        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        $bookingId = $_REQUEST['bookingId'] ?? null;
        $resourceRequestId = $_REQUEST['resourceId'];

        $result = $this->checkResourceAvailability($resourceRequestId, $startDate, $endDate, $bookingId);

        echo json_encode($result);
        return;
    }

    public function action_loadCenterResources()
    {
        $startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
        $endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
        $bookingId = isset($_REQUEST['bookingId']) ? $_REQUEST['bookingId'] : null;
        $centerIds = $_REQUEST['centerIds'];
        $resourcePlaceUserType = isset($_REQUEST['resourcePlaceUserType']) ? $_REQUEST['resourcePlaceUserType'] : '';
        $resourcePlaceType = isset($_REQUEST['resourcePlaceType']) ? $_REQUEST['resourcePlaceType'] : '';
        $resourceName = isset($_REQUEST['resourceName']) ? $_REQUEST['resourceName'] : '';
        $resourceGender = isset($_REQUEST['resourceGender']) ? $_REQUEST['resourceGender'] : '';
        $numberOfCenters = isset($_REQUEST['numberOfCenters']) ? $_REQUEST['numberOfCenters'] : '';
        
        if (empty($centerIds)) {
            echo json_encode(['success' => false]);
            return;
        }
        
        require_once 'modules/stic_Bookings/configPlaceFields.php';
        global $config_place_fields;
        $db = DBManagerFactory::getInstance();
        $centerIdsArray = explode(',', $centerIds);
        $resources = [];
        $totalCenters = 0;
        
        $resourceGenderCondition = '';
        if (!empty($resourceGender)) {
            if (is_array($resourceGender)) {
                $genderFilters = [];
                foreach ($resourceGender as $gender) {
                    $genderSafe = $db->quote($gender);
                    $genderFilters[] = "gender LIKE '%$genderSafe%'";
                }
                if (!empty($genderFilters)) {
                    $resourceGenderCondition = " AND (" . implode(" OR ", $genderFilters) . ")";
                }
            } else {
                $resourceGenderSafe = $db->quote($resourceGender);
                $resourceGenderCondition = " AND gender LIKE '%$resourceGenderSafe%'";
            }
        }
        
        $resourcePlaceUserTypeCondition = '';
        if (!empty($resourcePlaceUserType)) {
            if (is_array($resourcePlaceUserType)) {
                $typeFilters = [];
                foreach ($resourcePlaceUserType as $type) {
                    $typeSafe = $db->quote($type);
                    $typeFilters[] = "user_type LIKE '%$typeSafe%'";
                }
                if (!empty($typeFilters)) {
                    $resourcePlaceUserTypeCondition = " AND (" . implode(" OR ", $typeFilters) . ")";
                }
            } else {
                $typeSafe = $db->quote($resourcePlaceUserType);
                $resourcePlaceUserTypeCondition = " AND user_type LIKE '%$typeSafe%'";
            }
        }
        
        $resourcePlaceTypeCondition = '';
        if (!empty($resourcePlaceType)) {
            if (is_array($resourcePlaceType)) {
                $placeTypeFilters = [];
                foreach ($resourcePlaceType as $placeType) {
                    $placeTypeSafe = $db->quote($placeType);
                    $placeTypeFilters[] = "place_type LIKE '%$placeTypeSafe%'";
                }
                if (!empty($placeTypeFilters)) {
                    $resourcePlaceTypeCondition = " AND (" . implode(" OR ", $placeTypeFilters) . ")";
                }
            } else {
                $placeTypeSafe = $db->quote($resourcePlaceType);
                $resourcePlaceTypeCondition = " AND place_type LIKE '%$placeTypeSafe%'";
            }
        }
        
        $resourceNameCondition = '';
        if (!empty($resourceName)) {
            $resourceNameSafe = $db->quote($resourceName);
            $resourceNameCondition = " AND name LIKE '%$resourceNameSafe%'";
        }
        
        foreach ($centerIdsArray as $centerId) {
            $query = "SELECT stic_resources_stic_centersstic_resources_idb
                      FROM stic_resources_stic_centers_c
                      WHERE stic_resources_stic_centersstic_centers_ida = '$centerId'";
            $result = $db->query($query, true);
            
            while ($row = $db->fetchByAssoc($result)) {
                $resourceId = $row['stic_resources_stic_centersstic_resources_idb'];
                
                if ($numberOfCenters && $totalCenters >= (int)$numberOfCenters) {
                    break;
                }
                
                $availability = $this->checkResourceAvailability($resourceId, $startDate, $endDate, $bookingId);
                
                if ($availability['resources_allowed']) {
                    $resourceQuery = "SELECT *
                                      FROM stic_resources
                                      WHERE id = '$resourceId'";
                    
                    $resourceQuery .= $resourcePlaceUserTypeCondition;
                    $resourceQuery .= $resourcePlaceTypeCondition;
                    $resourceQuery .= $resourceGenderCondition;
                    $resourceQuery .= $resourceNameCondition;
                    
                    if (!empty($numberOfCenters)) {
                        $resourceQuery .= " LIMIT $numberOfCenters"; 
                    }
                    
                    $resourceResult = $db->query($resourceQuery);
                    
                    if ($resourceResult !== false) {
                        $resourceData = $db->fetchByAssoc($resourceResult);
                        
                        if ($resourceData !== false) {
                            $resourceItem = [
                                'resource_id' => $resourceData['id'],
                            ];
                            
                            foreach ($config_place_fields as $fieldKey => $fieldLabel) {
                                if (isset($resourceData[$fieldKey])) {
                                    $resourceItem['resource_' . $fieldKey] = $resourceData[$fieldKey];
                                }
                            }
                            
                            $resources[] = $resourceItem;
                        }
                    }
                    
                    $totalCenters++;
                }
            }
            
            if ($numberOfCenters && $totalCenters >= (int)$numberOfCenters) {
                break;
            }
        }
        
        echo json_encode(['success' => true, 'resources' => $resources]);
        return;
    }
    private function checkResourceAvailability($resourceId, $startDate, $endDate, $bookingId)
    {
        global $current_user;

        $resourcesIds = array();
        if ($resourceId) {
            // If a single resource id is provided, will only check that resource
            $resourcesIds[] = $resourceId;
        } else if ($bookingId) {
            // If a single resource id is not provided, will check all resources attached to the booking
            $booking = BeanFactory::getBean('stic_Bookings', $bookingId);
            if ($booking && $booking->load_relationship('stic_resources_stic_bookings')) {
                foreach ($booking->stic_resources_stic_bookings->getBeans() as $resourceBean) {
                        $resourcesIds[] = $resourceBean->id;
                }
            }
        }
        if (empty($resourcesIds)) {
            return array('success' => true, 'resources_allowed' => true);
        }
    
        $db = DBManagerFactory::getInstance();
        $tzone = $current_user->getPreference('timezone');
        $dateTimeZone = new DateTimeZone($tzone);

        $timeZoneOffsetHourStartDate = $startDate ? $dateTimeZone->getOffset(new DateTime($startDate)) / 3600 : 0;
        $timeZoneOffsetHourEndDate = $endDate ? $dateTimeZone->getOffset(new DateTime($endDate)) / 3600 : 0;

        // Check if there are other bookings in the period that include the required resource(s)
        foreach ($resourcesIds as $resourceId) {
            $query = "SELECT
                COUNT(stic_bookings.id) AS bookingsCount
                FROM stic_bookings
                JOIN stic_resources_stic_bookings_c
                    ON stic_resources_stic_bookings_c.stic_resources_stic_bookingsstic_bookings_idb=stic_bookings.id
                WHERE stic_resources_stic_bookings_c.deleted=0
                    AND stic_bookings.deleted=0
                    AND stic_resources_stic_bookings_c.stic_resources_stic_bookingsstic_resources_ida='" . $resourceId . "'
                    AND stic_bookings.id != '" . $bookingId . "'
                    AND stic_bookings.status != 'cancelled'";

            if ($startDate && $endDate) {
                $query .= " AND TIMESTAMPDIFF(SECOND, DATE_ADD(stic_bookings.start_date, INTERVAL " . $timeZoneOffsetHourStartDate . " HOUR),'" . $endDate . "') > 0
                            AND TIMESTAMPDIFF(SECOND, '" . $startDate . "', DATE_ADD(stic_bookings.end_date, INTERVAL " . $timeZoneOffsetHourEndDate . " HOUR)) > 0 ";
            }

            if ($res = $db->query($query)) {
                $row = $db->fetchByAssoc($res);
                if ($row['bookingsCount'] > 0) {
                    // Requested resource(s) is(are) not available
                    return array('success' => true, 'resources_allowed' => false);
                }
            } else {
                // Action unsuccessfully completed
                return array('success' => false, 'resources_allowed' => $res);
            }
        }

        // Requested resource(s) is(are) available
        return array('success' => true, 'resources_allowed' => true);
    }

    public function action_getResourceTypes()
    {
        $centerId = $_REQUEST['centerId'];

        require_once 'modules/stic_Resources/vardefs.php';
        global $app_list_strings;
        $options = $app_list_strings['stic_resources_places_users_list'] ?? [];
        $options2 = $app_list_strings['stic_resources_places_type_list'] ?? [];
        $options3 = $app_list_strings['stic_resources_places_gender_list'] ?? [];

        $response = array('success' => true, 'options' => array(), 'options2' => array(), 'options3' => array());

        foreach ($options as $value => $label) {
            $response['options'][] = array('value' => $value, 'label' => $label);
        }
        foreach ($options2 as $value => $label) {
            $response['options2'][] = array('value' => $value, 'label' => $label);
        }
        foreach ($options3 as $value => $label) {
            $response['options3'][] = array('value' => $value, 'label' => $label);
        }

        echo json_encode($response);
        sugar_cleanup(true);
    }
    public function action_closeResource()
    {
        global $app_list_strings, $timedate, $current_user;

        if (empty($_REQUEST['record_id']) || empty($_REQUEST['resource_id'])) {
            echo json_encode(['success' => false]);
            return;
        }

        $bookingId = $_REQUEST['record_id'];
        $resourceId = $_REQUEST['resource_id'];

        $booking = BeanFactory::getBean('stic_Bookings', $bookingId);

        $newBooking = BeanFactory::newBean('stic_Bookings');

        $baseName = $booking->name;

        $counter = 0;
        $uniqueName = $baseName;
        while (true) {
            $existingBooking = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM stic_bookings WHERE name = '{$uniqueName}' and deleted =0");

            if ($existingBooking == 0) {
                break;
            }

            $counter++;
            $uniqueName = $baseName . '.' . $counter;
        }

        $nowDb = $timedate->nowDb();
        $endDate = $timedate->to_display_date_time($nowDb, true, true, $current_user);

        $newBooking->name = $uniqueName;
        $newBooking->status = $booking->status;
        $newBooking->start_date = $booking->start_date;
        $newBooking->end_date = $endDate;
        $newBooking->parent_name = $booking->parent_name;
        $newBooking->parent_type = $booking->parent_type;
        $newBooking->parent_id = $booking->parent_id;
        $newBooking->code = $booking->code . $counter;
        $newBooking->status = $app_list_strings['stic_bookings_status_list']['closed'];
        $newBooking->assigned_user_id = $booking->assigned_user_id;
        $newBooking->assigned_user_name = $booking->assigned_user_name;
        $newBooking->description = $booking->description;

        $newBooking->save();

        if ($newBooking->load_relationship('stic_resources_stic_bookings')) {
            $newBooking->stic_resources_stic_bookings->add($resourceId);
        } else {
            echo json_encode(['success' => false]);
            return;
        }

        if ($booking->load_relationship('stic_resources_stic_bookings')) {
            $booking->stic_resources_stic_bookings->delete($bookingId, $resourceId);
        }

        echo json_encode(['success' => true, 'booking_id' => $newBooking->id]);
        return;
    }
    public function action_validateResourceDates()
    {
        global $timedate;

        if (empty($_REQUEST['record_id'])) {
            echo json_encode(['valid' => false]);
            return;
        }

        $bookingId = $_REQUEST['record_id'];
        $booking = BeanFactory::getBean('stic_Bookings', $bookingId);

        if (!$booking) {
            echo json_encode(['valid' => false]);
            return;
        }

        $startDate = $booking->start_date;
        $currentDate = $timedate->nowDb();

        if (!$timedate->fromDb($startDate)) {
            $startDate = $timedate->to_db($startDate);
        }

        $valid = $timedate->fromDb($currentDate) >= $timedate->fromDb($startDate);

        echo json_encode(['valid' => $valid]);
        return;
    }
}
