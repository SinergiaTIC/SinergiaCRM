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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/modules/stic_Signatures/SignatureSignersManager.php';

/**
 * SignatureAction
 * Deferred action that adds the user as a signer to a signature process
 * and redirects to the signature portal.
 * Follows the same pattern as EmailConfirmationAction (PR #1143).
 */
class SignatureAction extends DeferredBeanActionDefinition implements ITerminalAction
{
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'integration';
        $this->baseLabel = 'LBL_SIGNATURE_ACTION';
        $this->defaultExpirationDays = '7';
    }

    /**
     * Declares who will resume this deferred process and how.
     */
    public function getResumptionContext(): DeferredResumptionContext
    {
        return DeferredResumptionContext::ORIGINAL_USER;
    }

    /**
     * Modules supported by the action
     */
    protected function getSupportedModules(): array {
        return ['Contacts', 'Users'];
    }

    /**
     * Name of the parameter that contains the data block.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('RECIPIENT_BLOCK_TEXT');
    }

    /**
     * The description (help text) of the data block parameter.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('RECIPIENT_BLOCK_DESC');
    }

    /**
     * Definition of the ADDITIONAL parameters needed for the deferred action
     */
    protected function getDeferredCustomParameters(): array
    {
        $paramSignature = new ActionParameterDefinition();
        $paramSignature->name = 'signature_process';
        $paramSignature->text = $this->translate('SIGNATURE_PROCESS_TEXT');
        $paramSignature->description = $this->translate('SIGNATURE_PROCESS_DESC');
        $paramSignature->type = ActionParameterType::CRM_RECORD;
        $paramSignature->supportedModules = ['stic_Signatures'];
        $paramSignature->required = true;

        return [$paramSignature];
    }

    /**
     * Executes the action, receives the loaded bean and the main data block with the form data
     *
     * @param ExecutionContext $context The global context.
     * @param FormAction $actionConfig The configuration of the action.
     * @param SugarBean $bean The bean loaded from the DB (saved data).
     * @param DataBlockResolved $block The data block (form data).
     * @return ActionResult
     */
    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        /** @var BeanReference $signatureRef */
        $signatureRef = $actionConfig->getResolvedParameter('signature_process');
        if (empty($signatureRef)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Signature process parameter is missing.");
        }

        // Get signer data from Bean
        $emailAddress = $bean->email1 ?? null;
        if (empty($emailAddress)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Bean '{$bean->name}' does not have an email address.");
        }

        // Create a deferred ticket
        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->id = create_guid(); // Set Id for the ContextData
        $ticket->new_with_id = true;
        $ticket->name = 'Signature: ' . $bean->name . ' - ' . date('Y-m-d H:i:s');
        $ticket->stic_awf_responses_id_c = $context->responseId;
        $ticket->token_hash = bin2hex(random_bytes(32));
        $ticket->status = 'pending';
        $ticket->handler_action_id = $actionConfig->id;

        // Set the expiration date
        $days = (int)$actionConfig->getResolvedParameter('expiration_days', 7);
        $ticket->expiration_date = date('Y-m-d H:i:s', strtotime("+{$days} days"));

        // Set the context data for the deferred flow
        $contextData = DeferredContextData::createSnapshot(
            self::class, $ticket, $actionConfig, $bean, $context,
            [
                'signature_id' => $signatureRef->beanId,
                'target_id' => $bean->id,
                'target_module' => $bean->module_name,
                'email' => $emailAddress,
            ]
        );
        $ticket->context_data = $contextData->toJson();

        // Save the ticket
        $ticket->save();

        // Add the user as a signer to the signature process
        $currentUserId = $context->currentUserId ?? $bean->assigned_user_id ?? '1';
        $signerResult = SignatureSignersManager::addSignersToSignature(
            $signatureRef->beanId,
            $bean->module_name,
            [$bean->id],
            $currentUserId
        );

        if (!$signerResult['success'] || $signerResult['ok'] === 0) {
            $errorMsg = !empty($signerResult['errors']) ? implode(', ', $signerResult['errors']) : 'Unknown error adding signer';
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Failed to add signer to signature {$signatureRef->beanId}: {$errorMsg}");
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Failed to add signer: {$errorMsg}");
        }

        // Generate the redirect URL to the signature portal
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'] ?? '', '/');
        $redirectUrl = $siteUrl . '/index.php?entryPoint=sticSign'
            . '&signatureId=' . urlencode($signatureRef->beanId)
            . '&targetId=' . urlencode($bean->id)
            . '&returnToken=' . urlencode($ticket->token_hash);

        // Return a WAIT result to halt the flow and redirect to signature portal
        return new ActionResult(ResultStatus::WAIT, $actionConfig, "Redirecting to signature portal")
            ->setData(['redirect_url' => $redirectUrl]);
    }

    /**
     * Redirects the user to the signature portal.
     * Only called if the action returned WAIT.
     *
     * @param ExecutionContext $context Execution context of the action
     * @param ActionResult $executionResult Result of the execution of the action
     */
    public function performTerminal(ExecutionContext $context, ActionResult $executionResult): void
    {
        if (!$executionResult->isWait()) {
            return;
        }

        $data = $executionResult->getData();
        $url = $data['redirect_url'] ?? '';

        if (!empty($url)) {
            header("Location: " . $url);
            exit;
        }
    }

    /**
     * Processes an incoming request (webhook) from an external service.
     * Not applicable for SignatureAction - the flow resumes via the signature portal redirect.
     *
     * @param ExecutionContext $context The global context.
     * @param array $requestData The data of the incoming request.
     * @return ActionResult Result of the execution of the action.
     */
    public function processWebhook(ExecutionContext $context, array $requestData): ActionResult
    {
        return new ActionResult(ResultStatus::ERROR, null, "SignatureAction does not support webhooks.");
    }
}
