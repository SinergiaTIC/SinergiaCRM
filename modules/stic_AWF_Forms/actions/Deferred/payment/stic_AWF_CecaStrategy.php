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

    /**
     * Prepare payment via CECA TPV.
     * Returns WAIT with form HTML.
     */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payments $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('CURRENCY', 'MERCHANT_CODE', 'ACQUIRER_BIN', 'TERMINAL', 'TEST', 'PASSWORD'));
        
        $isTest = !empty($config['TEST']) && $config['TEST'] == '1';
        $serverUrl = $isTest 
            ? 'https://tpv.ceca.es/tpvweb/tpv/compra.action'
            : 'https://pgw.ceca.es/tpvweb/tpv/compra.action';
            
        $orderNumber = $this->generateTransactionCode($beanPayment);
        
        $amountCents = number_format($beanPayment->amount * 100, 0, '', '');
        
        $formHtml = $this->renderTemplate('CecaFirstStep', [
            'ACTION' => $serverUrl,
            'MERCHANT_ID' => $config['MERCHANT_CODE'] ?? '',
            'ACQUIRER_BIN' => $config['ACQUIRER_BIN'] ?? '',
            'TERMINAL' => $config['TERMINAL'] ?? '001',
            'ORDER' => $orderNumber,
            'AMOUNT' => $amountCents,
            'CURRENCY' => $config['CURRENCY'] ?? '978',
            'TRANSACTION_TYPE' => '0',
            'PAN' => '',
            'EXPIRY_DATE' => '',
            'CVV' => '',
            'SHA256' => $this->generateCecaSignature($config, $orderNumber, $amountCents),
            'URL_OK' => $this->getReturnUrl('success'),
            'URL_KO' => $this->getReturnUrl('error'),
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
     */
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {
        $rawData = $_POST;
        
        $responseCode = $rawData['response_code'] ?? '';
        $authCode = $rawData['auth_code'] ?? '';
        
        $successCodes = ['00', '0000', '000'];
        
        if (in_array($responseCode, $successCodes)) {
            $paymentId = $context->getCustomData()['payment_id'] ?? null;
            if ($paymentId) {
                $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
                if ($paymentBean) {
                    $this->updatePayment($paymentBean, 'completed', $authCode);
                }
            }
            return new ActionResult(ResultStatus::OK, $result->actionConfig, 'Payment successful', [
                'auth_code' => $authCode,
            ]);
        }
        
        require_once 'modules/stic_Web_Forms/Catcher/Include/Payment/lib/CecaResponseCodes.php';
        $errorMessage = CecaResponseCodes::getMessage($responseCode) ?? 'Payment failed (code: ' . $responseCode . ')';
        
        $paymentId = $context->getCustomData()['payment_id'] ?? null;
        if ($paymentId) {
            $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);
            if ($paymentBean) {
                $this->updatePayment($paymentBean, 'failed', null);
            }
        }
        
        return new ActionResult(ResultStatus::ERROR, $result->actionConfig, $errorMessage);
    }

    /**
     * Generate CECA SHA256 signature
     */
    private function generateCecaSignature(array $config, string $orderNumber, string $amount): string
    {
        $password = $config['PASSWORD'] ?? '';
        $merchantCode = $config['MERCHANT_CODE'] ?? '';
        $acquirerBin = $config['ACQUIRER_BIN'] ?? '';
        $terminal = $config['TERMINAL'] ?? '001';
        
        $data = $merchantCode . $acquirerBin . $terminal . $orderNumber . $amount . '978' . '0' . $password;
        
        return hash('sha256', $data);
    }

    /**
     * Generate a unique transaction code
     */
    private function generateTransactionCode(stic_Payment $beanPayment): string
    {
        $timestamp = date('ymdHi');
        $uniqueId = substr($beanPayment->id ?? uniqid(), 0, 8);
        $code = $timestamp . $uniqueId;
        return str_pad($code, 12, '0', STR_PAD_LEFT);
    }
}