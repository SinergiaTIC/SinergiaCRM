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

class stic_Job_ApplicationsUtils
{
    /**
     * Generate a record in the Work Experience module
     *
     * @param Object $jobApplicationBean The Job application bean
     * @return void
     */
    public static function createWorkExperience($jobApplicationBean)
    {
        include_once 'SticInclude/Utils.php';

        global $current_user;

        // Create the new work experience
        $workBean = new stic_Work_Experience();

        $workBean->name = translate('LBL_WORK_EXPERIENCE_SUBJECT', 'stic_Job_Applications');
        if (!empty($jobApplicationBean->stic_job_applications_contactscontacts_ida)) {
            $workBean->name .= "- {$jobApplicationBean->stic_job_applications_contacts_name}";
        }

        $workBean->assigned_user_id = (empty($jobApplicationBean->assigned_user_id) ? $current_user->id : $jobApplicationBean->assigned_user_id);

        $offerBean = null;
        if(!empty($jobApplicationBean->stic_job_applications_stic_job_offersstic_job_offers_ida)) {
            if ($jobApplicationBean->stic_job_applications_stic_job_offersstic_job_offers_ida instanceof Link2) {
                $offerBean = SticUtils::getRelatedBeanObject($jobApplicationBean, 'stic_job_applications_stic_job_offers');
            } else {
                $offerBean = BeanFactory::getBean('stic_Job_Offers', $jobApplicationBean->stic_job_applications_stic_job_offersstic_job_offers_ida);
            }
        }

        $accountOfferId = '';
        if (isset($offerBean)) {
            $accountOfferId = SticUtils::getRelatedBeanObject($offerBean, 'stic_job_offers_accounts')->id;
        }

        $contactApplicationId = '';
        if(!empty($jobApplicationBean->stic_job_applications_contactscontacts_ida)) {
            if ($jobApplicationBean->stic_job_applications_contactscontacts_ida instanceof Link2) {
                $contactApplicationId = SticUtils::getRelatedBeanObject($jobApplicationBean, 'stic_job_applications_contacts');
                $contactApplicationId = $contactApplicationId->id;
            } else {
                $contactApplicationId= $jobApplicationBean->stic_job_applications_contactscontacts_ida;
            }
        }

        $applicationId = $jobApplicationBean->id;

        $workBean->stic_work_experience_accountsaccounts_ida = $accountOfferId;
        $workBean->stic_work_experience_contactscontacts_ida = $contactApplicationId;
        $workBean->stic_work_9fefcations_idb = $applicationId;
        $workBean->sector = $offerBean->sector;
        $workBean->subsector = $offerBean->subsector;
        $workBean->position_type = $offerBean->position_type;
        $workBean->workday_type = $offerBean->workday_type;
        $workBean->contract_type = $offerBean->contract_type;
        $workBean->achieved=true;
        $workBean->save();
    }

    /**
     * Decide whether to update counts and collect related offer ids
     *
     * @param SugarBean $bean
     * @param bool $checkChanges
     * @param bool $allowRequest
     * @return void
     */
    public static function updateRelatedOffersApplicationsCounts($bean, $checkChanges, $allowRequest = true)
    {
        if (empty($bean) || empty($bean->id)) {
            return;
        }

        $previousStatus = $bean->fetched_row['status'] ?? null;
        $currentStatus = $bean->status ?? null;
        $statusChanged = empty($bean->fetched_row) || $previousStatus !== $currentStatus;

        $previousOfferId = $bean->fetched_row['stic_job_applications_stic_job_offersstic_job_offers_ida'] ?? null;
        $offerId = self::getRelatedOfferId($bean);
        if (empty($offerId) && $allowRequest) {
            $offerId = self::getOfferIdFromRequest();
        }

        $offerIds = array_filter(array_unique(array($offerId, $previousOfferId)));

        if ($checkChanges && !$statusChanged && empty($previousOfferId)) {
            return;
        }

        self::updateOfferCountsByIds($offerIds);
    }

    /**
    * Collect related offer id from the bean
     *
     * @param SugarBean $bean
     * @return string|null
     */
    protected static function getRelatedOfferId($bean)
    {
        if (!empty($bean->stic_job_applications_stic_job_offersstic_job_offers_ida)
            && !($bean->stic_job_applications_stic_job_offersstic_job_offers_ida instanceof Link2)) {
            return $bean->stic_job_applications_stic_job_offersstic_job_offers_ida;
        }

        if ($bean->load_relationship('stic_job_applications_stic_job_offers')) {
            $ids = $bean->stic_job_applications_stic_job_offers->get();
            if (!empty($ids)) {
                return reset($ids) ?: null;
            }
        }

        return null;
    }

    /**
     * Try to get offer id from request when created from subpanel
     *
     * @return string|null
     */
    protected static function getOfferIdFromRequest()
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
     * Update counts for the given offer ids
     *
     * @param array $offerIds
     * @return void
     */
    public static function updateOfferCountsByIds($offerIds)
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
     * Check whether the relationship belongs to Job Offers
     *
     * @param array $arguments
     * @return bool
     */
    public static function isOfferRelationship($arguments)
    {
        return !empty($arguments['relationship'])
            && $arguments['relationship'] === 'stic_job_applications_stic_job_offers';
    }

    /**
     * Notify on job application status change
     *
     * @param SugarBean $bean
     * @return void
     */
    public static function notifyStatusChange($bean)
    {
        if (empty($bean->id) || empty($bean->status)) {
            return;
        }

        // Get previous status from the bean property set by before_save hook
        $previousStatus = $bean->_previous_status ?? null;

        // Only notify if status actually changed
        if (empty($previousStatus) || $previousStatus === $bean->status) {
            return;
        }

        self::sendNotificationStatusChange($bean);
    }

    /**
     * Send notification email to candidate on status change
     *
     * @param SugarBean $bean
     * @return void
     */
    protected static function sendNotificationStatusChange($bean)
    {
        global $sugar_config, $app_list_strings;

        if (empty($bean->stic_job_applications_contactscontacts_ida)) {
            $GLOBALS['log']->error(__METHOD__ . ": Job application {$bean->id} has no candidate.");
            return;
        }

        $candidate = BeanFactory::getBean('Contacts', $bean->stic_job_applications_contactscontacts_ida);
        if (empty($candidate) || empty($candidate->id)) {
            $GLOBALS['log']->error(__METHOD__ . ": Unable to retrieve candidate for job application {$bean->id}.");
            return;
        }

        if (!$candidate->emailAddress || !($address = $candidate->emailAddress->getPrimaryAddress($candidate))) {
            $GLOBALS['log']->error(__METHOD__ . ": Candidate {$candidate->id} has no email address.");
            return;
        }

        require_once 'include/SugarPHPMailer.php';
        $mail = new SugarPHPMailer();

        require_once 'modules/Emails/Email.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();

        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];

        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->AddAddress($address, trim($candidate->first_name . ' ' . $candidate->last_name));

        $subjectTemplate = translate('LBL_JOB_APPLICATION_STATUS_CHANGE_SUBJECT', 'stic_Job_Applications');
        if ($subjectTemplate === 'LBL_JOB_APPLICATION_STATUS_CHANGE_SUBJECT') {
            $subjectTemplate = 'Status change of the application for offer {0}';
        }

        $bodyTemplate = translate('LBL_JOB_APPLICATION_STATUS_CHANGE_BODY', 'stic_Job_Applications');
        if ($bodyTemplate === 'LBL_JOB_APPLICATION_STATUS_CHANGE_BODY') {
            $bodyTemplate = 'The job application of the offer {0} status has changed to {1}. Please review the application details: {2}';
        }

        // Get offer name
        $offerName = $bean->name;
        if (!empty($bean->stic_job_applications_stic_job_offersstic_job_offers_ida)) {
            $offer = BeanFactory::getBean('stic_Job_Offers', $bean->stic_job_applications_stic_job_offersstic_job_offers_ida);
            if (!empty($offer) && !empty($offer->name)) {
                $offerName = $offer->name;
            }
        }

        // Get status label
        $statusLabel = $bean->status;
        $statusOptions = BeanFactory::newBean('stic_Job_Applications')->field_defs['status']['options'] ?? 'stic_job_applications_status_list';
        if (is_string($statusOptions)) {
            $statusLabel = $app_list_strings[$statusOptions][$bean->status] ?? $bean->status;
        }

        $url = rtrim($sugar_config['site_url'], '/') . "/index.php?module={$bean->module_name}&action=DetailView&record={$bean->id}";
        $link = "<a href=\"{$url}\">{$bean->name}</a>";

        $mail->Subject = string_format($subjectTemplate, array($offerName));
        $mail->Body = from_html(string_format($bodyTemplate, array($offerName, $statusLabel, $link)));
        $mail->IsHTML(true);

        $mail->setMailerForSystem();
        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->fatal(__METHOD__ . ": Error sending notification email for job application {$bean->id}.");
        }
    }
}
