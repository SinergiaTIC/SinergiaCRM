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

    protected array $contextCustomData = [];

    public static function getSourceName(): string
    {
        return 'paypal';
    }

    public static function extractExternalId(array $rawData, string $rawBody): ?string
    {
        return $rawData['custom'] ?? null;
    }

    /**
     * Prepare payment via PayPal.
     * Returns WAIT with PayPal form HTML.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('ID', 'ID_TEST', 'URL', 'URL_TEST', 'TEST'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $paypalUrl = $isTest 
            ? ($config['URL_TEST'] ?? 'https://www.sandbox.paypal.com/cgi-bin/webscr')
            : ($config['URL'] ?? 'https://www.paypal.com/cgi-bin/webscr');
        $paypalId = $isTest ? ($config['ID_TEST'] ?? '') : ($config['ID'] ?? '');
        
        if (empty($paypalId)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, 'PayPal ID not configured');
        }

        $transactionCode = $this->generateTransactionCode($beanPayment);
        
        $this->createTicket($context, $actionConfig, $beanPayment, $transactionCode);

        $isRecurring = !empty($actionConfig->data['recurring']) && $actionConfig->data['recurring'] != 'punctual';
        $cmd = $isRecurring ? '_xclick-subscriptions' : '_xclick';
        
        $templateVars = [
            'PAYPAL_URL' => $paypalUrl,
            'PAYPAL_ID' => $paypalId,
            'AMOUNT' => number_format($beanPayment->amount, 2, '.', ''),
            'CURRENCY' => 'EUR',
            'CMD' => $cmd,
            'BUSINESS' => $paypalId,
            'ITEM_NAME' => 'Payment - ' . $transactionCode,
            'ITEM_NUMBER' => $transactionCode,
            'INVOICE' => $transactionCode,
            'RETURN' => $this->getReturnUrl('success'),
            'CANCEL_RETURN' => $this->getReturnUrl('cancel'),
            'NOTIFY_URL' => $this->getCallbackUrl('paypal'),
            'CUSTOM' => $transactionCode,
            'CONFIRM_CODE' => $transactionCode,
        ];

        if ($isRecurring) {
            $templateVars['CMD'] = '_xclick-subscriptions';
            $templateVars['A3'] = number_format($beanPayment->amount, 2, '.', '');
            $templateVars['P3'] = '1';
            $templateVars['T3'] = 'M';
            $templateVars['SRC'] = '1';
            $templateVars['SRA'] = '1';
        }
        
        $formHtml = $this->renderTemplate('PaypalFirstStep', $templateVars);
        
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
     * Matches stic_Web_Forms PaymentController::actionPaypalResponse() + PaymentBO::proccessPaypalResponse() behavior.
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $rawData = $_POST;
        
        if (empty($rawData)) {
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Empty PayPal IPN data');
        }

        // Store context customData for use in verifyPayPal()
        $this->contextCustomData = $context->getCustomData();

        $verifyResult = $this->verifyPayPal();
        if (strcmp($verifyResult, "VERIFIED") != 0) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invalid or empty PayPal IPN message: {$verifyResult}");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid IPN source');
        }

        $txnType = $rawData['txn_type'] ?? '';
        $rawData['subscr_id'] = $rawData['subscr_id'] ?? null;

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Processing PayPal IPN Type: [{$txnType}]");

        switch ($txnType) {
            case 'web_accept':
            case 'subscr_payment':
                return $this->processPaypalPayment($rawData, $result);

            case 'subscr_signup':
                return $this->processPaypalSubscrSignup($rawData, $result);

            case 'subscr_cancel':
                return $this->processPaypalSubscrCancel($rawData, $result);

            default:
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Unhandled PayPal IPN type: [{$txnType}]");
                return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Unhandled IPN type acknowledged');
        }
    }

    /**
     * Process web_accept / subscr_payment IPN.
     * Matches PaymentBO::proccessPaypalResponse() behavior for payment types.
     */
    private function processPaypalPayment(array $ipnMessage, ActionResult $result): ActionResult
    {
        $paymentStatus = $ipnMessage['payment_status'] ?? '';
        $paymentBean = $this->getPaymentByIPNMessage($ipnMessage);

        if (empty($paymentBean)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not retrieve payment from PayPal IPN. subscr_id: " . ($ipnMessage['subscr_id'] ?? 'null') . " | custom: " . ($ipnMessage['custom'] ?? 'null'));
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found');
        }

        if ($paymentStatus == 'Completed') {
            $this->updatePayment($paymentBean, 'paid', [
                'authCode' => $ipnMessage['txn_id'] ?? '',
                'amount' => floatval($ipnMessage['mc_gross'] ?? 0),
                'gatewayLog' => print_r($ipnMessage, true),
            ]);
            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment completed', [
                'txn_id' => $ipnMessage['txn_id'] ?? '',
            ]);
        }

        $this->updatePayment($paymentBean, 'rejected_gateway', [
            'gatewayRejectionReason' => 'PayPal status: ' . $paymentStatus,
            'gatewayLog' => print_r($ipnMessage, true),
        ]);

        self::disablePaymentCommitment($paymentBean);

        return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment ' . $paymentStatus);
    }

    /**
     * Process subscr_signup IPN.
     * Matches PaymentBO::proccessPaypalResponse() subscr_signup handling.
     */
    private function processPaypalSubscrSignup(array $ipnMessage, ActionResult $result): ActionResult
    {
        $transactionCode = $ipnMessage['custom'] ?? '';
        $PCBean = $this->getPaymentCommitmentByIPNTransactionCode($transactionCode);

        if ($PCBean && !empty($PCBean->id)) {
            $PCBean->paypal_subscr_id = $ipnMessage['subscr_id'] ?? '';
            $PCBean->gateway_log = ($PCBean->gateway_log ?? '') . '###### ' . print_r($ipnMessage, true);
            $PCBean->save(false);
        } else {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not find Payment Commitment for PayPal subscr_signup. custom: {$transactionCode}");
        }

        return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Subscription signup processed');
    }

    /**
     * Process subscr_cancel IPN.
     * Matches PaymentBO::proccessPaypalResponse() subscr_cancel handling.
     */
    private function processPaypalSubscrCancel(array $ipnMessage, ActionResult $result): ActionResult
    {
        $subscrId = $ipnMessage['subscr_id'] ?? '';
        $PCBean = $this->getPaymentCommitmentByIPNSubscrId($subscrId);

        if ($PCBean && !empty($PCBean->id)) {
            $PCBean->end_date = date('Y-m-d');
            $PCBean->gateway_log = ($PCBean->gateway_log ?? '') . '###### ' . print_r($ipnMessage, true);
            $PCBean->save(false);
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": {$PCBean->name}. PayPal subscription {$subscrId} has been cancelled.");
        } else {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not find Payment Commitment for PayPal subscr_cancel. subscr_id: {$subscrId}");
        }

        return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Subscription cancellation processed');
    }

    /**
     * Verify that the IPN actually comes from PayPal.
     * Matches PaymentController::verifyPayPal() behavior.
     */
    private function verifyPayPal(): ?string
    {
        $curlInfo = curl_version();
        if ($curlInfo['ssl_version'] < "OpenSSL/1.0.1") {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ": SSL_VERSION [{$curlInfo['ssl_version']}] less than OpenSSL/1.0.1t. Verification omitted.");
            return "VERIFIED";
        }

        // Use raw body from context if available (WebhookHandler pre-reads php://input)
        $customData = $this->contextCustomData ?? [];
        $rawPostData = $customData['_rawBody'] ?? file_get_contents('php://input');
        $rawPostArray = explode('&', $rawPostData);
        $myPost = array();

        foreach ($rawPostArray as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value) {
            if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $config = $this->getConfigValues(array('URL', 'URL_TEST', 'TEST'));
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $paypalUrl = $isTest ? ($config['URL_TEST'] ?? 'https://www.sandbox.paypal.com/cgi-bin/webscr') : ($config['URL'] ?? 'https://www.paypal.com/cgi-bin/webscr');

        $ch = curl_init($paypalUrl);
        if ($ch == false) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Can't start CURL.");
            return null;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        $res = curl_exec($ch);

        if (curl_errno($ch) != 0) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Can't connect to PayPal to validate IPN [" . curl_error($ch) . "]");
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": PayPal verification result [{$res}]");
        return $res;
    }

    /**
     * Get the payment bean from PayPal IPN message.
     * Matches PaymentBO::getPaymentByIPNMessage() behavior.
     */
    private function getPaymentByIPNMessage(array $ipnMessage): ?stic_Payments
    {
        $subscrId = $ipnMessage['subscr_id'] ?? null;
        $paymentDate = date('Ym', strtotime($ipnMessage['payment_date']));

        if (!empty($subscrId)) {
            $paymentIdSQL = "SELECT p.id
                             FROM stic_payment_commitments pc
                             JOIN stic_payments_stic_payment_commitments_c rel ON
                                 rel.stic_paymebfe2itments_ida = pc.id
                             JOIN stic_payments p ON
                                 p.id = rel.stic_payments_stic_payment_commitmentsstic_payments_idb
                             WHERE TRIM(pc.paypal_subscr_id) != ''
                                 AND pc.paypal_subscr_id = '{$subscrId}'
                                 AND p.status = 'pending'
                                 AND date_format(p.payment_date, '%Y%m') = {$paymentDate}
                                 AND p.deleted = 0
                                 AND rel.deleted = 0
                             LIMIT 1";
            $paymentId = $GLOBALS['db']->getOne($paymentIdSQL);

            if (!empty($paymentId)) {
                $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
                if ($paymentBean && !empty($paymentBean->id)) {
                    return $paymentBean;
                }
            }
        }

        $transactionCode = $ipnMessage['custom'] ?? '';
        if (!empty($transactionCode)) {
            $paymentBean = BeanFactory::getBean('stic_Payments');
            $paymentBean->retrieve_by_string_fields(array('transaction_code' => intval($transactionCode)));
            if ($paymentBean && !empty($paymentBean->id)) {
                return $paymentBean;
            }
        }

        return null;
    }

    /**
     * Get Payment Commitment by PayPal subscription ID.
     * Matches PaymentBO::getPaymentCommitmentByIPNSubscrId() behavior.
     */
    private function getPaymentCommitmentByIPNSubscrId(string $subscrId): ?stic_Payment_Commitments
    {
        $pcBean = BeanFactory::getBean('stic_Payment_Commitments');
        $pcBean = $pcBean->retrieve_by_string_fields(array('paypal_subscr_id' => $subscrId));
        return ($pcBean && !empty($pcBean->id)) ? $pcBean : null;
    }

    /**
     * Get Payment Commitment by transaction code from PayPal IPN.
     * Matches PaymentBO::getPaymentCommitmentByIPNTransactionCode() behavior.
     */
    private function getPaymentCommitmentByIPNTransactionCode(string $transactionCode): ?stic_Payment_Commitments
    {
        global $db;
        $safeCode = $db->quote(intval($transactionCode));

        $pcIdSql = "SELECT rel.stic_paymebfe2itments_ida
                    FROM stic_payments p
                    INNER JOIN stic_payments_stic_payment_commitments_c rel ON
                        p.id = rel.stic_payments_stic_payment_commitmentsstic_payments_idb
                        AND p.deleted = 0
                        AND rel.deleted = 0
                        AND p.transaction_code = '{$safeCode}'";

        $pcId = $db->getOne($pcIdSql);
        if (!empty($pcId)) {
            $pcBean = BeanFactory::getBean('stic_Payment_Commitments', $pcId);
            if ($pcBean && !empty($pcBean->id)) {
                return $pcBean;
            }
        }

        return null;
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
