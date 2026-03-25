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
 * Receives and processes webhook responses from payment gateways (Redsys, Stripe, PayPal, CECA).
 *
 * Flow:
 *   1. Determine the payment source from the request
 *   2. Create an IncomingEvent log record (status=new)
 *   3. Extract the external transaction ID
 *   4. Atomically find and lock the Deferred Ticket (status pending→processing)
 *   5. Rebuild the ExecutionContext from the ticket
 *   6. Call PaymentRouterAction::processWebhook()
 *   7. Update the ticket status and resume the form flow
 *   8. Update the IncomingEvent log record (status=processed/error/ignored)
 */
class WebhookHandler
{
    public function run(): void
    {
        global $current_user;
        // Run as system user so permission checks do not interfere with processing
        $current_user = BeanFactory::newBean('Users');
        $current_user->getSystemUser();

        $source  = $_REQUEST['source'] ?? '';
        $rawData = $_POST;

        // For Stripe and some providers the body arrives as raw JSON
        $rawBody = file_get_contents('php://input');

        // --- 1. Create IncomingEvent log record ---
        $incomingEvent = BeanFactory::newBean('stic_AWF_Incoming_Events');
        $incomingEvent->name = 'AWF Webhook: ' . $source . ' - ' . date('Y-m-d H:i:s');
        $incomingEvent->token = $source;
        $incomingEvent->raw_payload = $rawBody ?: json_encode($rawData);
        $incomingEvent->status = 'new';
        $incomingEvent->date_received = date('Y-m-d H:i:s');
        $incomingEvent->save();

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Received webhook from source='{$source}'. IncomingEvent ID={$incomingEvent->id}");

        // --- 2. Extract the external transaction ID ---
        $externalId = $this->extractExternalId($source, $rawData, $rawBody);

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

        if (!$ticket) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Ticket not found or already processed for externalId='{$externalId}'");
            $incomingEvent->status = 'ignored';
            $incomingEvent->last_error_message = "Ticket not found or already processed";
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();
            http_response_code(200);
            die("Already processed");
        }

        // --- 4. Rebuild ExecutionContext ---
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

        // --- 5. Instantiate PaymentRouterAction and call processWebhook ---
        $actionDefinition = new PaymentRouterAction();
        $result = $actionDefinition->processWebhook($context, $rawData);

        // --- 6. Handle result ---
        if ($result->isOk()) {
            $ticket->status = 'resolved';
            $ticket->save();

            $this->resumeFlow($ticket, $context, true);

            $incomingEvent->status = 'processed';
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();

            http_response_code(200);
            echo "OK";

        } elseif ($result->isWait()) {
            // Intermediate state (e.g. Stripe async_payment_pending)
            // Keep ticket as pending, update context_data if strategy provided new data
            $ticket->status = 'pending';
            if (!empty($result->getData())) {
                $ticket->context_data = json_encode($result->getData());
            }
            $ticket->save();

            $incomingEvent->status = 'processed';
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();

            http_response_code(200);
            echo "OK, Updated";

        } else {
            $ticket->status = 'failed';
            $ticket->save();

            $this->resumeFlow($ticket, $context, false);

            $incomingEvent->status = 'error';
            $incomingEvent->last_error_message = $result->message ?? 'Unknown error';
            $incomingEvent->date_processed = date('Y-m-d H:i:s');
            $incomingEvent->save();

            http_response_code(400);
            echo "Error: " . ($result->message ?? 'Unknown error');
        }
    }

    /**
     * Extracts the external transaction ID from the raw webhook data.
     * Each payment gateway sends it in a different location.
     *
     * @param string $source Payment gateway source identifier
     * @param array $rawData POST data array
     * @param string $rawBody Raw request body (for JSON-based gateways like Stripe)
     * @return string|null The external transaction ID or null if not found
     */
    private function extractExternalId(string $source, array $rawData, string $rawBody = ''): ?string
    {
        switch ($source) {
            case 'redsys':
            case 'bizum':
                // Redsys sends a base64-encoded JSON in Ds_MerchantParameters
                $params = $rawData['Ds_MerchantParameters'] ?? '';
                if (empty($params)) return null;
                $decoded = json_decode(base64_decode(strtr($params, '-_', '+/')), true);
                return $decoded['Ds_Order'] ?? null;

            case 'stripe':
                // Stripe sends a JSON body with the event object
                $payload = json_decode($rawBody, true);
                return $payload['data']['object']['id'] ?? null;

            case 'paypal':
                // PayPal IPN sends transaction data as POST; custom field holds our reference
                return $rawData['custom'] ?? null;

            case 'ceca':
                return $rawData['Num_operacion'] ?? null;

            default:
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF WebhookHandler: Unknown source '{$source}'");
                return null;
        }
    }

    /**
     * Atomically finds and locks the Deferred Ticket using an UPDATE...WHERE status='pending'.
     * This prevents race conditions when the same webhook arrives multiple times.
     *
     * @param string $externalId The external transaction ID
     * @return stic_AWF_Deferred_Tickets|null The ticket bean or null if not found/already processed
     */
    private function findTicket(string $externalId): ?stic_AWF_Deferred_Tickets
    {
        global $db;
        $safeId = $db->quote($externalId);

        // Atomic update: only succeeds if the ticket is still in 'pending' state
        $sql = "UPDATE stic_AWF_Deferred_Tickets 
                SET status = 'processing' 
                WHERE external_transaction_id = '{$safeId}' 
                AND status = 'pending' 
                AND deleted = 0";
        $db->query($sql);

        if ($db->getAffectedRowCount() === 0) {
            // Either already processed or not found
            return null;
        }

        /** @var stic_AWF_Deferred_Tickets $ticket */
        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->retrieve_by_string_fields(['external_transaction_id' => $externalId, 'deleted' => '0']);

        return (!empty($ticket->id)) ? $ticket : null;
    }

    /**
     * Rebuilds the ExecutionContext from data stored in the Deferred Ticket.
     *
     * @param stic_AWF_Deferred_Tickets $ticket The deferred ticket bean
     * @return ExecutionContext The rebuilt execution context
     * @throws Exception If the response or form cannot be loaded
     */
    private function rebuildContext(stic_AWF_Deferred_Tickets $ticket): ExecutionContext
    {
        // Load the Response bean
        $responseBean = BeanFactory::getBean('stic_AWF_Responses', $ticket->stic_awf_responses_id_c);
        if (empty($responseBean) || empty($responseBean->id)) {
            throw new Exception("Response not found for ticket ID={$ticket->id}, response_id={$ticket->stic_awf_responses_id_c}");
        }

        // Load the Form bean from the response-form relationship
        // The form ID is stored via the relationship table; retrieve via relationship
        $responseBean->load_relationship('stic_69c1s_responses');
        $formId = null;
        if (!empty($responseBean->stic_69c1s_responses)) {
            // Try getting the form ID from the relationship
            $relatedForms = $responseBean->stic_69c1s_responses->getBeans();
            if (!empty($relatedForms)) {
                $formBeanRel = reset($relatedForms);
                $formId = $formBeanRel->id;
            }
        }

        // Fallback: query the join table directly
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

        // Parse form configuration
        $jsonConfig = html_entity_decode($formBean->configuration, ENT_QUOTES, 'UTF-8');
        $configData = json_decode($jsonConfig, true);
        if (!$configData) {
            throw new Exception("Invalid form configuration for form ID={$formId}");
        }
        $formConfig = FormConfig::fromJsonArray($configData);

        // Parse form data from the raw payload stored in the response
        $formData = json_decode($responseBean->raw_payload, true) ?: [];

        // Build the context
        $context = new ExecutionContext(
            $formBean->id,
            $responseBean->id,
            $formData,
            $formConfig,
            null,
            $responseBean->assigned_user_id,
            $responseBean
        );

        // Inject ticket's context_data so PaymentRouterAction::processWebhook() can read strategy_class etc.
        $contextData = json_decode($ticket->context_data, true) ?: [];
        $context->setCustomData($contextData);

        return $context;
    }

    /**
     * Executes the success or error deferred flow using the flow IDs stored in the ticket's context_data.
     *
     * @param stic_AWF_Deferred_Tickets $ticket The resolved/failed ticket
     * @param ExecutionContext $context The rebuilt execution context
     * @param bool $isSuccess True if payment was successful, false if failed
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

            // Update response status
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

            // Update response status
            if ($context->responseBean) {
                $context->responseBean->status = 'error';
                $context->responseBean->save();
            }
        }
    }
}

// Handler execution
$handler = new WebhookHandler();
$handler->run();
