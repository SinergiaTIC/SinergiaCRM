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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "modules/stic_AWF_Forms/core/includes.php";
require_once "modules/stic_AWF_Deferred_Tickets/stic_AWF_Deferred_Tickets.php";
require_once "modules/stic_AWF_Incoming_Events/stic_AWF_Incoming_Events.php";
require_once "include/SugarQueue/SugarJobQueue.php";

/**
 * EntryPoint: WebhookHandler
 * Receives and processes webhook responses from payment gateways.
 *
 * This entry point is fully gateway-agnostic. All gateway-specific logic
 * (signature verification, response parsing, event handling) lives in the
 * individual payment strategy classes. The WebhookHandler only:
 *   1. Creates an IncomingEvent log record
 *   2. Extracts the external transaction ID (delegated to the strategy)
 *   3. Atomically finds and locks the Deferred Ticket
 *   4. Rebuilds the ExecutionContext from the ticket
 *   5. Calls PaymentRouterAction::processWebhook() → strategy->resolve()
 *   6. Updates the ticket status and resumes the form flow
 *
 * When no ticket is found (e.g. Stripe recurring events), the strategy's
 * resolve() method handles it directly.
 */
class WebhookHandler
{
    public function run(): void
    {
        global $current_user;
        $current_user = BeanFactory::newBean('Users');
        $current_user->getSystemUser();

        $source  = $_REQUEST['source'] ?? '';
        $requestData = $_POST;
        $rawPayload = file_get_contents('php://input');
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        // Create IncomingEvent log record
        $incomingEvent = BeanFactory::newBean('stic_AWF_Incoming_Events');
        $incomingEvent->name = 'AWF Webhook: ' . $source . ' - ' . date('Y-m-d H:i:s');
        $incomingEvent->token = $_REQUEST['token'] ?? null;
        $incomingEvent->source = $source;
        $incomingEvent->raw_payload = $rawPayload ?: json_encode($requestData);
        $incomingEvent->status = 'new';
        $incomingEvent->date_received = date('Y-m-d H:i:s');
        $incomingEvent->save();

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Received webhook from source='{$source}'. IncomingEvent ID={$incomingEvent->id}");

        // Extract Identifier
        $searchField = 'token_hash';
        $identifier = $_REQUEST['token'] ?? null;

        if (empty($identifier) && !empty($source)) {
            // No token in URL, but we have a source. Let's ask the actions.
            $searchField = 'external_transaction_id';
            
            $deferredActions = ActionDiscoveryService::discoverActions([ActionType::DEFERRED]);
            foreach ($deferredActions as $action) {
                if ($action instanceof IWebhookDecodable && $action->handlesSource($source)) {
                    $identifier = $action->extractTokenFromEvent($source, $requestData, $rawPayload, $headers);
                    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Action '{$action->getName()}' handled source '{$source}' and extracted identifier.");
                    break;
                }
            }
        }

        if (empty($identifier)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Could not extract identifier for source='{$source}'. No matching Decodable action found or extraction failed.");
            $incomingEvent->status = 'ignored';
            $incomingEvent->last_error_message = "Could not extract identifier";
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
            http_response_code(400);
            die("Cannot determine transaction ID");
        }

        if ($searchField === 'external_transaction_id') {
            $incomingEvent->external_transaction_id = $identifier;
        }
        $incomingEvent->save();

        // Atomically find and lock the Deferred Ticket
        $ticket = $this->findTicket($identifier, $searchField);

        // Build context and resolve
        $context = null;
        if ($ticket) {
            $result = $this->processWithTicket($ticket, $requestData, $rawPayload, $incomingEvent, $context);
        } else {
            $result = $this->processWithoutTicket($source, $requestData, $rawPayload, $incomingEvent);
        }

        // Handle result
        $this->handleResult($result, $ticket, $incomingEvent, $context);
    }

    /**
     * Process a webhook when a matching Deferred Ticket exists.
     * Rebuilds the execution context from the ticket, then delegates
     * to PaymentRouterAction::processWebhook() which calls strategy->resolve().
     */
    private function processWithTicket(stic_AWF_Deferred_Tickets $ticket, array $rawData, string $rawBody, $incomingEvent, ?ExecutionContext &$outContext): ActionResult
    {
        try {
            $context = stic_AWFUtils::rebuildContextFromTicket($ticket);
        } catch (Exception $e) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Failed to rebuild context for Ticket ID={$ticket->id}: " . $e->getMessage());
            $ticket->status = 'failed';
            $ticket->save();
            $incomingEvent->status = 'error';
            $incomingEvent->last_error_message = "Context rebuild failed: " . $e->getMessage();
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
            http_response_code(500);
            die("Internal error");
        }

        // Inject rawBody into context for strategies that need it
        $contextObj = DeferredContextData::fromJson($ticket->context_data);
        $contextObj->setCustom('_rawBody', $rawBody);
        $context->deferredContext = $contextObj;

        $outContext = $context;

        $actionClass = $context->deferredContext->actionClass;
        if (empty($actionClass) || !class_exists($actionClass)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Handler class {$actionClass} not found for webhook processing.");
            return new ActionResult(ResultStatus::ERROR, null, "Handler class '{$actionClass}' not found for webhook processing.");
        }
        $actionDefinition = new $actionClass();
        return $actionDefinition->processWebhook($context, $rawData);
    }

    /**
     * Process a webhook when no matching Deferred Ticket exists.
     * Creates a strategy from the source identifier and calls resolve() directly.
     * This handles events like Stripe subscription/invoice events that occur
     * after the initial checkout and have no associated ticket.
     */
    private function processWithoutTicket(string $source, array $rawData, string $rawBody, $incomingEvent): ActionResult
    {
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: No ticket found for source='{$source}'. Delegating to strategy directly.");

        $strategy = stic_AWF_PaymentStrategyFactory::createFromSource($source);
        if ($strategy === null) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Unknown source '{$source}' and no ticket found.");
            $incomingEvent->status = 'ignored';
            $incomingEvent->last_error_message = "Ticket not found or already processed";
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
            http_response_code(200);
            die("Already processed");
        }

        $context = new ExecutionContext('', '', [], new FormConfig(), null, '');
        $context->setCustomData(['_rawBody' => $rawBody]);

        $result = $strategy->resolve($context, new ActionResult(ResultStatus::WAIT, null, '', []));

        $incomingEvent->status = $result->isOk() ? 'processed' : 'error';
        $incomingEvent->last_error_message = $result->message ?? '';
        $incomingEvent->date_processed = date('Y-m-d H:i:s');
        $incomingEvent->save();

        return $result;
    }

    /**
     * Handles the ActionResult from strategy->resolve(), updating ticket,
     * resuming flows, and sending the HTTP response.
     */
    private function handleResult(ActionResult $result, ?stic_AWF_Deferred_Tickets $ticket, $incomingEvent, ?ExecutionContext $context): void
    {
        // Update status depending on result
        if ($ticket) {
            if ($result->isOk()) {
                $ticket->status = 'resolved';
                $ticket->save();
            } elseif ($result->isError()) {
                $maxRetries = 3;
                $retryCount = intval($ticket->retry_count ?? 0) + 1;
                $ticket->retry_count = $retryCount;
                $ticket->last_error_message = $result->getMessage() ?? 'Unknown error';

                if ($retryCount < $maxRetries) {
                    $ticket->status = 'pending';
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Ticket [{$ticket->id}] failed (attempt {$retryCount}/{$maxRetries}). Reset to pending for retry.");
                } else {
                    $ticket->status = 'failed';
                    $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Ticket [{$ticket->id}] permanently failed after {$maxRetries} attempts. Error: " . $ticket->last_error_message);
                    $this->createAdminAlert($ticket, $ticket->last_error_message);
                }
                $ticket->save();

                // If final status is failed: Change to error flow
                if ($ticket->status === 'failed') {
                    $this->enqueueDeferredFlow($ticket->id, false);
                }
            }
            // If is WAIT, do not change status
        }

        // Register incoming event
        if ($incomingEvent->status !== 'processed') {
            // Set event as processed but save error if any
            $incomingEvent->status = 'processed';
            $incomingEvent->last_error_message = $result->isError() ? ($result->getMessage() ?? 'Unknown error') : '';
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
        }

        // Redirect UI if url has param 'redirect'
        if (!empty($_REQUEST['redirect']) && $ticket) {
            $token = $ticket->token_hash;
            // ReturnHandler will check ticket status to show correct message
            header("Location: index.php?entryPoint=stic_AWF_returnHandler&token=" . urlencode($token));
            exit;
        }

        // Return 200 even for rejected responses: the webhook itself was processed correctly.
        http_response_code(200);
        if ($result->isOk()) {
            echo "OK";
        } elseif ($result->isError()) {
            echo "Error: " . ($result->getMessage() ?? 'Unknown error');
        } else {
            echo "Pending / Waiting";
        }
    }

    /**
     * Enqueues a deferred flow for async execution via SuiteCRM job queue.
     * This ensures the webhook returns HTTP 200 immediately without waiting
     * for the flow (emails, PDFs, etc.) to complete.
     *
     * @param string $ticketId The deferred ticket ID
     * @param bool $isSuccess Whether to run the success or error flow
     */
    private function enqueueDeferredFlow(string $ticketId, bool $isSuccess): void
    {
        try {
            $job = BeanFactory::newBean('SchedulersJobs');
            $job->name = 'AWF Deferred Flow - Ticket ' . $ticketId;
            $job->target = 'sticAWFResumeDeferredFlow';
            $job->data = json_encode([
                'ticket_id' => $ticketId,
                'is_success' => $isSuccess,
            ]);
            $job->assigned_user_id = $GLOBALS['current_user']->id ?? '1';

            $queue = new SugarJobQueue();
            $jobId = $queue->submitJob($job);

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Enqueued deferred flow Job ID={$jobId} for ticket {$ticketId} (success=" . ($isSuccess ? 'true' : 'false') . ")");
        } catch (Exception $e) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to enqueue deferred flow for ticket {$ticketId}: " . $e->getMessage());
        }
    }

    /**
     * Creates an alert Task in the CRM when a deferred payment ticket permanently fails,
     * so an administrator can perform manual reconciliation.
     */
    private function createAdminAlert(stic_AWF_Deferred_Tickets $ticket, string $errorMessage): void
    {
        try {
            $task = BeanFactory::newBean('Tasks');
            $task->name = '[AWF] Deferred payment failed - Ticket ' . $ticket->id;
            $task->status = 'Not Started';
            $task->priority = 'High';
            $task->description = "A deferred payment ticket has permanently failed after maximum retries.\n\n"
                . "Ticket ID: {$ticket->id}\n"
                . "External Transaction ID: " . ($ticket->external_transaction_id ?? 'N/A') . "\n"
                . "Error: {$errorMessage}\n"
                . "Retry count: " . ($ticket->retry_count ?? 0) . "\n\n"
                . "The donor may have already paid at the gateway. Manual reconciliation is required.";
            $task->parent_type = 'stic_AWF_Deferred_Tickets';
            $task->parent_id = $ticket->id;
            $task->assigned_user_id = '1';
            $task->save();
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Created admin alert Task ID={$task->id} for failed ticket {$ticket->id}");
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to create admin alert task: " . $e->getMessage());
        }
    }

    /**
     * Atomically finds and locks the Deferred Ticket using an UPDATE...WHERE status='pending'.
     * This prevents race conditions when the same webhook arrives multiple times.
     */
    private function findTicket(string $identifier, string $searchField): ?stic_AWF_Deferred_Tickets
    {
        global $db;
        $safeId = $db->quote($identifier);
        // Note: The searchField is validated to be either 'token_hash' or 'external_transaction_id'.
        if (!in_array($searchField, ['token_hash', 'external_transaction_id'])) {
            return null;
        }

        $sql = "UPDATE stic_AWF_Deferred_Tickets 
                SET status = 'processing' 
                WHERE {$searchField} = '{$safeId}' 
                AND status = 'pending' 
                AND deleted = 0";
        $result = $db->query($sql);

        if ($db->getAffectedRowCount($result) === 0) {
            return null;
        }

        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->retrieve_by_string_fields([$searchField => $identifier, 'deleted' => '0']);

        return (!empty($ticket->id)) ? $ticket : null;
    }

}

$handler = new WebhookHandler();
$handler->run();
