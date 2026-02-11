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

class stic_Job_OffersLogicHooks
{
    public function before_save(&$bean, $event, $arguments)
    {
        // Bring Incorpora location data, if there is any
        if (isset($bean->stic_incorpora_locations_id) && 
            (!isset($this->fetched_row['stic_incorpora_locations_id']) || $bean->fetched_row['stic_incorpora_locations_id'] != $bean->stic_incorpora_locations_id)
        ) {
            include_once 'modules/stic_Incorpora_Locations/Utils.php';
            stic_Incorpora_LocationsUtils::transferLocationData($bean);
        }
    }
    

    /**
     * Calculate Job Applications Counts
     * 
     * @param SugarBean $bean The stic_Job_Offers bean
     * @param string $event The hook event
     * @param array $arguments Additional arguments
     */
    public function after_retrieve(&$bean, $event, $arguments)
    {
        if (empty($bean->id)) {
            return;
        }

        global $db;

        // Initialize counts to zero
        $bean->stic_job_applications_count_total = 0;
        $statuses = array(
            'expected_presentation',
            'presented',
            'pending_interview',
            'interviewed',
            'accepted',
            'rejected_closed',
            'review'
        );
        
        foreach ($statuses as $status) {
            $fieldName = 'stic_job_applications_count_' . $status;
            $bean->$fieldName = 0;
        }

        // Query to count applications by status
        $query = "SELECT 
                    ja.status,
                    COUNT(*) as total
                  FROM stic_job_applications_stic_job_offers_c rel
                  INNER JOIN stic_job_applications ja ON rel.stic_job_applications_stic_job_offersstic_job_applications_idb = ja.id
                  WHERE rel.stic_job_applications_stic_job_offersstic_job_offers_ida = " . $db->quoted($bean->id) . "
                  AND rel.deleted = 0
                  AND ja.deleted = 0
                  GROUP BY ja.status";
        
        $result = $db->query($query);
        
        // Process the results
        $totalCount = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $status = $row['status'];
            $count = (int)$row['total'];
            
            // Accumulate the total
            $totalCount += $count;
            
            // Assign the count to the corresponding field
            if (!empty($status)) {
                $fieldName = 'stic_job_applications_count_' . $status;
                $bean->$fieldName = $count;
            }
        }
        
        // Assign the total
        $bean->stic_job_applications_count_total = $totalCount;
    }
    
}