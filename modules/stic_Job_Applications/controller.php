<?php

class stic_Job_ApplicationsController extends SugarController
{
    /**
     * This action massively generates job applications for a single job offer and as many selected contacts
     * STIC#959
     */
    public function action_createMassJobApplications() {
        global $db, $current_user;
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
            $sql .= $where;
            $resultsQuery = $db->query($sql);
            while ($contactRow = $db->fetchByAssoc($resultsQuery)) {
                $contactsIds[]= $contactRow['id'];
            }
        } else {
            $contactsIds = explode(',', $_REQUEST['uid']);
        }
        if (is_array($contactsIds) && !empty($contactsIds) && $offerId && $offerName) {
            // Create a job application for each selected contact
            foreach($contactsIds as $contactId) {
                $jobApplicationBean = BeanFactory::newBean('stic_Job_Applications');
                $jobApplicationBean->start_date = date('Y-m-d');
                $jobApplicationBean->status = 'expected_presentation';
                $jobApplicationBean->stic_job_applications_contactscontacts_ida = $contactId;
                $jobApplicationBean->stic_job_applications_stic_job_offersstic_job_offers_ida = $offerId;
                $jobApplicationBean->assigned_user_id = $current_user->id;
                $jobApplicationBean->save();
            }
            SugarApplication::redirect('index.php?module=stic_Job_Applications&action=index&query=true&searchFormTab=advanced_search&status_advanced=expected_presentation&range_start_date_advanced='.date('Y-m-d').'&stic_job_applications_stic_job_offers_name_advanced='.$offerName);
        } 
    }
}
