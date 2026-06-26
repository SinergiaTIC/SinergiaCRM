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

class stic_AWF_RedsysStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'TPV';
    protected string $configKeyPrefix = 'TPV';

        /**
    * Prepare payment.
    * If Offline -> Returns OK.
    * If External platform -> Returns WAIT with data to redirection.
    */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('CURRENCY', 'MERCHANT_CODE', 'TERMINAL', 'MERCHANT_NAME', 'TEST', 'PASSWORD', 'PASSWORD_TEST'));

        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $serverUrl = $isTest 
            ? 'https://sis-t.redsys.es:25443/sis/realizarPago'
            : 'https://sis.redsys.es/sis/realizarPago';
        $version = 'HMAC_SHA256_V1';
        $password = $isTest ? ($config['PASSWORD_TEST'] ?? '') : ($config['PASSWORD'] ?? '');

        $orderNumber = $this->generateTransactionCode($beanPayment);

        $PCBean = self::getPaymentCommitment($beanPayment);
        $paymentMethod = $beanPayment->payment_method ?? $actionConfig->data['payment_method'] ?? '';
        $isCardPayment = ($paymentMethod == 'card' || substr($paymentMethod, 0, 5) == 'card_');
        $isRecurring = !empty($actionConfig->data['recurring']) && $actionConfig->data['recurring'] != 'punctual';

        require_once 'modules/Currencies/Currency.php';
        $cleanAmount = unformat_number($beanPayment->amount);
        $amount = number_format($cleanAmount * 100, 0, '', '');

        $isCardRecurring = $isCardPayment && $PCBean && $PCBean->periodicity != 'punctual';
        if ($isCardRecurring) {
            if (!empty($PCBean->first_payment_date) && preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $PCBean->first_payment_date)) {
                $dateObj = DateTime::createFromFormat('d/m/Y', $PCBean->first_payment_date);
                if ($dateObj !== false) {
                    $PCBean->first_payment_date = $dateObj->format('Y-m-d');
                }
            }

            if (!empty($PCBean->first_payment_date) && $PCBean->first_payment_date > date('Y-m-d')) {
                $amount = '0';
                $orderNumber = substr($orderNumber, 0, 8) . '-AUT';
            }
        }

        $redsys = new RedsysAPI();
        $redsys->setParameter("DS_MERCHANT_AMOUNT", $amount);
        $redsys->setParameter("DS_MERCHANT_ORDER", $orderNumber);
        $redsys->setParameter("DS_MERCHANT_MERCHANTCODE", $config['MERCHANT_CODE'] ?? '');
        $redsys->setParameter("DS_MERCHANT_CURRENCY", $config['CURRENCY'] ?? '978');
        $redsys->setParameter("DS_MERCHANT_TRANSACTIONTYPE", '0');
        $redsys->setParameter("DS_MERCHANT_TERMINAL", $config['TERMINAL'] ?? '001');
        $redsys->setParameter("DS_MERCHANT_MERCHANTNAME", $config['MERCHANT_NAME'] ?? '');
        $redsys->setParameter("DS_MERCHANT_URL", $this->getCallbackUrl('redsys'));
        $redsys->setParameter("DS_MERCHANT_URLKO", $this->getReturnUrl('error'));
        $redsys->setParameter("DS_MERCHANT_URLOK", $this->getReturnUrl('ok'));

        if (strpos($paymentMethod, 'bizum') === 0) {
            $redsys->setParameter('DS_MERCHANT_PAYMETHODS', 'z');
            $isCardRecurring = false;
            $isRecurring = false;
        }

        if ($isCardRecurring) {
            $redsys->setParameter('DS_MERCHANT_IDENTIFIER', 'REQUIRED');
            $redsys->setParameter("DS_MERCHANT_COF_INI", "S");
            $redsys->setParameter("DS_MERCHANT_COF_TYPE", "R");
        } elseif ($isRecurring) {
            $redsys->setParameter("DS_MERCHANT_COFRANQUICIA", 'CRC');
            $redsys->setParameter("DS_MERCHANT_DCOFRANQUICIA", '3');
        }

        $paramsJson = $redsys->createMerchantParameters();
        $signature = $redsys->createMerchantSignature($password);

        $this->createTicket($context, $actionConfig, $beanPayment, $orderNumber);

        $formHtml = $this->renderTemplate('TPVFirstStep', [
            'ACTION' => $serverUrl,
            'PARAMS' => $paramsJson,
            'SIGNATURE' => $signature,
            'VERSION' => $version,
            'TRANSACTION_TYPE' => '0',
        ]);

        return new ActionResult(ResultStatus::WAIT, $actionConfig, '', [
            'strategy_class' => static::class,
            'strategy_suffix' => $this->suffix,
            'ticket_id' => $this->ticket->id ?? '',
            'payment_id' => $beanPayment->id,
            'form_html' => $formHtml,
            'order_number' => $orderNumber,
        ]);
    }

    /**
     * Terminal: Execute the output (HTML form).
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
     * WEBHOOK: Resolves action when notification arrives from Redsys.
     * Matches stic_Web_Forms PaymentController::actionTPVResponse() + PaymentBO::proccessTPVResponse() behavior.
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $data = $_POST;
        
        $params = $data['Ds_MerchantParameters'] ?? '';
        if (empty($params)) {
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Missing Ds_MerchantParameters');
        }

        $receivedSignature = $data['Ds_Signature'] ?? '';
        $version = $data['Ds_SignatureVersion'] ?? '';

        $tpvSys = new RedsysAPI();
        $decoded = $tpvSys->decodeMerchantParameters($params);
        $tpvSys->stringToArray($decoded);
        $tpvParams = $tpvSys->vars_pay;

        $paymentId = $context->getCustomData()['payment_id'] ?? null;
        if (!$paymentId) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": No payment_id in context data.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Missing payment_id');
        }

        $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
        if (!$paymentBean) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not retrieve payment with ID {$paymentId}.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Payment not found');
        }

        if (self::isAlreadyProcessed($paymentBean)) {
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Payment [{$paymentId}] already processed (status={$paymentBean->status}). Duplicate webhook acknowledged.");
            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Already processed');
        }

        $config = $this->getConfigValues(array('PASSWORD', 'PASSWORD_TEST', 'TEST'));
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $kc = $isTest ? ($config['PASSWORD_TEST'] ?? '') : ($config['PASSWORD'] ?? '');
        if (empty($kc)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not retrieve the TPV PASSWORD.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'TPV password not configured');
        }

        $signature = $tpvSys->createMerchantSignatureNotif($kc, $params);
        if ($signature != $receivedSignature) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature mismatch [{$signature}] vs [{$receivedSignature}].");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid signature');
        }

        if (!isset($tpvParams['Ds_Response']) || !isset($tpvParams['Ds_Order'])) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Missing Ds_Response or Ds_Order.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Missing required parameters');
        }

        $responseCode = $tpvParams['Ds_Response'];
        $responseNum = intval($responseCode);

        if (($responseNum >= 0 && $responseNum <= 99) || $responseNum == 900) {
            $authCode = $tpvParams['Ds_AuthorisationCode'] ?? '';
            $amount = intval($tpvParams['Ds_Amount'] ?? 0);

            if ($amount > 0) {
                $status = 'paid';
            } else {
                $status = 'not_remitted';
            }

            $this->updatePayment($paymentBean, $status, [
                'authCode' => $authCode,
                'gatewayLog' => print_r($tpvParams, true),
            ]);

            if (!empty($tpvParams['Ds_Merchant_Identifier']) && !empty($tpvParams['Ds_ExpiryDate']) && !empty($tpvParams['Ds_Merchant_Cof_Txnid'])) {
                $PCBean = self::getPaymentCommitment($paymentBean);
                if ($PCBean) {
                    $PCBean->redsys_ds_merchant_identifier = $tpvParams['Ds_Merchant_Identifier'];
                    $PCBean->card_expiry_date = $tpvParams['Ds_ExpiryDate'];
                    $PCBean->redsys_ds_merchant_cof_txnid = $tpvParams['Ds_Merchant_Cof_Txnid'];
                    $PCBean->save(false);
                }
            }

            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment successful', [
                'auth_code' => $authCode,
            ]);
        }

        require_once 'modules/stic_Web_Forms/Catcher/Include/Payment/lib/RedsysResponseCodes.php';
        $error = !empty($redsysResponseCode[$responseNum]) ? "[{$responseNum}] " . $redsysResponseCode[$responseNum] : 'Undefined Redsys error';

        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Payment [{$paymentBean->id}] failed. TPV response: [{$error}].");

        $this->updatePayment($paymentBean, 'rejected_gateway', [
            'gatewayRejectionReason' => $error,
            'gatewayLog' => print_r($tpvParams, true),
        ]);

        self::disablePaymentCommitment($paymentBean);

        return new ActionResult(ResultStatus::ERROR, $result->actionConfig, $error);
    }

    /**
     * Generate a unique transaction code (order number)
     */
    private function generateTransactionCode(stic_Payments $beanPayment): string
    {
        $timestamp = date('ymdHi');
        $uniqueId = substr($beanPayment->id ?? uniqid(), 0, 8);
        $code = $timestamp . $uniqueId;
        return substr($code, 0, 12);
    }
}
