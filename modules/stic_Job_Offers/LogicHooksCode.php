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

        // Check if status is changing and store previous status
        if (!empty($bean->id)) {
            $hasFetchedStatus = isset($bean->fetched_row)
                && is_array($bean->fetched_row)
                && array_key_exists('status', $bean->fetched_row);
            $previousStatus = $hasFetchedStatus ? $bean->fetched_row['status'] : null;
            if (!$hasFetchedStatus) {
                $storedBean = BeanFactory::getBean('stic_Job_Offers', $bean->id);
                if (!empty($storedBean) && !empty($storedBean->id)) {
                    $previousStatus = $storedBean->status ?? null;
                }
            }
            // Store previous status in bean for use in after_save
            $bean->_previous_status = $previousStatus;
        }
    }

    /**
     * Notify when the status of the job offer changes
     *
     * @param SugarBean $bean The stic_Job_Offers bean
     * @param string $event The hook event
     * @param array $arguments Additional arguments
     */
    public function after_save(&$bean, $event, $arguments)
    {
        if (empty($bean->id)) {
            return;
        }

        require_once 'modules/stic_Job_Offers/Utils.php';
        stic_Job_OffersUtils::notifyStatusChange($bean);

        $hasFetchedInterlocutor = isset($bean->fetched_row)
            && is_array($bean->fetched_row)
            && array_key_exists('contact_id_c', $bean->fetched_row);
        $previousInterlocutorId = $hasFetchedInterlocutor ? (string)$bean->fetched_row['contact_id_c'] : '';
        $currentInterlocutorId = (string)($bean->contact_id_c ?? '');

        // Keep job applications interlocutor aligned with offer interlocutor when it changes
        if ($previousInterlocutorId !== $currentInterlocutorId) {
            $this->syncApplicationsInterlocutorFromOffer($bean->id, $currentInterlocutorId);
        }
    }

    /**
     * Sync interlocutor_id in related job applications from offer contact_id_c
     *
     * @param string $offerId
     * @param string $interlocutorId
     * @return void
     */
    protected function syncApplicationsInterlocutorFromOffer($offerId, $interlocutorId)
    {
        if (empty($offerId)) {
            return;
        }

        $db = DBManagerFactory::getInstance();
        $safeOfferId = $db->quote((string)$offerId);
        $safeInterlocutorId = $db->quote((string)$interlocutorId);

        $query = "UPDATE stic_job_applications ja
            INNER JOIN stic_job_applications_stic_job_offers_c rel
                ON rel.stic_job_applications_stic_job_offersstic_job_applications_idb = ja.id
               AND rel.deleted = 0
               AND rel.stic_job_applications_stic_job_offersstic_job_offers_ida = '{$safeOfferId}'
            SET ja.interlocutor_id = '{$safeInterlocutorId}'
            WHERE ja.deleted = 0";

        $db->query($query);
    }
}