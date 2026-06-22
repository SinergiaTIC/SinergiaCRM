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

class stic_AWF_CecaStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'TPVCECA';
    protected string $configKeyPrefix = 'TPVCECA';

    public static function getSourceName(): string
    {
        return 'ceca';
    }

    public static function extractExternalId(array $rawData, string $rawBody , array $headers): ?string
    {
        return $rawData['Num_operacion'] ?? null;
    }

    /**
     * Prepare payment via CECA TPV.
     * Returns WAIT with form HTML.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('CURRENCY', 'MERCHANT_CODE', 'ACQUIRER_BIN', 'TERMINAL', 'TEST', 'PASSWORD', 'PASSWORD_TEST'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $serverUrl = $isTest 
            ? 'https://tpv.ceca.es/tpvweb/tpv/compra.action'
            : 'https://pgw.ceca.es/tpvweb/tpv/compra.action';
            
        $orderNumber = $this->generateTransactionCode($beanPayment);
        
        $amountCents = number_format($beanPayment->amount * 100, 0, '', '');

        $password = $isTest ? ($config['PASSWORD_TEST'] ?? '') : ($config['PASSWORD'] ?? '');
        $merchantCode = str_pad($config['MERCHANT_CODE'] ?? '', 9, '0', STR_PAD_LEFT);
        $acquirerBin = str_pad($config['ACQUIRER_BIN'] ?? '', 10, '0', STR_PAD_LEFT);
        $terminal = str_pad($config['TERMINAL'] ?? '001', 8, '0', STR_PAD_LEFT);
        
        $okURL = $this->getReturnUrl('ok');
        $koURL = $this->getReturnUrl('error');

        $formHtml = $this->renderTemplate('CecaFirstStep', [
            'ACTION' => $serverUrl,
            'MERCHANT_ID' => $merchantCode,
            'ACQUIRER_BIN' => $acquirerBin,
            'TERMINAL' => $terminal,
            'ORDER' => $orderNumber,
            'AMOUNT' => $amountCents,
            'CURRENCY' => $config['CURRENCY'] ?? '978',
            'TRANSACTION_TYPE' => '0',
            'PAN' => '',
            'EXPIRY_DATE' => '',
            'CVV' => '',
            'SHA256' => $this->generateCecaSignature($password, $merchantCode, $acquirerBin, $terminal, $orderNumber, $amountCents, $config['CURRENCY'] ?? '978', $okURL, $koURL),
            'URL_OK' => $okURL,
            'URL_KO' => $koURL,
        ]);
        
        $this->createTicket($context, $actionConfig, $beanPayment, $orderNumber);
        
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
     * Terminal: Output CECA form HTML.
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
     * WEBHOOK: Resolves action when notification arrives from CECA.
     * Matches stic_Web_Forms PaymentController::actionCECAResponse() + PaymentBO::proccessTPVCECAResponse() behavior.
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $requestData = $_REQUEST;

        if (!isset($requestData['Num_operacion'])) {
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Missing Num_operacion');
        }

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

        $config = $this->getConfigValues(array('CURRENCY', 'MERCHANT_CODE', 'ACQUIRER_BIN', 'TERMINAL', 'TEST', 'PASSWORD', 'PASSWORD_TEST'));
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $password = $isTest ? ($config['PASSWORD_TEST'] ?? '') : ($config['PASSWORD'] ?? '');

        $merchantCode = str_pad($config['MERCHANT_CODE'] ?? '', 9, '0', STR_PAD_LEFT);
        $acquirerBin = str_pad($config['ACQUIRER_BIN'] ?? '', 10, '0', STR_PAD_LEFT);
        $terminal = str_pad($config['TERMINAL'] ?? '001', 8, '0', STR_PAD_LEFT);

        if (empty($password)) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": TPVCECA PASSWORD not configured.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'CECA password not configured');
        }

        $receivedSignature = $requestData['Firma'] ?? '';
        $signSourceString = 
            $password 
            . ($requestData['MerchantID'] ?? '') 
            . ($requestData['AcquirerBIN'] ?? '') 
            . ($requestData['TerminalID'] ?? '') 
            . $requestData['Num_operacion'] 
            . ($requestData['Importe'] ?? '') 
            . ($config['CURRENCY'] ?? '978') 
            . ($requestData['Exponente'] ?? '') 
            . ($requestData['Referencia'] ?? $requestData['Codigo_error'] ?? '');

        if (strlen(trim($signSourceString)) > 0) {
            $computedSignature = strtolower(hash('sha256', $signSourceString));
        } else {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Empty signature source string.");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid CECA signature data');
        }

        if ($computedSignature != $receivedSignature) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": CECA signature mismatch. Computed [{$computedSignature}] vs received [{$receivedSignature}].");
            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Invalid signature');
        }

        if (!empty($requestData['Referencia'])) {
            $this->updatePayment($paymentBean, 'paid', [
                'authCode' => $requestData['Referencia'],
                'gatewayLog' => print_r($requestData, true),
            ]);

            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment successful', [
                'referencia' => $requestData['Referencia'],
            ]);
        } elseif (!empty($requestData['Codigo_error'])) {
            require_once 'modules/stic_Web_Forms/Catcher/Include/Payment/lib/CecaResponseCodes.php';
            $errorCode = $requestData['Codigo_error'];
            $errorMsg = !empty($cecaResponseCode[$errorCode]) ? $errorCode . ' - ' . $cecaResponseCode[$errorCode] : $errorCode;

            $this->updatePayment($paymentBean, 'rejected_gateway', [
                'gatewayRejectionReason' => $errorMsg,
                'gatewayLog' => print_r($requestData, true),
            ]);

            self::disablePaymentCommitment($paymentBean);

            return new ActionResult(ResultStatus::ERROR, $result->actionConfig, $errorMsg);
        }

        return new ActionResult(ResultStatus::ERROR, $result->actionConfig, 'Missing Referencia and Codigo_error');
    }

    /**
     * Generate CECA SHA256 signature for payment initiation.
     * Format: PASSWORD + MERCHANT_CODE + ACQUIRER_BIN + TERMINAL + ORDER + AMOUNT + CURRENCY + "0"
     */
    private function generateCecaSignature(string $password, string $merchantCode, string $acquirerBin, string $terminal, string $orderNumber, string $amount, string $currency, string $okURL, string $koURL): string
    {
        $data = $password . $merchantCode . $acquirerBin . $terminal . $orderNumber . $amount . $currency . '2' . 'SHA2' . $okURL . $koURL;
        return strtolower(hash('sha256', $data));
    }

    /**
     * Generate a unique transaction code
     */
    private function generateTransactionCode(stic_Payments $beanPayment): string
    {
        $timestamp = date('ymdHi');
        $uniqueId = substr($beanPayment->id ?? uniqid(), 0, 8);
        $code = $timestamp . $uniqueId;
        return substr($code, 0, 12);
    }
}
