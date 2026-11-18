<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


class stic_AWF_Responses extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_AWF_Responses';
    public $object_name = 'stic_AWF_Responses';
    public $table_name = 'stic_awf_responses';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $raw_payload;
    public $response_hash;
    public $status;
    public $execution_log;
    public $remote_ip;
    public $form_url;
    public $clean_referrer;
    public $user_agent;
    public $html_summary;
    public $processing_mode;

    public $is_automated_save = false;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	
    /**
     * Enable "edit" only in automated saving.
     * @param string $view
     * @param string $is_owner
     * @param string $in_group
     */
    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        $editViews = ['edit', 'editview', 'save'];
        if (in_array(strtolower($view), $editViews)) {
            if ($this->is_automated_save) {
                return true;
            }
            return false;
        }
        return parent::ACLAccess($view, $is_owner, $in_group);
    }

    /**
     * Cascade cleanup before logically deleting a response.
     * Deletes the dependent records so no orphaned rows remain active in the database:
     * 1. Links (stic_AWF_Links) linked through the stic_awf_responses_stic_awf_links relationship.
     * 2. Response Details (stic_AWF_Response_Details) linked through the stic_awf_responses_details relationship.
     * 3. Deferred Tickets (stic_AWF_Deferred_Tickets) linked by the stic_awf_responses_id_c foreign key.
     *    Pending or processing tickets are cancelled before being deleted so that WebhookHandler,
     *    ResumeHandler and ReturnHandler cannot resume their deferred flows afterwards.
     * Related Documents are not deleted (shared records); the platform cleans their relationship rows.
     *
     * @param string $id Response record id
     */
    public function mark_deleted($id)
    {
        // Respect the platform guard: with show_deleted the parent restores (mark_undeleted)
        // the record instead of deleting it, so the cascade must not run in that case.
        if (isset($_SESSION['show_deleted'])) {
            parent::mark_deleted($id);
            return;
        }

        // Nothing to cascade without a valid record id
        if (empty($this->id)) {
            parent::mark_deleted($id);
            return;
        }

        $log = $GLOBALS['log'] ?? null;

        // 1. Delete the links saved for this response
        if ($this->load_relationship('stic_1c31forms_links')) {
            $deletedLinks = 0;
            foreach ($this->stic_1c31forms_links->getBeans() as $linkBean) {
                if (!empty($linkBean->id) && empty($linkBean->deleted)) {
                    $linkBean->mark_deleted($linkBean->id);
                    $deletedLinks++;
                }
            }
            if (!empty($log)) {
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Deleted ' . $deletedLinks . ' related links for Response ID ' . $this->id);
            }
        } elseif (!empty($log)) {
            $log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Could not load relationship responses-links for Response ID ' . $this->id);
        }

        // 2. Delete the response details
        if ($this->load_relationship('details_link')) {
            $deletedDetails = 0;
            foreach ($this->details_link->getBeans() as $detailBean) {
                if (!empty($detailBean->id) && empty($detailBean->deleted)) {
                    $detailBean->mark_deleted($detailBean->id);
                    $deletedDetails++;
                }
            }
            if (!empty($log)) {
                $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Deleted ' . $deletedDetails . ' related response details for Response ID ' . $this->id);
            }
        } elseif (!empty($log)) {
            $log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Could not load relationship responses-details for Response ID ' . $this->id);
        }

        // 3. Cancel and delete the deferred tickets related to this response
        // Tickets have no relationship definition, so they are located by their foreign key
        $ticketCount = 0;
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT id, status FROM stic_awf_deferred_tickets
                WHERE stic_awf_responses_id_c = " . $db->quoted($this->id) . "
                AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $ticketBean = BeanFactory::getBean('stic_AWF_Deferred_Tickets', $row['id']);
            if (!empty($ticketBean) && !empty($ticketBean->id)) {
                // Cancel active tickets so no external callback or confirmation link can resume the flow
                if (in_array($row['status'], ['pending', 'processing'])) {
                    $ticketBean->status = 'cancelled';
                    $ticketBean->save();
                }
                $ticketBean->mark_deleted($row['id']);
                $ticketCount++;
            }
        }
        if (!empty($log)) {
            $log->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Cancelled/deleted ' . $ticketCount . ' related deferred tickets for Response ID ' . $this->id);
        }

        parent::mark_deleted($id);
    }
}
