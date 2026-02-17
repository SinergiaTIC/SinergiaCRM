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

class stic_Job_ApplicationsLogicHooks
{
    /**
     * Update counts when an application is created
     *
     * @param SugarBean $bean The stic_Job_Applications bean
     * @param string $event The hook event
     * @param array $arguments Additional arguments
     */
    public function after_save(&$bean, $event, $arguments)
    {
        if (empty($bean->id)) {
            return;
        }

        $this->updateRelatedOffersApplicationsCounts($bean, true);
    }

    /**
     * Update counts when an application is deleted.
     *
     * @param SugarBean $bean
     * @param string $event
     * @param array $arguments
     */
    public function after_delete(&$bean, $event, $arguments)
    {
        $this->updateRelatedOffersApplicationsCounts($bean, false);
    }

    /**
     * Update counts when a relationship is added.
     *
     * @param SugarBean $bean
     * @param string $event
     * @param array $arguments
     */
    public function after_relationship_add(&$bean, $event, $arguments)
    {
        if (!$this->isOfferRelationship($arguments)) {
            return;
        }

        $this->updateOfferCountsByIds(array($arguments['related_id'] ?? null));
    }

    /**
     * Update counts when a relationship is removed.
     *
     * @param SugarBean $bean
     * @param string $event
     * @param array $arguments
     */
    public function after_relationship_delete(&$bean, $event, $arguments)
    {
        if (!$this->isOfferRelationship($arguments)) {
            return;
        }

        $this->updateOfferCountsByIds(array($arguments['related_id'] ?? null));
    }

    /**
     * Decide whether to update counts and collect related offer ids.
     *
     * @param SugarBean $bean
     * @param bool $checkChanges
     * @return void
     */
    protected function updateRelatedOffersApplicationsCounts($bean, $checkChanges)
    {
        if (empty($bean) || empty($bean->id)) {
            return;
        }

        $previousStatus = $bean->fetched_row['status'] ?? null;
        $currentStatus = $bean->status ?? null;
        $statusChanged = empty($bean->fetched_row) || $previousStatus !== $currentStatus;

        $previousOfferId = $bean->fetched_row['stic_job_applications_stic_job_offersstic_job_offers_ida'] ?? null;
        $offerIds = $this->getRelatedOfferIds($bean);

        if (empty($offerIds)) {
            $requestOfferId = $this->getOfferIdFromRequest();
            if (!empty($requestOfferId)) {
                $offerIds[] = $requestOfferId;
            }
        }

        if (!empty($previousOfferId) && !in_array($previousOfferId, $offerIds, true)) {
            $offerIds[] = $previousOfferId;
        }

        if ($checkChanges && !$statusChanged && empty($previousOfferId)) {
            return;
        }

        $this->updateOfferCountsByIds($offerIds);
    }

    /**
     * Collect related offer ids from the bean.
     *
     * @param SugarBean $bean
     * @return array
     */
    protected function getRelatedOfferIds($bean)
    {
        $offerIds = array();

        if ($bean->load_relationship('stic_job_applications_stic_job_offers')) {
            $ids = $bean->stic_job_applications_stic_job_offers->get();
            if (!empty($ids)) {
                $offerIds = array_merge($offerIds, $ids);
            }
        }

        if (!empty($bean->stic_job_applications_stic_job_offersstic_job_offers_ida)
            && !($bean->stic_job_applications_stic_job_offersstic_job_offers_ida instanceof Link2)) {
            $offerIds[] = $bean->stic_job_applications_stic_job_offersstic_job_offers_ida;
        }

        $offerIds = array_filter(array_unique($offerIds));
        return $offerIds;
    }

    /**
     * Try to get offer id from request when created from subpanel.
     *
     * @return string|null
     */
    protected function getOfferIdFromRequest()
    {
        if (empty($_REQUEST)) {
            return null;
        }

        if (!empty($_REQUEST['stic_job_applications_stic_job_offersstic_job_offers_ida'])) {
            return $_REQUEST['stic_job_applications_stic_job_offersstic_job_offers_ida'];
        }

        if (!empty($_REQUEST['parent_type']) && $_REQUEST['parent_type'] === 'stic_Job_Offers'
            && !empty($_REQUEST['parent_id'])) {
            return $_REQUEST['parent_id'];
        }

        return null;
    }

    /**
     * Update counts for the given offer ids.
     *
     * @param array $offerIds
     * @return void
     */
    protected function updateOfferCountsByIds($offerIds)
    {
        if (empty($offerIds)) {
            return;
        }

        require_once 'modules/stic_Job_Offers/Utils.php';
        foreach (array_filter(array_unique($offerIds)) as $offerId) {
            stic_Job_OffersUtils::updateApplicationsCounts($offerId);
        }
    }

    /**
     * Check whether the relationship belongs to Job Offers.
     *
     * @param array $arguments
     * @return bool
     */
    protected function isOfferRelationship($arguments)
    {
        return !empty($arguments['relationship'])
            && $arguments['relationship'] === 'stic_job_applications_stic_job_offers';
    }
}
