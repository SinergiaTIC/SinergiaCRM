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

class stic_AWF_StripeStrategy extends stic_AWF_PaymentStrategy
{
    protected string $configType = 'STRIPE'; 
    protected string $configKeyPrefix = 'STRIPE';

    /**
    * Prepare payment.
    * If Offline -> Returns OK.
    * If External platform -> Returns WAIT with data to redirection.
    */
    public function initiate(ExecutionContext $context, FormAction $actionConfig, stic_Payment $beanPayment): ActionResult
    {
        $config = $this->getConfigValues(array('SECRET_KEY', 'WEBHOOK_SECRET', 'SECRET_KEY_TEST', 'WEBHOOK_SECRET_TEST', 'TEST'));

        
        return new ActionResult(ResultStatus::WAIT, $actionConfig, "");
    }

    /**
    * Terminal: Execute the output (HTML form, Redirect header...).
    * Only called if initiate() has returned WAIT.
    */
    public function performTerminal(ExecutionContext $context, ActionResult $result): void
    {

    }

    /**
    * WEBHOOK: Resolves action when notification arrives from external event.
    */ 
    public function resolve(ExecutionContext $context, ActionResult $result): ActionResult
    {

    }
}