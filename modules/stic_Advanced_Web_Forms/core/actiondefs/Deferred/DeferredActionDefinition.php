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

abstract class DeferredActionDefinition extends ServerActionDefinition {
    final public function getType(): ActionType {
        return ActionType::DEFERRED;
    }

    /**
     * Procesa una petición entrante (webhook) de un servicio externo.
     * Este método solo es relevante para aquellas acciones que esperan un callback de servidor.
     * 
     * @param array $requestData Los datos de la petición entrante.
     * @return WebhookResult El objeto con el ID de la transacción y el estado.
     */
    public abstract function processWebhook(array $requestData): WebhookResult;

    /**
     * Decide si el resultado externo es un Éxito o un Error para decidir qué flujo ejecutar: flow_success_id o flow_error_id
     * 
     * @param ExecutionContext $context Execution context of the action
     * @param WebhookResult El objeto con el ID de la transacción y el estado.
     * Decideix si el resultat extern és un Èxit o un Error.
     * Retorna un ActionResult estàndard.
     * * - Si isSuccess() -> El Executor dispararà el flow_success_id.
     * - Si !isSuccess() -> El Executor dispararà el flow_error_id.
     * - getData() -> S'incorporaran al formulari ($context->mergeData).
     */
    abstract public function resolve(ExecutionContext $context, WebhookResult $webhookData): ActionResult;
}