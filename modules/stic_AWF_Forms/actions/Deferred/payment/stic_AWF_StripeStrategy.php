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

include_once __DIR__."/stic_AWF_PaymentStrategy.php";

class stic_AWF_StripeStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'STRIPE';
    protected string $configKeyPrefix = 'STRIPE';

    public static function getSourceName(): string
    {
        return 'stripe';
    }

    public static function extractExternalId(array $rawData, string $rawBody): ?string
    {
        $payload = json_decode($rawBody, true);
        return $payload['data']['object']['id'] ?? null;
    }

    /**
     * Prepare payment via Stripe Checkout.
     * Returns WAIT with redirect URL to Stripe.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('SECRET_KEY', 'SECRET_KEY_TEST', 'WEBHOOK_SECRET', 'WEBHOOK_SECRET_TEST', 'TEST'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $secretKey = $isTest ? ($config['SECRET_KEY_TEST'] ?? '') : ($config['SECRET_KEY'] ?? '');
        
        if (empty($secretKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, 'Stripe API key not configured');
        }

        require_once 'SticInclude/vendor/stripe/stripe-php/init.php';
        
        \Stripe\Stripe::setApiKey($secretKey);
        
        $transactionCode = $this->generateTransactionCode($beanPayment);
        
        $successUrl = $this->getReturnUrl('success') . '&session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = $this->getReturnUrl('cancel');

        $isRecurring = !empty($actionConfig->data['recurring']) && $actionConfig->data['recurring'] != 'punctual';
        $sessionParams = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => intval($beanPayment->amount * 100),
                    'product_data' => [
                        'name' => 'Payment - ' . $transactionCode,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => $isRecurring ? 'subscription' : 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'payment_id' => $beanPayment->id,
                'transaction_code' => $transactionCode,
                'form_id' => $context->formId,
                'response_id' => $context->responseId,
            ],
        ];
        
        try {
            $session = \Stripe\Checkout\Session::create($sessionParams);
            
            $this->createTicket($context, $actionConfig, $beanPayment, $session->id);
            
            return new ActionResult(ResultStatus::WAIT, $actionConfig, '', [
                'strategy_class' => static::class,
                'strategy_suffix' => $this->suffix,
                'ticket_id' => $this->ticket->id ?? '',
                'payment_id' => $beanPayment->id,
                'redirect_url' => $session->url,
                'session_id' => $session->id,
            ]);
            
        } catch (\Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Stripe error: " . $e->getMessage());
            return new ActionResult(ResultStatus::ERROR, $actionConfig, 'Stripe payment error: ' . $e->getMessage());
        }
    }

    /**
     * Terminal: Redirect to Stripe Checkout.
     * Only called if initiate() has returned WAIT.
     */
    public function performTerminal(ExecutionContext $context, ActionResult $result): void
    {
        $data = $result->getData();
        if (!empty($data['redirect_url'])) {
            header('Location: ' . $data['redirect_url']);
            sugarDie();
        }
    }

    /**
     * WEBHOOK: Resolves action when notification arrives from Stripe.
     * Matches stic_Web_Forms PaymentController::actionStripeResponse() behavior:
     * iterates all Stripe key pairs to find matching webhook signature.
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        require_once 'SticInclude/vendor/stripe/stripe-php/init.php';

        $stripeSettings = $this->getStripeSettings();
        if ($stripeSettings == null) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Unable to continue because Stripe settings can't be retrieved.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Stripe settings unavailable');
        }

        $customData = $context->getCustomData();
        $payload = $customData['_rawBody'] ?? @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $event = null;
        foreach ($stripeSettings as $settingKey => $settings) {
            $apiKey = $settings["STRIPE_SECRET_KEY"];
            $webhookSecret = $settings["STRIPE_WEBHOOK_SECRET"];
            
            \Stripe\Stripe::setApiKey($apiKey);
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
                break;
            } catch (\UnexpectedValueException $e) {
                return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid Stripe payload');
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature verification failed with ApiKey:{$apiKey}. Trying next key pair...");
                continue;
            }
        }

        if ($event === null) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Stripe webhook signature could not be verified with any key pair.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Webhook signature verification failed');
        }

        return $this->processStripeEvent($event, $result);
    }

    /**
     * Process the Stripe event, routing to the appropriate handler.
     * Matches PaymentBO::processStripeEvent() behavior.
     */
    private function processStripeEvent($event, ActionResult $result): ActionResult
    {
        switch ($event->type) {
            case 'checkout.session.completed':
            case 'checkout.session.async_payment_succeeded':
                return $this->processStripeCheckoutCompleted($event->data->object, $result);

            case 'checkout.session.async_payment_failed':
                return $this->processStripeCheckoutFailed($event->data->object, $result);

            case 'checkout.session.expired':
                return $this->processStripeCheckoutExpired($event->data->object, $result);

            case 'customer.subscription.deleted':
                return $this->processStripeSubscriptionDeleted($event->data->object, $result);

            case 'invoice.payment_succeeded':
                return $this->processStripeInvoiceSucceeded($event->data->object, $result);

            default:
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Not a Stripe response to manage... {$event->type}");
                return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Unhandled event acknowledged');
        }
    }

    /**
     * Process checkout.session.completed / async_payment_succeeded.
     * Matches PaymentBO::processStripeCheckout() for successful cases.
     */
    private function processStripeCheckoutCompleted($session, ActionResult $result): ActionResult
    {
        $paymentBean = $this->loadPaymentBeanFromStripeSession($session);
        if (!$paymentBean) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load payment from Stripe session.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found for Stripe session');
        }

        $this->updatePayment($paymentBean, 'paid', [
            'authCode' => $session->payment_intent ?? '',
            'amount' => $session->amount_total / 100,
            'gatewayLog' => print_r($session, true),
        ]);

        if ($session->subscription != null) {
            $PCBean = self::getPaymentCommitment($paymentBean);
            if ($PCBean) {
                $PCBean->stripe_subscr_id = $session->subscription;
                $PCBean->save(false);
            }
        }

        return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment completed');
    }

    /**
     * Process checkout.session.async_payment_failed.
     * Matches PaymentBO::processStripeCheckout() for async failed case.
     */
    private function processStripeCheckoutFailed($session, ActionResult $result): ActionResult
    {
        $paymentBean = $this->loadPaymentBeanFromStripeSession($session);
        if (!$paymentBean) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load payment from Stripe session (async payment failed).");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found');
        }

        $this->updatePayment($paymentBean, 'pending', [
            'gatewayLog' => print_r($session, true),
        ]);

        return new ActionResult(ResultStatus::WAIT, $result->actionConfig, 'Async payment failed, kept pending');
    }

    /**
     * Process checkout.session.expired.
     * Matches PaymentBO::processStripeCheckout() for expired case.
     */
    private function processStripeCheckoutExpired($session, ActionResult $result): ActionResult
    {
        $paymentBean = $this->loadPaymentBeanFromStripeSession($session);
        if (!$paymentBean) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load payment from Stripe session (expired).");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found');
        }

        $this->updatePayment($paymentBean, 'rejected_gateway', [
            'gatewayRejectionReason' => 'Stripe session expired',
            'gatewayLog' => print_r($session, true),
        ]);

        self::disablePaymentCommitment($paymentBean);

        return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment session expired');
    }

    /**
     * Process customer.subscription.deleted.
     * Matches PaymentBO::processStripeSubscription() behavior.
     */
    private function processStripeSubscriptionDeleted($subscription, ActionResult $result): ActionResult
    {
        $pcBean = BeanFactory::getBean('stic_Payment_Commitments');
        $pcBean = $pcBean->retrieve_by_string_fields(array('stripe_subscr_id' => $subscription->id));

        if ($pcBean && !empty($pcBean->id)) {
            if (!empty($subscription->ended_at)) {
                $pcBean->end_date = date('Y-m-d', $subscription->ended_at);
                $pcBean->gateway_log = ($pcBean->gateway_log ?? '') . '##### ' . print_r($subscription, true);
                $pcBean->save();
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Payment commitment [{$pcBean->id}] end_date set because Stripe subscription was deleted.");
            }
        } else {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not find Payment Commitment for Stripe subscription ID: {$subscription->id}");
        }

        return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Subscription deletion processed');
    }

    /**
     * Process invoice.payment_succeeded.
     * Matches PaymentBO::processStripeInvoice() behavior.
     */
    private function processStripeInvoiceSucceeded($invoice, ActionResult $result): ActionResult
    {
        if ($invoice->subscription == null) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invoice not related to a subscription. Skipping.");
            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Invoice not subscription-related, acknowledged');
        }

        $pcBean = BeanFactory::getBean('stic_Payment_Commitments');
        $pcBean = $pcBean->retrieve_by_string_fields(array('stripe_subscr_id' => $invoice->subscription));

        if (!$pcBean || empty($pcBean->id)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not find Payment Commitment for Stripe subscription ID: {$invoice->subscription}");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment commitment not found');
        }

        $paymentBean = $this->getBeanPaymentFromStripePaymentCommitment($pcBean, $invoice->created);
        if (!$paymentBean) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not find Payment for PC ID: {$pcBean->id} at invoice date.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found');
        }

        if ($invoice->paid) {
            $this->updatePayment($paymentBean, 'paid', [
                'amount' => $invoice->amount_paid / 100,
                'gatewayLog' => print_r($invoice, true),
            ]);
        } else {
            $this->updatePayment($paymentBean, 'pending', [
                'gatewayLog' => print_r($invoice, true),
            ]);
        }

        return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Invoice payment processed');
    }

    /**
     * Load a Payment bean from a Stripe Checkout Session.
     * Tries transaction_code from metadata first, then payment_id from context.
     */
    private function loadPaymentBeanFromStripeSession($session): ?stic_Payments
    {
        $transactionCode = $session->metadata['transaction_code'] ?? null;
        if (!empty($transactionCode)) {
            $paymentBean = BeanFactory::getBean('stic_Payments');
            $paymentBean = $paymentBean->retrieve_by_string_fields(array('transaction_code' => intval($transactionCode)));
            if ($paymentBean && !empty($paymentBean->id)) {
                return $paymentBean;
            }
        }

        $paymentId = $session->metadata['payment_id'] ?? null;
        if (!empty($paymentId)) {
            $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
            if ($paymentBean && !empty($paymentBean->id)) {
                return $paymentBean;
            }
        }

        return null;
    }

    /**
     * Get the Payment bean related to a Payment Commitment for a given Stripe payment timestamp.
     * Matches PaymentBO::getBeanPaymentFromStripePaymentCommitment() query.
     */
    private function getBeanPaymentFromStripePaymentCommitment($pcBean, $paymentTimestamp): ?stic_Payments
    {
        if (empty($pcBean->id)) {
            return null;
        }

        global $db;
        $pcId = $db->quote($pcBean->id);
        $paymentDate = date('Ym', $paymentTimestamp);
        $paymentIdSQL = "SELECT p.id
                         FROM stic_payments p
                            INNER JOIN stic_payments_stic_payment_commitments_c rel 
                                ON p.id = rel.stic_payments_stic_payment_commitmentsstic_payments_idb
                            INNER JOIN stic_payment_commitments pc 
                                ON pc.id = rel.stic_paymebfe2itments_ida
                         WHERE pc.Id = '{$pcId}'
                            AND p.deleted = 0
                            AND rel.deleted = 0
                            AND date_format(p.payment_date, '%Y%m') = {$paymentDate}
                         LIMIT 1";
        $paymentId = $db->getOne($paymentIdSQL);

        if (!empty($paymentId)) {
            return BeanFactory::getBean('stic_Payments', $paymentId);
        }

        return null;
    }

    /**
     * Get Stripe settings as key-pair array, matching PaymentBO::getStripeSettings() format.
     * Returns array of [configKey => ['STRIPE_SECRET_KEY' => ..., 'STRIPE_WEBHOOK_SECRET' => ...]]
     */
    private function getStripeSettings(): ?array
    {
        require_once "modules/stic_Settings/Utils.php";
        $settingsStripe = stic_SettingsUtils::getSettingsByType('STRIPE');

        if ($settingsStripe == null) {
            return null;
        }

        $mode = $settingsStripe['STRIPE_TEST'] ?? '';
        $stripeConsts = array();

        foreach ($settingsStripe as $key => $value) {
            if (str_ends_with($key, "SECRET_KEY") || str_ends_with($key, "SECRET_KEY_TEST")) {
                if (($mode == '0' && !str_ends_with($key, "_TEST") && str_starts_with($key, "STRIPE_")) ||
                    ($mode == '1' &&  str_ends_with($key, "_TEST") && str_starts_with($key, "STRIPE_"))) {
                    $webHookSecretKey = str_replace("SECRET_KEY", "WEBHOOK_SECRET", $key);
                    if (!isset($settingsStripe[$webHookSecretKey])) {
                        $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": STRIPE missing Setting {$webHookSecretKey}; Ignoring {$key}.");
                    } else {
                        $configKey = (str_starts_with($key, "STRIPE_ALT_")) ? str_replace(array("_TEST","STRIPE_ALT_","_SECRET_KEY"), "", $key) : "";
                        $stripeConsts[$configKey]['STRIPE_SECRET_KEY'] = $value;
                        $stripeConsts[$configKey]['STRIPE_WEBHOOK_SECRET'] = $settingsStripe[$webHookSecretKey];
                    }
                }
            }
        }

        return empty($stripeConsts) ? null : $stripeConsts;
    }

    /**
     * Generate a unique transaction code
     */
    private function generateTransactionCode(stic_Payments $beanPayment): string
    {
        $timestamp = date('ymdHi');
        $uniqueId = substr($beanPayment->id ?? uniqid(), 0, 8);
        return $timestamp . $uniqueId;
    }
}
