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

include_once "modules/stic_AWF_Forms/actions/coreActions.php";
require_once "modules/stic_Payment_Commitments/stic_Payment_Commitments.php";
require_once "modules/stic_Payments/stic_Payments.php";
require_once "modules/stic_AWF_Deferred_Tickets/stic_AWF_Deferred_Tickets.php";

abstract class stic_AWF_PaymentStrategy
{
    protected ?string $suffix = null;

    protected string $configType = ''; // 'TPV', 'STRIPE'...
    protected string $configKeyPrefix = ''; // 'TPV', 'TPVCECA', 'STRIPE'...

    /** @var ?stic_AWF_Deferred_Tickets Ticket created in initiate(), used by getReturnUrl() */
    protected ?stic_AWF_Deferred_Tickets $ticket = null;

    protected ?array $settings = null; // Cache with loaded configurations from DB

    /**
     * Configure suffix to load alternative constants
     * Ex. Football...
     */
    public function setSuffix(string $suffix): void {
        $this->suffix = $suffix;
    }

    /**
    * Loads the configurations and resolves the values.
    * @param array $keys List of keys without prefix (ex: ['MERCHANT_CODE'])
    * @return array
    */
    protected function getConfigValues(array $keys): array {
        require_once "modules/stic_Settings/Utils.php";

        // Lazy load all the configuration of this type
        if ($this->settings === null) {
            $this->settings = stic_SettingsUtils::getSettingsByType($this->configType);
            if (!is_array($this->settings)) {
                $this->settings = array();
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load settings of type ". $this->configType);
                return [];
            }
        }

        $resolvedValues = array();
        $prefix = $this->configKeyPrefix; // Ex: 'TPV'

        foreach ($keys as $key) {
            // General Key: {PREFIX}_{KEY}  => TPV_MERCHANT_CODE
            $defaultConfigKey = $prefix . '_' . $key;
            // Initial value
            $value = isset($this->settings[$defaultConfigKey]) ? $this->settings[$defaultConfigKey] : null;

            // Key with Suffix: {PREFIX}_ALT_{SUFFIX}_{KEY} => TPV_ALT_FOOTBALL_MERCHANT_CODE
            if ($this->suffix) {
                $altConfigKey = $prefix . '_ALT_' . $this->suffix . '_' . $key;
                if (isset($this->settings[$altConfigKey]) && $this->settings[$altConfigKey] !== '') {
                    $value = $this->settings[$altConfigKey];
                }
            }
            $resolvedValues[$key] = $value;
        }

        return $resolvedValues;
    }

    /**
     * Creates a Deferred Ticket record to track this payment.
     * Stores strategy_class, strategy_suffix, payment_id, flow_success_id and flow_error_id
     * in context_data so the webhook can reconstruct the context.
     *
     * @param ExecutionContext $context The execution context
     * @param FormAction $actionConfig The action configuration
     * @param stic_Payments $beanPayment The payment bean
     * @param string $externalTransactionId The external transaction ID from the gateway
     * @return stic_AWF_Deferred_Tickets The created ticket
     */
    protected function createTicket(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment, string $externalTransactionId): stic_AWF_Deferred_Tickets
    {
        /** @var stic_AWF_Deferred_Tickets $ticket */
        $ticket = BeanFactory::newBean('stic_AWF_Deferred_Tickets');
        $ticket->name = 'AWF Payment: ' . $beanPayment->id . ' - ' . date('Y-m-d H:i:s');
        $ticket->stic_awf_responses_id_c = $context->responseId;
        $ticket->token_hash = bin2hex(random_bytes(32));
        $ticket->external_transaction_id = $externalTransactionId;
        $ticket->status = 'pending';
        $ticket->handler_action_id = $actionConfig->id;
        $ticket->expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));

        $contextData = [
            'strategy_class'   => static::class,
            'strategy_suffix'  => $this->suffix,
            'payment_id'       => $beanPayment->id,
            'form_id'          => $context->formId,
            'flow_success_id'  => $actionConfig->flow_success_id,
            'flow_error_id'    => $actionConfig->flow_error_id,
        ];
        $ticket->context_data = json_encode($contextData);
        $ticket->save();

        $this->ticket = $ticket;

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF PaymentStrategy: Created Deferred Ticket ID={$ticket->id} for payment {$beanPayment->id}");

        return $ticket;
    }

    /**
     * Updates the payment status (and optionally a metadata field) and saves the bean.
     *
     * @param stic_Payments $beanPayment The payment bean
     * @param string $status The new status value
     * @param mixed $authCode Optional authorization code / external reference to store in description
     */
    protected function updatePayment(stic_Payments $beanPayment, string $status, $authCode = null): void
    {
        $beanPayment->status = $status;
        if ($authCode !== null) {
            // stic_Payments does not have a dedicated authorization_code field,
            // so we store it in banking_concept as an external reference.
            $beanPayment->banking_concept = (string)$authCode;
        }
        $beanPayment->save();
    }

    /**
     * Returns the URL to redirect the user after the gateway processes the payment.
     * Requires $this->ticket to be set (call createTicket first).
     *
     * @param string $status The status to append ('success', 'error', 'pending')
     * @return string The full return URL
     */
    protected function getReturnUrl(string $status): string
    {
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'] ?? '', '/');
        $token = $this->ticket ? $this->ticket->token_hash : '';
        return $siteUrl . '/index.php?entryPoint=stic_AWF_ReturnHandler&token=' . urlencode($token) . '&status=' . urlencode($status);
    }

    /**
     * Returns the webhook callback URL for a given payment source.
     * NOTE: The key 'stic_AWF_webhookHanlder' intentionally preserves the existing typo
     * for backward compatibility with the registered entry point.
     *
     * @param string $source The payment source identifier (e.g. 'redsys', 'stripe')
     * @return string The full callback URL
     */
    protected function getCallbackUrl(string $source): string
    {
        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'] ?? '', '/');
        return $siteUrl . '/index.php?entryPoint=stic_AWF_webhookHanlder&source=' . urlencode($source);
    }

    /**
     * Renders an HTML template by substituting {VAR_NAME} placeholders.
     * Looks first in modules/stic_AWF_Forms/tpls/, then falls back to
     * modules/stic_Web_Forms/Catcher/Include/Payment/tpls/.
     *
     * @param string $templateName Template file name without extension (e.g. 'TPVFirstStep')
     * @param array $vars Associative array of placeholder => value substitutions
     * @return string The rendered HTML string
     */
    protected function renderTemplate(string $templateName, array $vars): string
    {
        $awfPath = "modules/stic_AWF_Forms/tpls/{$templateName}.html";
        $wfPath  = "modules/stic_Web_Forms/Catcher/Include/Payment/tpls/{$templateName}.html";

        $templateFile = null;
        if (file_exists($awfPath)) {
            $templateFile = $awfPath;
        } elseif (file_exists($wfPath)) {
            $templateFile = $wfPath;
        }

        if ($templateFile === null) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": AWF PaymentStrategy: Template not found: {$templateName}");
            return '';
        }

        $html = file_get_contents($templateFile);
        foreach ($vars as $key => $value) {
            $html = str_replace('{' . $key . '}', (string)$value, $html);
        }
        return $html;
    }

    /**
    * Prepare payment.
    * If Offline -> Returns OK.
    * If External platform -> Returns WAIT with data to redirection.
    */
    abstract public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult;

    /**
    * Terminal: Execute the output (HTML form, Redirect header...).
    * Only called if initiate() has returned WAIT.
    */
    abstract public function performTerminal(ExecutionContext $context, ActionResult $result): void;

    /**
    * WEBHOOK: Resolves action when notification arrives from external event.
    */ 
    abstract public function resolve(ExecutionContext $context, ActionResult $result): ActionResult;
}