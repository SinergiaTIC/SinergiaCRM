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
     * @return void
     */
    public static function updateRelatedOffersApplicationsCounts($bean, $checkChanges)
    {
        if (empty($bean) || empty($bean->id)) {
            return;
        }

        $previousStatus = $bean->fetched_row['status'] ?? null;
        $currentStatus = $bean->status ?? null;
        $statusChanged = empty($bean->fetched_row) || $previousStatus !== $currentStatus;

        $previousOfferId = $bean->fetched_row['stic_job_applications_stic_job_offersstic_job_offers_ida'] ?? null;
        $offerId = self::getRelatedOfferId($bean);
        $offerChanged = empty($bean->fetched_row) || $previousOfferId !== $offerId;

        $offerIds = array_filter(array_unique(array($offerId, $previousOfferId)));

        if ($checkChanges && !$statusChanged && !$offerChanged) {
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
            $relatedOffers = $bean->stic_job_applications_stic_job_offers->getBeans();
            if (!empty($relatedOffers)) {
                $firstOffer = reset($relatedOffers);
                return !empty($firstOffer->id) ? $firstOffer->id : null;
            }
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
     * Notify offer interlocutor when application status changes to Presented
     *
     * @param SugarBean $jobApplicationBean
     * @return void
     */
    public static function notifyInterlocutorOnPresented($jobApplicationBean)
    {
        if (empty($jobApplicationBean) || empty($jobApplicationBean->id)) {
            return;
        }

        $currentStatus = $jobApplicationBean->status ?? null;
        if ($currentStatus !== 'presented') {
            return;
        }

        $previousStatus = $jobApplicationBean->fetched_row['status'] ?? null;
        if (!empty($previousStatus) && $previousStatus === $currentStatus) {
            return;
        }

        $offerId = self::getRelatedOfferId($jobApplicationBean);
        if (empty($offerId)) {
            return;
        }

        $offerBean = BeanFactory::getBean('stic_Job_Offers', $offerId);
        if (empty($offerBean) || empty($offerBean->id)) {
            return;
        }

        if (empty($offerBean->status_notifications_enabled)) {
            return;
        }

        $interlocutorId = $offerBean->contact_id_c ?? '';
        if (empty($interlocutorId)) {
            return;
        }

        $interlocutorBean = BeanFactory::getBean('Contacts', $interlocutorId);
        if (empty($interlocutorBean) || empty($interlocutorBean->id)) {
            return;
        }

        $templateId = self::getInterlocutorNotificationTemplateId($offerBean);
        if (empty($templateId)) {
            return;
        }

        $outboundEmail = self::getDefaultOutboundEmailAccount();
        if (empty($outboundEmail)) {
            $GLOBALS['log']->error(__METHOD__ . ': missing outbound configuration.');
            return;
        }

        self::sendNotificationEmail(
            $jobApplicationBean,
            $offerBean,
            $interlocutorBean,
            $templateId,
            $outboundEmail,
            'presented interlocutor'
        );
    }

    /**
     * Notify assigned user and interlocutor when a candidate cancels from the portal
     *
     * @param SugarBean $jobApplicationBean
     * @return void
     */
    public static function notifyOnPortalCancellation($jobApplicationBean)
    {
        if (empty($jobApplicationBean) || empty($jobApplicationBean->id)) {
            return;
        }

        $currentStatus = $jobApplicationBean->status ?? null;
        if ($currentStatus !== 'rejected_closed') {
            return;
        }

        $previousStatus = $jobApplicationBean->fetched_row['status'] ?? null;
        if (!empty($previousStatus) && $previousStatus === $currentStatus) {
            return;
        }

        $rejectionReason = $jobApplicationBean->rejection_reason ?? null;
        if ($rejectionReason !== 'resignation') {
            return;
        }

        $offerId = self::getRelatedOfferId($jobApplicationBean);
        if (empty($offerId)) {
            return;
        }

        $offerBean = BeanFactory::getBean('stic_Job_Offers', $offerId);
        if (empty($offerBean) || empty($offerBean->id)) {
            return;
        }

        if (empty($offerBean->status_notifications_enabled)) {
            return;
        }

        $outboundEmail = self::getDefaultOutboundEmailAccount();
        if (empty($outboundEmail)) {
            $GLOBALS['log']->error(__METHOD__ . ': missing outbound configuration.');
            return;
        }

        $assignedUserId = $jobApplicationBean->assigned_user_id ?? '';
        if (!empty($assignedUserId)) {
            $assignedUserBean = BeanFactory::getBean('Users', $assignedUserId);
            $assignedTemplateId = self::getPortalCancellationAssignedUserTemplateId($offerBean);
            if (!empty($assignedUserBean) && !empty($assignedUserBean->id) && !empty($assignedTemplateId)) {
                self::sendNotificationEmail(
                    $jobApplicationBean,
                    $offerBean,
                    $assignedUserBean,
                    $assignedTemplateId,
                    $outboundEmail,
                    'portal cancellation (assigned user)'
                );
            }
        }

        $interlocutorId = $offerBean->contact_id_c ?? '';
        if (!empty($interlocutorId)) {
            $interlocutorBean = BeanFactory::getBean('Contacts', $interlocutorId);
            $interlocutorTemplateId = self::getPortalCancellationInterlocutorTemplateId($offerBean);
            if (!empty($interlocutorBean) && !empty($interlocutorBean->id) && !empty($interlocutorTemplateId)) {
                self::sendNotificationEmail(
                    $jobApplicationBean,
                    $offerBean,
                    $interlocutorBean,
                    $interlocutorTemplateId,
                    $outboundEmail,
                    'portal cancellation (interlocutor)'
                );
            }
        }
    }

    /**
     * Resolve the interlocutor notification template id
     *
     * @param SugarBean $offerBean
     * @return string|null
     */
    protected static function getInterlocutorNotificationTemplateId($offerBean)
    {
        $templateId = $offerBean->emailtemplate_interlocutor_id ?? null;
        if (empty($templateId)) {
            $templateId = '4f2c7b91-0c3e-4a2b-9b3e-7c4a9b1e9001';
        }

        return self::getValidNotificationTemplateId($templateId);
    }

    /**
     * Resolve portal cancellation template id for assigned user
     *
     * @return string|null
     */
    protected static function getPortalCancellationAssignedUserTemplateId($offerBean)
    {
        $templateId = $offerBean->emailtemplate_cancelled_assigned_user_id ?? null;
        if (empty($templateId)) {
            $templateId = '4f2c7b91-0c3e-4a2b-9b3e-7c4a9b1e9002';
        }

        return self::getValidNotificationTemplateId($templateId);
    }

    /**
     * Resolve portal cancellation template id for interlocutor
     *
     * @return string|null
     */
    protected static function getPortalCancellationInterlocutorTemplateId($offerBean)
    {
        $templateId = $offerBean->emailtemplate_cancelled_interlocutor_id ?? null;
        if (empty($templateId)) {
            $templateId = '4f2c7b91-0c3e-4a2b-9b3e-7c4a9b1e9003';
        }

        return self::getValidNotificationTemplateId($templateId);
    }

    /**
     * Send notification email to a recipient
     *
     * @param SugarBean $jobApplicationBean
     * @param SugarBean $offerBean
     * @param SugarBean $recipientBean
     * @param string $templateId
     * @param SugarBean $outboundEmail
     * @param string $contextLabel
     * @return bool
     */
    protected static function sendNotificationEmail($jobApplicationBean, $offerBean, $recipientBean, $templateId, $outboundEmail, $contextLabel)
    {
        if (empty($jobApplicationBean) || empty($offerBean) || empty($recipientBean) || empty($templateId) || empty($outboundEmail)) {
            return false;
        }

        $destAddress = '';
        if (!empty($recipientBean->emailAddress)) {
            $destAddress = $recipientBean->emailAddress->getPrimaryAddress($recipientBean);
        }
        if (empty($destAddress) && !empty($recipientBean->email1)) {
            $destAddress = $recipientBean->email1;
        }

        if (empty($destAddress)) {
            $GLOBALS['log']->error(
                "Notification not sent for application {$jobApplicationBean->id} ({$contextLabel}): no recipient email address."
            );
            return false;
        }

        require_once 'SticInclude/Utils.php';
        $parsedMailArray = SticUtils::parseEmailTemplate($templateId, array(
            $jobApplicationBean,
            $offerBean,
            $recipientBean,
        ));

        $subject = $parsedMailArray['subject'] ?? '';
        $bodyHtml = $parsedMailArray['body_html'] ?? '';
        $bodyText = $parsedMailArray['body'] ?? '';

        if (empty($subject) || (empty($bodyHtml) && empty($bodyText))) {
            $GLOBALS['log']->error(
                "Notification not sent for application {$jobApplicationBean->id} ({$contextLabel}): parsed template is empty."
            );
            return false;
        }

        require_once 'include/SugarPHPMailer.php';
        require_once 'modules/Emails/Email.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();

        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = !empty($outboundEmail->smtp_from_addr) ? $outboundEmail->smtp_from_addr : ($defaults['email'] ?? '');
        $mail->FromName = !empty($outboundEmail->smtp_from_name) ? $outboundEmail->smtp_from_name : ($defaults['name'] ?? '');
        $mail->AddAddress($destAddress);
        $mail->Subject = $subject;
        if (!empty($bodyHtml)) {
            $mail->Body = from_html($bodyHtml);
            $mail->isHtml(true);
            $mail->AltBody = from_html($bodyText);
        } else {
            $mail->Body = $bodyText;
            $mail->isHtml(false);
        }
        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->error(
                "Notification send error for application {$jobApplicationBean->id} ({$contextLabel}): " . $mail->ErrorInfo
            );
            return false;
        }

        $GLOBALS['log']->info("Notification sent for application {$jobApplicationBean->id} ({$contextLabel}) to {$destAddress}.");
        return true;
    }

    /**
     * Validate notification email template id
     *
     * @param string|null $templateId
     * @return string|null
     */
    protected static function getValidNotificationTemplateId($templateId)
    {
        if (empty($templateId)) {
            return null;
        }

        $templateBean = BeanFactory::getBean('EmailTemplates', $templateId);
        if (!empty($templateBean) && !empty($templateBean->id) && empty($templateBean->deleted)) {
            return $templateBean->id;
        }

        return null;
    }

    /**
     * Get first available outbound email account (system)
     *
     * @return OutboundEmailAccounts|null
     */
    protected static function getDefaultOutboundEmailAccount()
    {
        if (!class_exists('InboundEmail')) {
            require_once 'modules/InboundEmail/InboundEmail.php';
        }
        $ie = new InboundEmail();
        $user = $GLOBALS['current_user'] ?? null;
        $defaultOutboundId = $user ? $ie->getUsersDefaultOutboundServerId($user) : '';

        if (!empty($defaultOutboundId)) {
            $defaultOutbound = BeanFactory::getBean('OutboundEmailAccounts', $defaultOutboundId);
            if (!empty($defaultOutbound) && !empty($defaultOutbound->id)) {
                return $defaultOutbound;
            }
        }

        $outboundEmailsFocus = BeanFactory::newBean('OutboundEmailAccounts');
        $outboundEmails = $outboundEmailsFocus->get_full_list("", "type != 'user'");
        if (!empty($outboundEmails)) {
            return array_shift($outboundEmails);
        }
        return null;
    }
}
