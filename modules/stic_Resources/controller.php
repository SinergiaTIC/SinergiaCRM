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

class stic_ResourcesController extends SugarController
{
    /**
     * Show the M182 wizard
     *
     * @return void
     */
    public function action_listplaces()
    {        
        // Call to the smarty template
        $this->view = "listplaces";
    }

    /**
     * Check if a resource has associated bookings
     *
     * @return void
     */
    public function action_checkBookings()
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $resourceId = $_POST['resource_id'] ?? '';
        if (empty($resourceId)) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => false, "message" => "Resource ID is required"));
            sugar_cleanup(true); 
            exit();
        }
    
        global $db;
    
        $query = "SELECT COUNT(DISTINCT rsb.stic_resources_stic_bookingsstic_bookings_idb) AS booking_count
                  FROM stic_resources_stic_bookings_c rsb
                  INNER JOIN stic_bookings sb ON rsb.stic_resources_stic_bookingsstic_bookings_idb = sb.id
                  WHERE rsb.stic_resources_stic_bookingsstic_resources_ida = '" . $db->quote($resourceId) . "'
                  AND rsb.deleted = 0;";
    
        $result = $db->query($query);
    
        $response = [];
    
        if ($result !== false) {
            $row = $db->fetchByAssoc($result);
            $response = array(
                "success" => true,
                "hasBookings" => ($row["booking_count"] > 0)
            );
        } else {
            $response = array(
                "success" => false,
                "hasBookings" => false
            );
        }
    
        header('Content-Type: application/json');
        echo json_encode($response);
                sugar_cleanup(true); 
        exit(); 
    }

    public function action_massupdate()
    {
        if (!empty($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true' && (!empty($_REQUEST['uid']) || !empty($_REQUEST['entire']))) {
            if (!empty($_REQUEST['Delete']) && $_REQUEST['Delete'] == 'true' && !$this->bean->ACLAccess('delete')
                || (empty($_REQUEST['Delete']) || $_REQUEST['Delete'] != 'true') && !$this->bean->ACLAccess('save')
            ) {
                ACLController::displayNoAccess(true);
                sugar_cleanup(true);
            }

            set_time_limit(0);//I'm wondering if we will set it never goes timeout here.
            // until we have more efficient way of handling MU, we have to disable the limit
            DBManagerFactory::getInstance()->setQueryLimit(0);
            require_once("include/MassUpdate.php");
            require_once('modules/MySettings/StoreQuery.php');
            $seed = loadBean($_REQUEST['module']);
            $mass = new MassUpdate();
            $mass->setSugarBean($seed);
            if (isset($_REQUEST['entire']) && empty($_POST['mass'])) {
                $mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_query_by_page']);
            }
            // This line is added to process custom query added to discrinate between places and resources in mass update. without this, the custom where is not added to mass update query and all the resources are updated instead of only places or non-places.
            $mass->where_clauses .= html_entity_decode($_REQUEST['custom_where'] ?? '');
            $mass->handleMassUpdate();
            $storeQuery = new StoreQuery();//restore the current search. to solve bug 24722 for multi tabs massupdate.
            $temp_req = array(
                'current_query_by_page' => $_REQUEST['current_query_by_page'],
                'return_module' => $_REQUEST['return_module'],
                'return_action' => $_REQUEST['return_action']
            );
            if ($_REQUEST['return_module'] == 'Emails') {
                if (!empty($_REQUEST['type']) && !empty($_REQUEST['ie_assigned_user_id'])) {
                    $this->req_for_email = array(
                        'type' => $_REQUEST['type'],
                        'ie_assigned_user_id' => $_REQUEST['ie_assigned_user_id']
                    ); // Specifically for My Achieves
                }
            }
            $_REQUEST = array();
            $_REQUEST = json_decode(html_entity_decode($temp_req['current_query_by_page']), true);
            unset($_REQUEST[$seed->module_dir . '2_' . strtoupper($seed->object_name) . '_offset']);//after massupdate, the page should redirect to no offset page
            $storeQuery->saveFromRequest($_REQUEST['module']);
            $_REQUEST = array(
                'return_module' => $temp_req['return_module'],
                'return_action' => $temp_req['return_action']
            );//for post_massupdate, to go back to original page.
        } else {
            sugar_die("You must massupdate at least one record");
        }
    }
}