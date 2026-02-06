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

include_once __DIR__."/PaymentStrategy.php";

class stic_AWF_RedsysStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'TPV';
    protected string $configKeyPrefix = 'TPV';

    public function isDeferred(): bool {
        return true;
    }

    public function initiate(ExecutionContext $context, FormAction $actionConfig): ActionResult {
        $config = $this->getConfigValues(array('CURRENCY', 'MERCHANT_CODE', 'TERMINAL', 'MERCHANT_NAME', 'TEST', 'PASSWORD', 'PASSWORD_TEST'));

        // TODO: Lògica
        // // 2. Validació bàsica
        // if (empty($config['MERCHANT_CODE']) || empty($config['CLAVE_SECRETA'])) {
        //     $suffixMsg = $this->suffix ? " (Suffix: {$this->suffix})" : "";
        //     return new ActionResult(ResultStatus::ERROR, null, "Redsys configuration missing for MERCHANT_CODE or CLAVE_SECRETA{$suffixMsg}.");
        // }

        // // 3. Ús dels valors
        // $code = $config['MERCHANT_CODE'];
        // $secret = $config['CLAVE_SECRETA'];
        // $terminal = $config['TERMINAL'];
        // $url = !empty($config['URL']) ? $config['URL'] : 'https://sis.redsys.es/sis/realizarPago';
        // $currency = !empty($config['MONEDA']) ? $config['MONEDA'] : '978';

        // ... Recuperar amount del context ...
        // ... Generar Firma ...

        // (La resta del codi de generació de firma igual que abans)
        
        return new ActionResult(ResultStatus::WAIT, $actionConfig, "");
    }

    public function resolve(ExecutionContext $context, WebhookResult $webhookData): ActionResult {
        // TODO: Lògica
    }
}