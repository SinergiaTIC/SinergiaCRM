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

class stic_Job_OffersUtils
{
    /**
     * Recalculate and store Job Applications counts for an offer.
     *
     * @param string $offerId
     * @return void
     */
    public static function updateApplicationsCounts($offerId)
    {
        if (empty($offerId)) {
            return;
        }

        $db = DBManagerFactory::getInstance();
        $offerId = $db->quote($offerId);

        $statuses = array(
            'expected_presentation',
            'presented',
            'pending_interview',
            'interviewed',
            'accepted',
            'rejected_closed',
            'review',
        );

        $counts = array();
        foreach ($statuses as $status) {
            $counts[$status] = 0;
        }

        $query = "SELECT
                ja.status,
                COUNT(*) as total
            FROM stic_job_applications_stic_job_offers_c rel
            INNER JOIN stic_job_applications ja
                ON rel.stic_job_applications_stic_job_offersstic_job_applications_idb = ja.id
            WHERE rel.stic_job_applications_stic_job_offersstic_job_offers_ida = '{$offerId}'
              AND rel.deleted = 0
              AND ja.deleted = 0
            GROUP BY ja.status";

        $result = $db->query($query);

        $totalCount = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $status = $row['status'];
            $count = (int)$row['total'];
            $totalCount += $count;
            if (!empty($status) && array_key_exists($status, $counts)) {
                $counts[$status] = $count;
            }
        }

        $fields = array(
            'stic_job_applications_count_total' => $totalCount,
        );
        foreach ($counts as $status => $count) {
            $fields['stic_job_applications_count_' . $status] = $count;
        }

        $setParts = array();
        foreach ($fields as $field => $value) {
            $setParts[] = $field . ' = ' . $db->quote($value);
        }

        if (empty($setParts)) {
            return;
        }

        $update = "UPDATE stic_job_offers SET " . implode(', ', $setParts) . " WHERE id = '{$offerId}'";
        $db->query($update);
    }

    /**
     * Notify assigned user when applications_end_date is today and offer is not closed.
     *
     * @param SugarBean $bean
     * @return void
     */
    public static function notifyClosingDateIfNeeded($bean)
    {
        global $timedate;

        if (empty($bean->id)) {
            return;
        }

        $today = $timedate ? $timedate->nowDbDate() : date('Y-m-d');
        $endDate = !empty($bean->applications_end_date)
            ? date('Y-m-d', strtotime($bean->applications_end_date))
            : null;
        if (empty($endDate)) {
            return;
        }

        $closedStatuses = array(
            'closed_covered',
            'closed_partially_covered',
            'closed_not_covered',
            'closed',
        );

        if ($endDate !== $today || in_array($bean->status, $closedStatuses, true)) {
            return;
        }

        $prevEndDate = !empty($bean->fetched_row['applications_end_date'])
            ? date('Y-m-d', strtotime($bean->fetched_row['applications_end_date']))
            : null;
        $prevStatus = $bean->fetched_row['status'] ?? null;
        $wasOpenToday = ($prevEndDate === $today) && !in_array($prevStatus, $closedStatuses, true);

        if ($wasOpenToday) {
            return;
        }

        self::sendClosingDateNotification($bean);
    }

    /**
     * Send closing date notification to assigned user.
     *
     * @param SugarBean $bean
     * @return void
     */
    protected static function sendClosingDateNotification($bean)
    {
        global $sugar_config;

        if (empty($bean->assigned_user_id)) {
            $GLOBALS['log']->error(__METHOD__ . ": Offer {$bean->id} has no assigned user.");
            return;
        }

        $user = BeanFactory::getBean('Users', $bean->assigned_user_id);
        if (empty($user) || empty($user->id)) {
            $GLOBALS['log']->error(__METHOD__ . ": Unable to retrieve assigned user for offer {$bean->id}.");
            return;
        }

        if (!$user->emailAddress || !($address = $user->emailAddress->getPrimaryAddress($user))) {
            $GLOBALS['log']->error(__METHOD__ . ": Assigned user {$user->id} has no email address.");
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
        $mail->AddAddress($address, trim($user->first_name . ' ' . $user->last_name));

        $subjectTemplate = translate('LBL_JOB_OFFER_CLOSE_DATE_TODAY_SUBJECT', 'stic_Job_Offers');
        if ($subjectTemplate === 'LBL_JOB_OFFER_CLOSE_DATE_TODAY_SUBJECT') {
            $subjectTemplate = 'The offer {0} closes today';
        }

        $bodyTemplate = translate('LBL_JOB_OFFER_CLOSE_DATE_TODAY_BODY', 'stic_Job_Offers');
        if ($bodyTemplate === 'LBL_JOB_OFFER_CLOSE_DATE_TODAY_BODY') {
            $bodyTemplate = 'The offer {0} closes today. Please review the offer details: {1}';
        }

        $url = rtrim($sugar_config['site_url'], '/') . "/index.php?module={$bean->module_name}&action=DetailView&record={$bean->id}";
        $link = "<a href=\"{$url}\">{$bean->name}</a>";

        $mail->Subject = string_format($subjectTemplate, array($bean->name));
        $mail->Body = from_html(string_format($bodyTemplate, array($bean->name, $link)));
        $mail->IsHTML(true);

        $mail->setMailerForSystem();
        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->fatal(__METHOD__ . ": Error sending notification email for offer {$bean->id}.");
        }
    }
}
