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
// Scheduled task that calculates the age of the contacts in "contacts" database table, daily. The age is stored in the field stic_age_c.

$job_strings[]='calculateContactsAge';

function calculateContactsAge() {
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task calculateContactsAge');
    require_once 'custom/modules/Contacts/SticUtils.php';
    return ContactsUtils::calculateContactsAge();
}


// Scheduled task that resets the value of some config_override properties, like developer_mode or log level

$job_strings[]='sticCleanConfig';

function sticCleanConfig() {
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task sticCleanConfig');
    require_once 'SticInclude/CleanConfig.php';
    return SticCleanConfig::cleanConfig();
}


// Scheduled task for attendances daily generation in multisession events.
// Only attendances for current day sessions will be created.

$job_strings[] = 'createAttendances';

function createAttendances()
{
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task createAttendances');
    require_once 'modules/stic_Attendances/Utils.php';
    return stic_AttendancesUtils::createAttendances();
}


// Scheduled task for creating monthy recurring payments.
// Only payments linked to payment commitments will be created.

$job_strings[] = 'createCurrentMonthRecurringPayments';

function createCurrentMonthRecurringPayments()
{
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task createCurrentMonthRecurringPayments');
    require_once 'modules/stic_Payments/Utils.php';
    return stic_PaymentsUtils::createCurrentMonthRecurringPayments();
}


// Scheduled task that reminds the users by Email of opportunities approaching the due date.

$job_strings[]='opportunitiesReminder';

function opportunitiesReminder() {

    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task opportunitiesReminder');
    require_once 'custom/modules/Opportunities/SticUtils.php';
    return OpportunitiesUtils::opportunitiesReminder();
}

// Scheduled task for medication logs daily generation
// Only attendances for current day will be created.

$job_strings[] = 'createMedicationLogs';

function createMedicationLogs()
{
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task createMedicationLogs');
    require_once 'modules/stic_Medication_Log/Utils.php';
    return stic_Medication_LogUtils::createLogs();
}


// Scheduled task that runs different kind of validation for the data located in the CRM.

$job_strings[] = 'validationActions';

/**
 * Data analysis process.
 * Load the list of linked validation actions,
 * retrieve the necessary data set and execute the actions.
 * @param $scheduledJob Object Bean of the scheduled task
 * @return boolean
 */
function validationActions($scheduledJob)
{
    include_once 'modules/stic_Validation_Actions/Utils.php';
    return stic_Validation_ActionsUtils::runSchedulersValidationTasks($scheduledJob);
}


// Scheduled task that deletes from database the records where deleted = 1 was set at least N days before

$job_strings[] = 'sticPurgeDatabase';

/**
 * Deletes from the database the records where deleted = 1 was set at least N days before 
 * (N depends on config value 'stic_purge_database_days').
 * @return boolean
 */
function sticPurgeDatabase()
{
    global $sugar_config;
    $GLOBALS['log']->debug('Line '.__LINE__.': '.__METHOD__.':  Running the task purgeDatabase');

    $db = DBManagerFactory::getInstance();
    $tables = $db->getTablesArray();
    $queryString = array();

    if (!empty($tables)) {
        foreach ($tables as $kTable => $table) {
            // find tables with deleted=1
            $columns = $db->get_columns($table);
            // no deleted - won't delete
            if (empty($columns['deleted']) || empty($columns['date_modified'])) {
                continue;
            }

            $custom_columns = array();
            if (array_search($table . '_cstm', $tables)) {
                $custom_columns = $db->get_columns($table . '_cstm');
            }

            if (empty($sugar_config['stic_purge_database_days'])) {
                $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.':  $sugar_config["stic_purge_database_days"] is not provided.');
                return false;
            }

            $backupDays = $sugar_config['stic_purge_database_days'];             
            $today = date("Y-m-d");
            $limitDate = date("Y-m-d", strtotime($today." - ". $backupDays ."days"));

            $qDel = "SELECT * FROM $table WHERE deleted = 1 AND date_modified <= '".$limitDate."'";
            $rDel = $db->query($qDel);

            while ($aDel = $db->fetchByAssoc($rDel, false)) {
                $id = $db->quoted($aDel['id']);
                if (!empty($custom_columns) && !empty($aDel['id'])) {
                    $db->query('DELETE FROM ' . $table . '_cstm WHERE id_c = '.$id );
                }
                $db->query('DELETE FROM ' . $table . ' WHERE id = '.$id);
            }
        }
        return true;
    }
    return false;
}

// Scheduled task to process Phone messages queued
$job_strings[] = 'sticSendPhoneMessages';

/**
 * Deletes from the database the records where deleted = 1 was set at least N days before 
 * (N depends on config value 'stic_purge_database_days').
 * @return boolean
 */
function sticSendPhoneMessages() {
    require_once('modules/stic_MessagesMan/Utils.php');

    return stic_MessagesManUtils::sendQueuedMessages(false);
}

// Scheduled task to clean up expired AWF deferred tickets
$job_strings[] = 'sticAWFCancelExpiredTickets';

/**
 * Cancels deferred tickets that have exceeded their expiration date.
 * Finds tickets with status='pending' AND expiration_date < NOW() and marks them as 'cancelled'.
 * @return boolean
 */
function sticAWFCancelExpiredTickets() {
    require_once('modules/stic_AWF_Forms/Utils.php');

    return stic_AWF_FormsUtils::cancelExpiredTickets();
}

// Scheduled task to process async AWF responses
$job_strings[] = 'sticAWFProcessAsyncResponses';

/**
 * Processes pending async form responses in batch.
 * Called by scheduler every minute.
 * @return boolean
 */
function sticAWFProcessAsyncResponses() {
    require_once 'modules/stic_AWF_Forms/core/AsyncResponseProcessor.php';
    require_once 'modules/stic_AWF_Forms/core/ResponseProcessingService.php';
    require_once 'modules/stic_AWF_Forms/core/includes.php';
    
    $result = AsyncResponseProcessor::processBatch();
    
    $GLOBALS['log']->info("Line " . __LINE__ . ": " . __METHOD__ . ": Async batch complete. Processed: {$result['processed']}, Errors: {$result['errors']}");
    
    return $result['processed'] > 0 || $result['errors'] > 0;
}

// Job function to resume deferred flows asynchronously
$job_strings[] = 'sticAWFResumeDeferredFlow';

/**
 * Resumes a deferred form flow (success or error) for a ticket.
 * Called by the SuiteCRM job queue after a payment webhook resolves.
 * Expected $data format: {"ticket_id": "xxx", "is_success": true}
 * @param string $data JSON-encoded job data
 * @return boolean
 */
function sticAWFResumeDeferredFlow($data) {
    $jobData = json_decode($data, true);
    if (empty($jobData['ticket_id'])) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Missing ticket_id in job data");
        return false;
    }

    require_once 'modules/stic_AWF_Forms/core/includes.php';
    require_once 'modules/stic_AWF_Deferred_Tickets/stic_AWF_Deferred_Tickets.php';

    $ticket = BeanFactory::getBean('stic_AWF_Deferred_Tickets', $jobData['ticket_id']);
    if (!$ticket || empty($ticket->id)) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Ticket not found: {$jobData['ticket_id']}");
        return false;
    }

    $isSuccess = !empty($jobData['is_success']);

    $responseBean = BeanFactory::getBean('stic_AWF_Responses', $ticket->stic_awf_responses_id_c);
    if (empty($responseBean) || empty($responseBean->id)) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Response not found for ticket {$ticket->id}");
        return false;
    }

    $responseBean->load_relationship('stic_69c1s_responses');
    $formId = null;
    if (!empty($responseBean->stic_69c1s_responses)) {
        $relatedForms = $responseBean->stic_69c1s_responses->getBeans();
        if (!empty($relatedForms)) {
            $formBeanRel = reset($relatedForms);
            $formId = $formBeanRel->id;
        }
    }

    if (empty($formId)) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Cannot determine form ID for ticket {$ticket->id}");
        return false;
    }

    $formBean = BeanFactory::getBean('stic_AWF_Forms', $formId);
    if (empty($formBean) || empty($formBean->id)) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Form not found: {$formId}");
        return false;
    }

    $jsonConfig = html_entity_decode($formBean->configuration ?? '', ENT_QUOTES, 'UTF-8');
    $configData = json_decode($jsonConfig, true);
    if (!$configData) {
        $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invalid form config for form {$formId}");
        return false;
    }
    $formConfig = FormConfig::fromJsonArray($configData);
    $formData = json_decode($responseBean->raw_payload, true) ?: [];

    $context = new ExecutionContext(
        $formBean->id,
        $responseBean->id,
        $formData,
        $formConfig,
        null,
        $responseBean->assigned_user_id,
        $responseBean
    );

    $contextData = json_decode($ticket->context_data, true) ?: [];
    $context->setCustomData($contextData);

    $successFlowId = $contextData['flow_success_id'] ?? null;
    $errorFlowId   = $contextData['flow_error_id']   ?? null;

    $successFlow = ($successFlowId !== null && $successFlowId !== '')
        ? ($formConfig->flows[$successFlowId] ?? null)
        : null;
    $errorFlow = ($errorFlowId !== null && $errorFlowId !== '')
        ? ($formConfig->flows[$errorFlowId] ?? null)
        : null;

    if ($isSuccess) {
        if ($successFlow === null) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": No success flow for ticket {$ticket->id}");
            return true;
        }
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Executing deferred success flow for ticket {$ticket->id}");
        $executor = new ServerActionFlowExecutor($context);
        $executor->executeFlow($successFlow, $errorFlow);

        if ($context->responseBean) {
            $context->responseBean->status = 'processed';
            $context->responseBean->save();
        }
    } else {
        if ($errorFlow === null) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": No error flow for ticket {$ticket->id}");
            return true;
        }
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Executing deferred error flow for ticket {$ticket->id}");
        $executor = new ServerActionFlowExecutor($context);
        $executor->executeFlow($errorFlow);

        if ($context->responseBean) {
            $context->responseBean->status = 'error';
            $context->responseBean->save();
        }
    }

    return true;
}
