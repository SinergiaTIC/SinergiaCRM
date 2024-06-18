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
        $bookingId = $_REQUEST['bookingId'];
        $resourceRequestId = $_REQUEST['resourceId'];

        $result = $this->checkResourceAvailability($resourceRequestId, $startDate, $endDate, $bookingId);

        echo json_encode($result);
        return;
    }

    public function action_loadCenterResources() 
    {
        $startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
        $endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
        $bookingId = $_REQUEST['bookingId'];
        $centerIds = $_REQUEST['centerIds'];
        $resourceType = isset($_REQUEST['resourceType']) ? $_REQUEST['resourceType'] : '';
        $resourceName = isset($_REQUEST['resourceName']) ? $_REQUEST['resourceName'] : '';
        $resourceStatus = isset($_REQUEST['resourceStatus']) ? $_REQUEST['resourceStatus'] : '';
        $numberOfCenters = isset($_REQUEST['numberOfCenters']) ? $_REQUEST['numberOfCenters'] : '';
    
        if (empty($centerIds)) {
            echo json_encode(['success' => false, 'message' => 'ID del centro inválido.']);
            return;
        }
    
        $db = DBManagerFactory::getInstance();
        $centerIdsArray = explode(',', $centerIds);
        $resources = [];
        $totalCenters = 0;
    
        foreach ($centerIdsArray as $centerId) {
            $query = "SELECT stic_resources_stic_centersstic_resources_idb 
                      FROM stic_resources_stic_centers_c 
                      WHERE stic_resources_stic_centersstic_centers_ida = '$centerId'";
            $result = $db->query($query, true);
    
            while ($row = $db->fetchByAssoc($result)) {
                $resourceId = $row['stic_resources_stic_centersstic_resources_idb'];
    
                if ($numberOfCenters && $totalCenters >= $numberOfCenters) {
                    break;
                }
                $availability = $this->checkResourceAvailability($resourceId, $startDate, $endDate, $bookingId);

               /// $availability = $this->checkResourceAvailability($resourceId, null, null, null);
    
                if ($availability['resources_allowed']) {
                    $resourceQuery = "SELECT id, name, code, color, status, type, hourly_rate, daily_rate 
                                      FROM stic_resources 
                                      WHERE id = '$resourceId'";
    
                    if (!empty($resourceType)) {
                        $resourceQuery .= " AND type LIKE '%$resourceType%'";
                    }
                    if (!empty($resourceStatus)) {
                        $resourceQuery .= " AND status LIKE '%$resourceStatus%'";
                    }
                    if (!empty($resourceName)) {
                        $resourceQuery .= " AND name LIKE '%$resourceName%'";
                    }
                    if (!empty($numberOfCenters)) {
                        $query .= " LIMIT $numberOfCenters";
                    }
    
                    $resourceResult = $db->query($resourceQuery);
    
                    if ($resourceResult !== false) {
                        $resourceData = $db->fetchByAssoc($resourceResult);
                        if ($resourceData !== false) {
                            $resources[] = [
                                'resource_id' => $resourceData['id'],
                                'resource_name' => $resourceData['name'],
                                'resource_code' => $resourceData['code'],
                                'resource_color' => $resourceData['color'],
                                'resource_status' => $resourceData['status'],
                                'resource_type' => $resourceData['type'],
                                'resource_hourly_rate' => $resourceData['hourly_rate'],
                                'resource_daily_rate' => $resourceData['daily_rate']
                            ];
                        }
                    }
                    $totalCenters++;

                }
            }
    
            if ($numberOfCenters && $totalCenters >= $numberOfCenters) {
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
            if ($booking->load_relationship('stic_resources_stic_bookings')) {
                foreach ($booking->stic_resources_stic_bookings->getBeans() as $resourceBean) {
                    $resourcesIds[] = $resourceBean->id;
                }
            }
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

    public function action_getResourceTypes() {
        $centerId = $_REQUEST['centerId'];
        
        // Obtener las opciones del campo 'type' del resource vardefs
        require_once('modules/stic_Resources/vardefs.php');
        global $app_list_strings;
        $options = $app_list_strings['stic_resources_types_list'];
        $options2 = $app_list_strings['stic_resources_status_list'];

        $response = array('success' => true, 'options' => array(), 'options2' => array());

        foreach ($options as $value => $label) {
            $response['options'][] = array('value' => $value, 'label' => $label);
        }
        foreach ($options2 as $value => $label) {
            $response['options2'][] = array('value' => $value, 'label' => $label);
        }
        echo json_encode($response);
        sugar_cleanup(true);
    }


}
