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

class stic_AWF_PaypalStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'PAYPAL';
    protected string $configKeyPrefix = 'PAYPAL';

    /**
     * Prepare payment via PayPal.
     * Returns WAIT with PayPal form HTML.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('ID', 'TEST'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $paypalUrl = $isTest 
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';
        $paypalId = $config['ID'] ?? '';
        
        if (empty($paypalId)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, 'PayPal ID not configured');
        }

        $transactionCode = $this->generateTransactionCode($beanPayment);
        
        $this->createTicket($context, $actionConfig, $beanPayment, $transactionCode);
        
        $formHtml = $this->renderTemplate('PaypalFirstStep', [
            'PAYPAL_URL' => $paypalUrl,
            'PAYPAL_ID' => $paypalId,
            'AMOUNT' => number_format($beanPayment->amount, 2, '.', ''),
            'CURRENCY' => 'EUR',
            'CMD' => '_xclick',
            'BUSINESS' => $paypalId,
            'ITEM_NAME' => 'Payment - ' . $transactionCode,
            'ITEM_NUMBER' => $transactionCode,
            'INVOICE' => $transactionCode,
            'RETURN' => $this->getReturnUrl('success'),
            'CANCEL_RETURN' => $this->getReturnUrl('cancel'),
            'NOTIFY_URL' => $this->getCallbackUrl('paypal'),
            'CUSTOM' => $transactionCode,
            'CONFIRM_CODE' => $transactionCode,
        ]);
        
        return new ActionResult(ResultStatus::WAIT, $actionConfig, '', [
            'strategy_class' => static::class,
            'strategy_suffix' => $this->suffix,
            'ticket_id' => $this->ticket->id ?? '',
            'payment_id' => $beanPayment->id,
            'form_html' => $formHtml,
            'transaction_code' => $transactionCode,
        ]);
    }

    /**
     * Terminal: Output PayPal form HTML.
     * Only called if initiate() has returned WAIT.
     */
    public function performTerminal(ExecutionContext $context, ActionResult $result): void
    {
        $data = $result->getData();
        if (!empty($data['form_html'])) {
            echo $data['form_html'];
        } else {
            echo '<p>Error: No payment form available</p>';
        }
    }

    /**
     * WEBHOOK: Resolves action when IPN notification arrives from PayPal.
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $rawData = $_POST;
        
        if (empty($rawData)) {
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Empty PayPal IPN data');
        }
        
        $config = $this->getConfigValues(array('ID', 'TEST'));
        
        $receiverEmail = $rawData['receiver_email'] ?? '';
        $expectedEmail = $config['ID'] ?? '';
        
        if (strtolower($receiverEmail) !== strtolower($expectedEmail)) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": PayPal IPN: receiver_email mismatch. Expected: {$expectedEmail}, Got: {$receiverEmail}");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid receiver email');
        }
        
        $paymentStatus = $rawData['payment_status'] ?? '';
        $txnId = $rawData['txn_id'] ?? '';
        $custom = $rawData['custom'] ?? '';
        
        switch (strtolower($paymentStatus)) {
            case 'completed':
            case 'processed':
                $transactionCode = $rawData['custom'] ?? '';
                $txnId = $rawData['txn_id'] ?? '';
                
                if ($transactionCode) {
                    $paymentBean = BeanFactory::getBean('stic_Payments');
                    $paymentBean->retrieve_by_string_fields(['transaction_code' => $transactionCode]);
                } else {
                    $paymentId = $context->getCustomData()['payment_id'] ?? null;
                    $paymentBean = $paymentId ? BeanFactory::getBean('stic_Payments', $paymentId) : null;
                }
                
                if ($paymentBean && !empty($paymentBean->id)) {
                    $this->updatePayment($paymentBean, 'completed', $txnId);
                }
                return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment completed', [
                    'txn_id' => $txnId,
                ]);
                
            case 'pending':
                $pendingReason = $rawData['pending_reason'] ?? 'unknown';
                return new ActionResult(ResultStatus::WAIT, $result->actionConfig, 'Payment pending: ' . $pendingReason);
                
            case 'denied':
            case 'failed':
            case 'reversed':
                return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment ' . $paymentStatus);
                
            case 'refunded':
                return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment refunded');
                
            default:
                return new ActionResult(ResultStatus::WAIT, $result->actionConfig, 'Unknown payment status: ' . $paymentStatus);
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