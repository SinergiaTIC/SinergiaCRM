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
     * Recalculate and store Job Applications counts for an offer
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

        $statusPrefix = 'stic_job_applications_count_';
        $statuses = array();
        $offerBean = BeanFactory::newBean('stic_Job_Offers');
        $fieldDefs = $offerBean->field_defs ?? array();
        foreach (array_keys($fieldDefs) as $fieldName) {
            if (strpos($fieldName, $statusPrefix) !== 0) {
                continue;
            }
            if ($fieldName === $statusPrefix . 'total') {
                continue;
            }
            $statusKey = substr($fieldName, strlen($statusPrefix));
            $statuses[$statusKey] = true;
        }
        $statusKeys = array_keys($statuses);
        $counts = array_fill_keys($statusKeys, 0);

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
        if ($result === false) {
            $GLOBALS['log']->error(__METHOD__ . ": Error executing counts query for offer {$offerId}.");
            return;
        }

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
        $updateResult = $db->query($update);
        if ($updateResult === false) {
            $GLOBALS['log']->error(__METHOD__ . ": Error updating counts for offer {$offerId}.");
        }
    }

    /**
     * Notify on offer status change with LPO and Notification campaign
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

        // Create notification campaign for the new status
        $newStatus = $bean->status;
        
        self::createNotificationCampaign(
            'stic_Job_Offers',
            $bean,
            $newStatus
        );
    }

    /**
     * Create a Notification campaign for a status change
     *
     * @param string $parentType Parent module name
     * @param SugarBean $parentBean Parent bean
     * @param string $newStatus New status value
     * @return void
     */
    protected static function createNotificationCampaign($parentType, $parentBean, $newStatus)
    {
        global $current_user, $timedate;

        $templateId = self::getNotificationTemplateId('job_offers', $parentBean);
        $outboundEmail = self::getDefaultOutboundEmailAccount();
        $inboundEmailId = self::getDefaultInboundEmailId();

        if (empty($templateId) || empty($outboundEmail)) {
            $GLOBALS['log']->error(
                "Notification campaign not created for {$parentType} {$parentBean->id}: missing template/outbound configuration."
            );
            return;
        }

        $startDate = $timedate->nowDbDate();

        // Get or create unique prospect list for this offer
        $lpoBean = self::getOrCreateNotificationLpo($parentBean->id, $parentBean->name, $parentBean->assigned_user_id ?? $current_user->id, $parentBean->assigned_user_name ?? $current_user->user_name);
        if (empty($lpoBean) || empty($lpoBean->id)) {
            $GLOBALS['log']->error(
                "Notification campaign not created for {$parentType} {$parentBean->id}: could not get or create prospect list."
            );
            return;
        }

        // Update prospect list contacts
        $contactIds = self::getRelatedApplicantsContactIds($parentBean->id);
        self::updateLpoContacts($lpoBean, $contactIds, $parentBean->assigned_user_id);

        $campaign = self::getOrCreateNotificationCampaign($parentType, $parentBean, $lpoBean, $templateId, $outboundEmail, $inboundEmailId, $startDate, $newStatus);

        if (empty($campaign) || empty($campaign->id)) {
            $GLOBALS['log']->error(
                "Notification campaign could not be created or updated for {$parentType} {$parentBean->id}."
            );
            return;
        }

        $GLOBALS['log']->info("Notification campaign updated for {$parentType} {$parentBean->id}.");
    }

    /**
     * Create a new notification campaign for a status change
     *
     * @param string $parentType
     * @param SugarBean $parentBean
     * @param SugarBean $lpoBean
     * @param string $templateId
     * @param SugarBean $outboundEmail
     * @param string $inboundEmailId
     * @param string $startDate
     * @return SugarBean|null
     */
    protected static function getOrCreateNotificationCampaign($parentType, $parentBean, $lpoBean, $templateId, $outboundEmail, $inboundEmailId, $startDate, $newStatus)
    {
        global $app_list_strings, $mod_strings;

        $campaignName = "{$app_list_strings['emailTemplates_type_list']['notification']} {$mod_strings['LBL_STATUS']} {$app_list_strings['moduleListSingular'][$parentType]} '{$parentBean->name}' - {$app_list_strings['stic_job_offers_status_list'][$newStatus]} - {$startDate}";

        $lpoIdFormatted = "^{$lpoBean->id}^";

        // Create a new campaign for each status change
        $campaign = BeanFactory::newBean('Campaigns');
        $campaign->campaign_type = 'Notification';
        $campaign->parent_type = $parentType;
        $campaign->parent_id = $parentBean->id;
        $campaign->parent_name = $parentBean->name;
        $campaign->assigned_user_id = $parentBean->assigned_user_id;
        $campaign->assigned_user_name = $parentBean->assigned_user_name;
        $campaign->start_date = $startDate;
        $campaign->name = $campaignName;

        $campaign->notification_prospect_list_ids = $lpoIdFormatted;
        $campaign->notification_template_id = $templateId;
        $campaign->notification_outbound_email_id = $outboundEmail->id;
        if (!empty($inboundEmailId)) {
            $campaign->notification_inbound_email_id = $inboundEmailId;
        }
        $campaign->notification_from_name = $outboundEmail->smtp_from_name;
        $campaign->notification_from_addr = $outboundEmail->smtp_from_addr;
        $campaign->notification_reply_to_name = $outboundEmail->reply_to_name;
        $campaign->notification_reply_to_addr = $outboundEmail->reply_to_addr;

        $campaign->save();
        return !empty($campaign->id) ? $campaign : null;
    }

    /**
     * Get or create a unique prospect list for offer notifications
     *
     * @param string $offerId
     * @param string $offerName
     * @param string $assignedUserId
     * @param string $assignedUserName
     * @return SugarBean|null
     */
    protected static function getOrCreateNotificationLpo($offerId, $offerName, $assignedUserId, $assignedUserName)
    {
        global $app_list_strings;

        if (empty($offerId)) {
            return null;
        }

        $db = DBManagerFactory::getInstance();
        $offerId = $db->quote($offerId);

        // Use offer name in LPO for better identification, but ensure it's unique by including offer ID
        $lpoName = "LPO {$app_list_strings['moduleListSingular']['stic_Job_Offers']} '{$offerName}' - ({$offerId})";

        // Try to find existing LPO for this offer
        $query = "SELECT id FROM prospect_lists WHERE name = '{$db->quote($lpoName)}' AND deleted = 0 LIMIT 1";
        $result = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            return BeanFactory::getBean('ProspectLists', $row['id']);
        }

        // Create new LPO if it doesn't exist
        $lpoBean = BeanFactory::newBean('ProspectLists');
        $lpoBean->name = $lpoName;
        $lpoBean->list_type = 'default';
        $lpoBean->assigned_user_id = $assignedUserId;
        $lpoBean->assigned_user_name = $assignedUserName;
        $lpoBean->save();

        return !empty($lpoBean->id) ? $lpoBean : null;
    }

    /**
     * Update LPO contacts, adding new ones and removing old ones
     *
     * @param SugarBean $lpoBean
     * @param array $contactIds
     * @param string $assignedUserId
     * @return void
     */
    protected static function updateLpoContacts($lpoBean, $contactIds, $assignedUserId)
    {
        if (empty($lpoBean) || empty($lpoBean->id)) {
            return;
        }

        // Get current contacts in LPO
        if (!$lpoBean->load_relationship('contacts')) {
            return;
        }

        $currentContactIds = $lpoBean->contacts->get();
        $currentContactIds = is_array($currentContactIds) ? $currentContactIds : array();

        // Remove contacts no longer in the offer
        $toRemove = array_diff($currentContactIds, $contactIds);
        foreach ($toRemove as $contactId) {
            $lpoBean->contacts->delete($lpoBean->id, $contactId);
        }

        // Add new contacts
        $toAdd = array_diff($contactIds, $currentContactIds);
        foreach ($toAdd as $contactId) {
            $lpoBean->contacts->add($contactId);
        }

        // Ensure assigned user is in the list
        if (!empty($assignedUserId)) {
            if ($lpoBean->load_relationship('users')) {
                $currentUserIds = $lpoBean->users->get();
                $currentUserIds = is_array($currentUserIds) ? $currentUserIds : array();
                if (!in_array($assignedUserId, $currentUserIds, true)) {
                    $lpoBean->users->add($assignedUserId);
                }
            }
        }
    }



    /**
    * Get contact ids for applications related to the offer
     *
     * @param string $offerId
     * @return array
     */
    protected static function getRelatedApplicantsContactIds($offerId)
    {
        if (empty($offerId)) {
            return array();
        }

        $db = DBManagerFactory::getInstance();
        $offerId = $db->quote($offerId);

        $rejectedStatus = $db->quote('rejected_closed');

        $query = "SELECT DISTINCT c.id
            FROM stic_job_applications_stic_job_offers_c rel
            INNER JOIN stic_job_applications ja ON rel.stic_job_applications_stic_job_offersstic_job_applications_idb = ja.id
            INNER JOIN stic_job_applications_contacts_c jac ON jac.stic_job_applications_contactsstic_job_applications_idb = ja.id
            INNER JOIN contacts c ON c.id = jac.stic_job_applications_contactscontacts_ida
            WHERE rel.deleted = 0
              AND jac.deleted = 0
              AND ja.deleted = 0
              AND c.deleted = 0
              AND (ja.status IS NULL OR ja.status <> '{$rejectedStatus}')
              AND rel.stic_job_applications_stic_job_offersstic_job_offers_ida = '{$offerId}'";

        $result = $db->query($query);
        $contactIds = array();
        while ($row = $db->fetchByAssoc($result)) {
            if (!empty($row['id'])) {
                $contactIds[] = $row['id'];
            }
        }
        return array_values(array_unique($contactIds));
    }


    /**
     * Get first available Notification email template id
     *
     * @return string|null
     */
    protected static function getNotificationTemplateId($templateKey, $parentBean = null)
    {
        $requestedTemplateId = (!empty($parentBean) && !empty($parentBean->emailtemplate_id))
            ? $parentBean->emailtemplate_id
            : null;
        $validTemplateId = self::getValidNotificationTemplateId($requestedTemplateId);
        if (!empty($validTemplateId)) {
            return $validTemplateId;
        }

        $templateIds = array(
            'job_offers' => 'c79e5db6-d89d-487f-a4ff-6e2801f7ade5',
        );
        $defaultTemplateId = $templateIds[$templateKey] ?? null;

        return self::getValidNotificationTemplateId($defaultTemplateId);
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

    /**
     * Get first available inbound bounce mailbox id for campaign bounce handling
     *
     * @return string|null
     */
    protected static function getDefaultInboundEmailId()
    {
        $db = DBManagerFactory::getInstance();
        
        // Try to get any active bounce mailbox
        $query = "SELECT id FROM inbound_email 
                  WHERE mailbox_type='bounce' AND status='Active' AND deleted='0' 
                  LIMIT 1";
        $result = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            return $row['id'];
        }

        // Try to get any active inbound email
        $query = "SELECT id FROM inbound_email 
                  WHERE status='Active' AND deleted='0' 
                  LIMIT 1";
        $result = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            return $row['id'];
        }

        return null;
    }

}
