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
     * Notify assigned user when applications_end_date is today and offer is not closed.
     *
     * @param SugarBean $bean The stic_Job_Offers bean
     * @param string $event The hook event
     * @param array $arguments Additional arguments
     */
    public function after_save(&$bean, $event, $arguments)
    {
        global $timedate;

        // Ensure we have a saved record
        if (empty($bean->id)) {
            return;
        }

        // Compute today's date in DB format
        $today = $timedate ? $timedate->nowDbDate() : date('Y-m-d');

        // Normalize offer end date
        $endDate = !empty($bean->applications_end_date) ? date('Y-m-d', strtotime($bean->applications_end_date)) : null;
        if (empty($endDate)) {
            return;
        }

        // Closed statuses list
        $closedStatuses = array(
            'closed_covered',
            'closed_partially_covered',
            'closed_not_covered',
            'closed',
        );

        // Notify only if end date is today and status is not closed
        if ($endDate !== $today || in_array($bean->status, $closedStatuses, true)) {
            return;
        }

        // Avoid duplicate notifications if it was already open with today's end date
        $prevEndDate = !empty($bean->fetched_row['applications_end_date']) ? date('Y-m-d', strtotime($bean->fetched_row['applications_end_date'])) : null;
        
        $prevStatus = $bean->fetched_row['status'] ?? null;
        $wasOpenToday = ($prevEndDate === $today) && !in_array($prevStatus, $closedStatuses, true);

        if ($wasOpenToday) {
            return;
        }

        // Send the notification email to assigned user
        $this->sendClosingDateNotification($bean);
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

    /**
     * Send closing date notification to assigned user.
     *
     * @param SugarBean $bean
     * @return void
     */
    protected function sendClosingDateNotification($bean)
    {
        global $sugar_config;

        // Validate assigned user
        if (empty($bean->assigned_user_id)) {
            $GLOBALS['log']->error(__METHOD__ . ": Offer {$bean->id} has no assigned user.");
            return;
        }

        // Load assigned user bean
        $user = BeanFactory::getBean('Users', $bean->assigned_user_id);
        if (empty($user) || empty($user->id)) {
            $GLOBALS['log']->error(__METHOD__ . ": Unable to retrieve assigned user for offer {$bean->id}.");
            return;
        }

        // Require a valid email address
        if (!$user->emailAddress || !($address = $user->emailAddress->getPrimaryAddress($user))) {
            $GLOBALS['log']->error(__METHOD__ . ": Assigned user {$user->id} has no email address.");
            return;
        }

        // Initialize mailer
        require_once 'include/SugarPHPMailer.php';
        $mail = new SugarPHPMailer();

        // Load system email defaults
        require_once 'modules/Emails/Email.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();

        // Sender configuration
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];

        // Recipient configuration
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->AddAddress($address, trim($user->first_name . ' ' . $user->last_name));

        // Localized subject and body with fallback
        $subjectTemplate = translate('LBL_JOB_OFFER_CLOSE_DATE_TODAY_SUBJECT', 'stic_Job_Offers');
        if ($subjectTemplate === 'LBL_JOB_OFFER_CLOSE_DATE_TODAY_SUBJECT') {
            $subjectTemplate = 'The offer {0} closes today';
        }

        $bodyTemplate = translate('LBL_JOB_OFFER_CLOSE_DATE_TODAY_BODY', 'stic_Job_Offers');
        if ($bodyTemplate === 'LBL_JOB_OFFER_CLOSE_DATE_TODAY_BODY') {
            $bodyTemplate = 'The offer {0} closes today. Please review the offer details: {1}';
        }

        // Build offer detail link
        $url = rtrim($sugar_config['site_url'], '/') . "/index.php?module={$bean->module_name}&action=DetailView&record={$bean->id}";
        $link = "<a href=\"{$url}\">{$bean->name}</a>";

        // Prepare message content
        $mail->Subject = string_format($subjectTemplate, array($bean->name));
        $mail->Body = from_html(string_format($bodyTemplate, array($bean->name, $link)));
        $mail->IsHTML(true);

        // Send as system mailer
        $mail->setMailerForSystem();
        $mail->prepForOutbound();

        // Send and log on failure
        if (!$mail->Send()) {
            $GLOBALS['log']->fatal(__METHOD__ . ": Error sending notification email for offer {$bean->id}.");
        }
    }
    
}