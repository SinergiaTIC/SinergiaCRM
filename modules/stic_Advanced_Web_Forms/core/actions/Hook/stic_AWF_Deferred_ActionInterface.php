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

interface stic_AWF_Deferred_ActionInterface 
  extends stic_AWF_Hook_ActionInterface {
    /**
     * Procesa una petición entrante (webhook) de un servicio externo.
     * Este método solo es relevante para aquellas acciones que esperan un callback de servidor.
     * 
     * @param array $requestData Los datos de la petición entrante.
     * @return stic_AWF_WebhookResult El objeto con el ID de la transacción y el estado.
     */
    public function processWebhook(array $requestData): stic_AWF_WebhookResult;
}