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
     * Ejecuta la "acción terminal" de la acción diferida (si existe)
     * Se llama después de preparar y ejecutar el guardado del ticket (execute)
     * Es donde se hace la redirección a la plataforma externa: 'echo', 'header()', 'exit'.
     * 
     * @param ExecutionContext $context El contexto actual
     * @param ActionResult $executionResult El resultado que ha retornado execute()
     */
    public function performDeferral(ExecutionContext $context, ActionResult $executionResult) {
        // Por defecto no hace nada
        // Las acciones diferidas que redireccionen deberán sobreescribir la función
    }

    /**
     * Procesa una petición entrante (webhook) de un servicio externo.
     * Este método solo es relevante para aquellas acciones que esperan un callback de servidor.
     * 
     * @param array $requestData Los datos de la petición entrante.
     * @return WebhookResult El objeto con el ID de la transacción y el estado.
     */
    public abstract function processWebhook(array $requestData): WebhookResult;

}