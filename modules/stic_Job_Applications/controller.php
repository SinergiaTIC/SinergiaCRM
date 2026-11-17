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

class stic_Job_ApplicationsController extends SugarController
{
    /**
     * This action massively generates job applications for a single job offer and as many selected contacts
     * STIC#959
     */
    public function action_createMassJobApplications() {
        global $db;
        $offerId = $_REQUEST['offerId'] ?? '';
        $offerName = $_REQUEST['offerName'] ?? '';
        $contactsIds = array();
        // Build and run the query that retrieves the records selected in Contacts ListView
        if (isset($_REQUEST['select_entire_list']) && $_REQUEST['select_entire_list'] == '1' && isset($_REQUEST['current_query_by_page'])) {
            $sql = "SELECT id from contacts WHERE deleted=0";
            require_once 'include/export_utils.php';
            $retArray = generateSearchWhere('Contacts', $_REQUEST['current_query_by_page']);
            $where = '';
            if (!empty($retArray['where'])) {
                $where = " AND " . $retArray['where'];
            }

            $focus = BeanFactory::newBean('Contacts');
            if ($focus->bean_implements('ACL')) {
                if (!ACLController::checkAccess($focus->module_dir, 'list', true)) {
                    ACLController::displayNoAccess();
                    sugar_die('');
                }

                $accessWhere = $focus->buildAccessWhere('list');
                if (!empty($accessWhere)) {
                    $where .= ' AND ' . $accessWhere;
                }
            }
            $sql .= $where;
            $resultsQuery = $db->query($sql);
            while ($contactRow = $db->fetchByAssoc($resultsQuery)) {
                $contactsIds[]= $contactRow['id'];
            }
        } else {
            $contactsIds = explode(',', $_REQUEST['uid']);
        }
        if (is_array($contactsIds) && !empty($contactsIds) && $offerId && $offerName) {
            // Get the assigned user of the offer to inherit it in the created job applications
            $offerBean = BeanFactory::getBean('stic_Job_Offers', $offerId);
            $offerAssignedUserId = !empty($offerBean) && !empty($offerBean->id) ? $offerBean->assigned_user_id : '';
            // Create a job application for each selected contact
            foreach($contactsIds as $contactId) {
                $jobApplicationBean = BeanFactory::newBean('stic_Job_Applications');
                $jobApplicationBean->start_date = date('Y-m-d');
                $jobApplicationBean->status = 'expected_presentation';
                $jobApplicationBean->stic_job_applications_contactscontacts_ida = $contactId;
                $jobApplicationBean->stic_job_applications_stic_job_offersstic_job_offers_ida = $offerId;
                $jobApplicationBean->assigned_user_id = $offerAssignedUserId;
                $jobApplicationBean->save();
            }
            SugarApplication::redirect('index.php?module=stic_Job_Applications&action=index&query=true&searchFormTab=advanced_search&status_advanced=expected_presentation&range_start_date_advanced='.date('Y-m-d').'&stic_job_applications_stic_job_offers_name_advanced='.$offerName);
        } 
    }

    /**
     * Action that returns the assigned user of a job offer.
     * It is used from the edit view to prefill the assigned user of a new job application
     * with the assigned user of the related offer.
     *
     * @return void
     */
    public function action_getOfferAssignedUser() {
        $offerId = $_POST['offerId'] ?? '';
        $response['code'] = 'No data';
        if (!empty($offerId)) {
            $offerBean = BeanFactory::getBean('stic_Job_Offers', $offerId);
            if (!empty($offerBean) && !empty($offerBean->id)) {
                $response['code'] = 'OK';
                $response['data']['assigned_user_id'] = $offerBean->assigned_user_id ?? '';
                $response['data']['assigned_user_name'] = $offerBean->assigned_user_name ?? '';
            }
        }
        echo json_encode($response);
        exit;
    }
}
