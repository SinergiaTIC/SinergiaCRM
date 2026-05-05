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
require_once "modules/stic_AWF_Forms/actions/Deferred/PaymentRouterAction.php";

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
        $rawData = $_POST;
        $rawBody = file_get_contents('php://input');

        // --- 1. Create IncomingEvent log record ---
        $incomingEvent = BeanFactory::newBean('stic_AWF_Incoming_Events');
        $incomingEvent->name = 'AWF Webhook: ' . $source . ' - ' . date('Y-m-d H:i:s');
        $incomingEvent->token = $_REQUEST['token'] ?? null;
        $incomingEvent->source = $source;
        $incomingEvent->raw_payload = $rawBody ?: json_encode($rawData);
        $incomingEvent->status = 'new';
        $incomingEvent->date_received = date('Y-m-d H:i:s');
        $incomingEvent->save();

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Received webhook from source='{$source}'. IncomingEvent ID={$incomingEvent->id}");

        // --- 2. Extract external transaction ID (delegated to strategy) ---
        $externalId = stic_AWF_PaymentStrategyFactory::extractExternalIdBySource($source, $rawData, $rawBody);

        if (empty($externalId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Could not extract external transaction ID for source='{$source}'");
            $incomingEvent->status = 'ignored';
            $incomingEvent->last_error_message = "Could not extract external transaction ID";
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
            http_response_code(400);
            die("Cannot determine transaction ID");
        }

        $incomingEvent->external_transaction_id = $externalId;
        $incomingEvent->save();

        // --- 3. Atomically find and lock the Deferred Ticket ---
        $ticket = $this->findTicket($externalId);

        // --- 4. Build context and resolve ---
        $context = null;
        if ($ticket) {
            $result = $this->processWithTicket($ticket, $rawData, $rawBody, $incomingEvent, $context);
        } else {
            $result = $this->processWithoutTicket($source, $rawData, $rawBody, $incomingEvent);
        }

        // --- 5. Handle result ---
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
            $context = $this->rebuildContext($ticket);
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
        $customData = $context->getCustomData();
        $customData['_rawBody'] = $rawBody;
        $context->setCustomData($customData);

        $outContext = $context;

        $actionDefinition = new PaymentRouterAction();
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
        if ($result->isOk()) {
            if ($ticket) {
                $ticket->status = 'resolved';
                $ticket->save();
                if ($context !== null) {
                    try {
                        $this->resumeFlow($ticket, $context, true);
                    } catch (Exception $e) {
                        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to resume success flow: " . $e->getMessage());
                    }
                }
            }

            if ($incomingEvent->status !== 'processed') {
                $incomingEvent->status = 'processed';
                $incomingEvent->date_processed = date('Y-m-d H:i:s');
                $incomingEvent->save();
            }

            http_response_code(200);
            echo "OK";

        } elseif ($result->isWait()) {
            if ($ticket) {
                $ticket->status = 'pending';
                if (!empty($result->getData())) {
                    $ticket->context_data = json_encode($result->getData());
                }
                $ticket->save();
            }

            if ($incomingEvent->status !== 'processed') {
                $incomingEvent->status = 'processed';
                $incomingEvent->date_processed = date('Y-m-d H:i:s');
                $incomingEvent->save();
            }

            http_response_code(200);
            echo "OK, Updated";

        } else {
            if ($ticket) {
                $ticket->status = 'failed';
                $ticket->save();
                if ($context !== null) {
                    try {
                        $this->resumeFlow($ticket, $context, false);
                    } catch (Exception $e) {
                        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to resume error flow: " . $e->getMessage());
                    }
                }
            }

            if ($incomingEvent->status !== 'error') {
                $incomingEvent->status = 'processed';
                $incomingEvent->last_error_message = $result->message ?? 'Payment rejected';
                $incomingEvent->date_processed = date('Y-m-d H:i:s');
                $incomingEvent->save();
            }

            // Return 200 even for rejected payments — the webhook itself was processed correctly.
            http_response_code(200);
            echo "Processed with error: " . ($result->message ?? 'Unknown error');
        }
    }

    /**
     * Atomically finds and locks the Deferred Ticket using an UPDATE...WHERE status='pending'.
     * This prevents race conditions when the same webhook arrives multiple times.
     */
    private function findTicket(string $externalId): ?stic_AWF_Deferred_Tickets
    {
        global $db;
        $safeId = $db->quote($externalId);

        $sql = "UPDATE stic_AWF_Deferred_Tickets 
                SET status = 'processing' 
                WHERE external_transaction_id = '{$safeId}' 
                AND status = 'pending' 
                AND deleted = 0";
        $result = $db->query($sql);

        if ($db->getAffectedRowCount($result) === 0) {
            return null;
        }

        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->retrieve_by_string_fields(['external_transaction_id' => $externalId, 'deleted' => '0']);

        return (!empty($ticket->id)) ? $ticket : null;
    }

    /**
     * Rebuilds the ExecutionContext from data stored in the Deferred Ticket.
     */
    private function rebuildContext(stic_AWF_Deferred_Tickets $ticket): ExecutionContext
    {
        $responseBean = BeanFactory::getBean('stic_AWF_Responses', $ticket->stic_awf_responses_id_c);
        if (empty($responseBean) || empty($responseBean->id)) {
            throw new Exception("Response not found for ticket ID={$ticket->id}, response_id={$ticket->stic_awf_responses_id_c}");
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
            global $db;
            $safeResponseId = $db->quote($responseBean->id);
            $result = $db->query("SELECT stic_awf_forms_stic_awf_responsesforms_ida AS form_id
                                  FROM stic_awf_forms_stic_awf_responses_c
                                  WHERE stic_awf_forms_stic_awf_responsesresponses_idb = '{$safeResponseId}'
                                  AND deleted = 0 LIMIT 1");
            $row = $db->fetchByAssoc($result);
            $formId = $row['form_id'] ?? null;
        }

        if (empty($formId)) {
            throw new Exception("Cannot determine form ID for response={$responseBean->id}");
        }

        $formBean = BeanFactory::getBean('stic_AWF_Forms', $formId);
        if (empty($formBean) || empty($formBean->id)) {
            throw new Exception("Form not found. ID={$formId}");
        }

        $jsonConfig = html_entity_decode($formBean->configuration ?? '', ENT_QUOTES, 'UTF-8');
        $configData = json_decode($jsonConfig, true);
        if (!$configData) {
            throw new Exception("Invalid form configuration for form ID={$formId}");
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

        return $context;
    }

    /**
     * Executes the success or error deferred flow using the flow IDs stored in the ticket's context_data.
     */
    private function resumeFlow(stic_AWF_Deferred_Tickets $ticket, ExecutionContext $context, bool $isSuccess): void
    {
        $contextData = json_decode($ticket->context_data, true) ?: [];

        $successFlowId = $contextData['flow_success_id'] ?? null;
        $errorFlowId   = $contextData['flow_error_id']   ?? null;

        $successFlow = ($successFlowId !== null && $successFlowId !== '')
            ? ($context->formConfig->flows[$successFlowId] ?? null)
            : null;
        $errorFlow = ($errorFlowId !== null && $errorFlowId !== '')
            ? ($context->formConfig->flows[$errorFlowId] ?? null)
            : null;

        if ($isSuccess) {
            if ($successFlow === null) {
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: No success flow (flow_success_id={$successFlowId}) for ticket {$ticket->id}");
                return;
            }
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Executing success flow ID={$successFlowId} for ticket {$ticket->id}");
            $executor = new ServerActionFlowExecutor($context);
            $executor->executeFlow($successFlow, $errorFlow);

            if ($context->responseBean) {
                $context->responseBean->status = 'processed';
                $context->responseBean->save();
            }
        } else {
            if ($errorFlow === null) {
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: No error flow (flow_error_id={$errorFlowId}) for ticket {$ticket->id}");
                return;
            }
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Executing error flow ID={$errorFlowId} for ticket {$ticket->id}");
            $executor = new ServerActionFlowExecutor($context);
            $executor->executeFlow($errorFlow);

            if ($context->responseBean) {
                $context->responseBean->status = 'error';
                $context->responseBean->save();
            }
        }
    }
}

$handler = new WebhookHandler();
$handler->run();
