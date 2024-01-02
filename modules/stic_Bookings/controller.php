<?php

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
        global $current_user;

        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        $bookingId = $_REQUEST['bookingId'];
        $resourceRequestId = $_REQUEST['resourceId'];
        $resourcesIds = array();
        if ($resourceRequestId) {
            // If a single resource id is provided, will only check that resource
            $resourcesIds[] = $resourceRequestId;
        } else {
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
        
        $timeZoneOffsetHourStartDate = $dateTimeZone->getOffset(new DateTime($startDate)) / 3600;
        $timeZoneOffsetHourEndDate = $dateTimeZone->getOffset(new DateTime($endDate)) / 3600;

        // Check if there are other bookings in the period that include the required resource(s)
        foreach ($resourcesIds as $resourceId) {
            $query = "SELECT
                count(stic_bookings.id) AS bookingsCount
                FROM stic_bookings
                JOIN stic_resources_stic_bookings_c
                    ON stic_resources_stic_bookings_c.stic_resources_stic_bookingsstic_bookings_idb=stic_bookings.id
                WHERE stic_resources_stic_bookings_c.deleted=0
                    AND stic_bookings.deleted=0
                    AND stic_resources_stic_bookings_c.stic_resources_stic_bookingsstic_resources_ida='" . $resourceId . "'
                    AND stic_bookings.id != '" . $bookingId . "'
                    AND stic_bookings.status != 'cancelled'
                    AND TIMESTAMPDIFF(SECOND, date_add(stic_bookings.start_date, INTERVAL " . $timeZoneOffsetHourStartDate . " HOUR),'" . $endDate . "') > 0
                    AND TIMESTAMPDIFF(SECOND, '" . $startDate . "', date_add(stic_bookings.end_date, INTERVAL " . $timeZoneOffsetHourEndDate . " HOUR)) > 0 ";
            ob_clean();
            if ($res = $db->query($query)) {
                $row = $db->fetchByAssoc($res);
                if ($row['bookingsCount'] > 0) {
                    // Action successfully completed but requested resource(s) is(are) not available
                    echo json_encode(array('success' => true, 'resources_allowed' => false));
                    return;
                }
            } else {
                // Action unsuccessfully completed
                echo json_encode(array('success' => false, 'resources_allowed' => $res));
                return;
            }
        }
        
        // Action successfully completed and requested resource(s) is(are) available
        echo json_encode(array('success' => true, 'resources_allowed' => true));
        return;
    }
}
