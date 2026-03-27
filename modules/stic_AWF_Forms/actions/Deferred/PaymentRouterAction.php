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

require_once __DIR__.'/payment/stic_AWF_PaymentStrategyFactory.php';
require_once __DIR__.'/payment/stic_AWF_PaymentStrategy.php';
require_once 'modules/stic_Payment_Commitments/stic_Payment_Commitments.php';

class PaymentRouterAction extends DeferredBeanActionDefinition 
{
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->category = 'integration';
        $this->baseLabel = 'LBL_PAYMENT_ROUTER_ACTION';
    }

    /**
     * Modules supported by the action
     */
    protected function getSupportedModules(): array {
        return ['stic_Payment_Commitments'];
    }
    
    /**
     * Name of the parameter that contains the data block.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('PAYMENT_COMMITMENT_TEXT');
    }

    /**
     * The description (help text) of the data block parameter.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('PAYMENT_COMMITMENT_DESC');
    }

    /**
     * getCustomParameters()
     * Definition of the ADDITIONAL parameters required for the action
     * The main Data Block parameters are requested by the parent class.
     */
    protected function getCustomParameters(): array
    {
        return [];
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
        // $bean is a stic_Payment_Commitments registry
        /** @var stic_Payment_Commitments $paymentCommitmentBean */
        $paymentCommitmentBean = $bean;
     
        // Basic validations
        // Amount 
        if (!is_numeric($paymentCommitmentBean->amount) || $paymentCommitmentBean->amount <= 0) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Invalid amount in Payment Commitment (ID: {$paymentCommitmentBean->id}). Amount: {$paymentCommitmentBean->amount}");
        }
        // Payment method
        if (empty($paymentCommitmentBean->payment_method)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Payment method is empty in Payment Commitment (ID: {$paymentCommitmentBean->id})");
        }
        // Active
        if (!$paymentCommitmentBean->active) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Inactive Payment Commitment (ID: {$paymentCommitmentBean->id})");
        }

        // Get Payment Strategy
        try {
            /** @var stic_AWF_PaymentStrategy $strategy */
            $strategy = PaymentStrategyFactory::createFromMethodValue($paymentCommitmentBean->payment_method);
        } catch (Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error getting Payment Strategy for Payment Commitment (ID: {$paymentCommitmentBean->id}): " . $e->getMessage());
        }

        // Get the first Payment from PaymentCommitment

        // Reload the payment commitment bean in order to properly load relationships
        $paymentCommitmentBean->retrieve($paymentCommitmentBean->id);

        // Get the generated payment
        $paymentCommitmentBean->load_relationship('stic_payments_stic_payment_commitments');
        $payments = $paymentCommitmentBean->stic_payments_stic_payment_commitments->getBeans();

        if (empty($payments) || count($payments) < 1) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": An error occurred while trying to get payments from Payment Commitment (ID: {$paymentCommitmentBean->id})");
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error getting Payments from Payment Commitment (ID: {$paymentCommitmentBean->id})");
        } 

        $paymentBean = reset($payments); // Get the first element in the array

        // IEPA!!
        // if ($fp->payment_method == 'card' || $fp->payment_method == 'paypal' || $fp->payment_method == 'bizum') {
        //     // POS/Paypal payments must be set as pending
        //     $payment->status = 'pending';
        //     $payment->save(); // Save the changes
        // }

        // Reload the object since otherwise will not have reported the id (mysteries of sugar)
        $paymentBean = $paymentBean->retrieve($paymentBean->id);

        // Execute Strategy initiation
        $strategyResult = $strategy->initiate($context, $actionConfig, $paymentBean);

        // If the strategy returns OK immediately (e.g. Offline payment), execute the
        // Deferred OK flow right away for symmetry so confirmation emails etc. are sent.
        if ($strategyResult->isOk()) {
            $this->executeDeferredOkFlow($context, $actionConfig);
        }

        return $strategyResult;
    }

    /**
     * Executes the success flow configured on the action (used when a payment resolves OK immediately).
     * Falls back to the error flow if the success flow fails.
     *
     * @param ExecutionContext $context Execution context
     * @param FormAction $actionConfig Action configuration containing flow_success_id / flow_error_id
     */
    private function executeDeferredOkFlow(ExecutionContext $context, FormAction $actionConfig): void
    {
        $successFlowId = $actionConfig->flow_success_id ?? null;
        $errorFlowId   = $actionConfig->flow_error_id   ?? null;

        $successFlow = ($successFlowId !== null && $successFlowId !== '')
            ? ($context->formConfig->flows[$successFlowId] ?? null)
            : null;
        $errorFlow = ($errorFlowId !== null && $errorFlowId !== '')
            ? ($context->formConfig->flows[$errorFlowId] ?? null)
            : null;

        if ($successFlow === null) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": PaymentRouterAction: No success flow configured (flow_success_id={$successFlowId}). Skipping deferred OK flow.");
            return;
        }

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": PaymentRouterAction: Executing Deferred OK flow (ID={$successFlowId}).");

        $executor = new ServerActionFlowExecutor($context);
        $executor->executeFlow($successFlow, $errorFlow);
    }

    /**
     * Called only if execute() was successful.
     * This is where the 'exit', 'header' or HTML is rendered, losing control of execution.
     * 
     * @param ExecutionContext $context Execution context of the action
     * @param ActionResult Result of the execution of the action (last ActionResult)
     */
    public function performTerminal(ExecutionContext $context, ActionResult $executionResult): void
    {
        // If the action is not in Wait state: do not redirect
        if (!$executionResult->isWait()) return;

        // Recover using the Factory
        try {
            $strategy = PaymentStrategyFactory::createFromStoredData($executionResult->getData());
            $strategy->performTerminal($context, $executionResult);
        } catch (Exception $e) {
            $GLOBALS['log']->fatal("PaymentRouter: " . $e->getMessage());
        }
    }
    
    /**
     * Processes an incoming request (webhook) from an external service.
     * 
     * This method is only relevant for actions that expect a server callback.
     * @param ExecutionContext $context The global context.
     * @param array $requestData The data of the incoming request.
     * @return ActionResult Result of the execution of the action.
     */
    public function processWebhook(ExecutionContext $context, array $requestData): ActionResult
    {
        // Recover data from Ticket (from context)
        // TODO: Afegir i recuperar dades del context!!
        $savedData = $context->getCustomData() ?? []; 
        
        try {
            $strategy = PaymentStrategyFactory::createFromStoredData($savedData);
            return $strategy->resolve($context, $requestData);
        } catch (Exception $e) {
            return new ActionResult(ResultStatus::ERROR, null, "Error processing webhook response: " . $e->getMessage());
        }
    }

}