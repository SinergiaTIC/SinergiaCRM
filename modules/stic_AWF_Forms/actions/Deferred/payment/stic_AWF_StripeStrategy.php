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

    /**
     * Prepare payment via Stripe Checkout.
     * Returns WAIT with redirect URL to Stripe.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('SECRET_KEY', 'WEBHOOK_SECRET', 'TEST'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $secretKey = $config['SECRET_KEY'] ?? '';
        
        if (empty($secretKey)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, 'Stripe API key not configured');
        }

        require_once 'SticInclude/vendor/stripe/stripe-php/init.php';
        
        \Stripe\Stripe::setApiKey($secretKey);
        
        $transactionCode = $this->generateTransactionCode($beanPayment);
        
        $successUrl = $this->getReturnUrl('success') . '&session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = $this->getReturnUrl('cancel');
        
        try {
            $session = \Stripe\Checkout\Session::create([
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
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'payment_id' => $beanPayment->id,
                    'transaction_code' => $transactionCode,
                    'form_id' => $context->formId,
                    'response_id' => $context->responseId,
                ],
            ]);
            
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
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $config = $this->getConfigValues(array('WEBHOOK_SECRET', 'TEST'));
        $webhookSecret = $config['WEBHOOK_SECRET'] ?? '';
        
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Webhook signature verification failed');
        }
        
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $metadata = $session->metadata ?? null;
                
                $paymentId = $context->getCustomData()['payment_id'] ?? ($metadata->payment_id ?? null);
                if ($paymentId) {
                    $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
                    if ($paymentBean) {
                        $this->updatePayment($paymentBean, 'completed', $session->payment_intent ?? '');
                    }
                }
                return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment completed');
                
            case 'checkout.session.async_payment_succeeded':
                $paymentId = $context->getCustomData()['payment_id'] ?? null;
                if ($paymentId) {
                    $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
                    if ($paymentBean) {
                        $this->updatePayment($paymentBean, 'completed', '');
                    }
                }
                return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment succeeded (async)');
                
            case 'checkout.session.expired':
                return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment session expired');
                
            case 'checkout.session.payment_failed':
                return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment failed');
                
            default:
                return new ActionResult(ResultStatus::WAIT, $result->actionConfig, 'Unhandled event type: ' . $event->type);
        }
    }

    /**
     * Generate a unique transaction code
     */
    private function generateTransactionCode(stic_Payment $beanPayment): string
    {
        $timestamp = date('ymdHi');
        $uniqueId = substr($beanPayment->id ?? uniqid(), 0, 8);
        return $timestamp . $uniqueId;
    }
}