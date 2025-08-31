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

/**
 * DTO (Data Transfer Object) para per estandarditzar el resultat del processament d'un webhook.
 *
 * Aquesta classe serveix com a contenidor de dades estandarditzat. La implementació del
 * mètode `processWebhook()` en una acció diferida (ex: StripePaymentAction) és responsable
 * d'analitzar les dades brutes del webhook i traduir-les a aquest format simple,
 * que l'EntryPoint-Router pot entendre sense conèixer els detalls del servei extern.
 */
class stic_AWF_WebhookResult {
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILURE = 'failure';
    public const STATUS_PENDING = 'pending';
    public const STATUS_IGNORED = 'ignored'; // Útil per a webhooks informatius que no requereixen cap acció.

    /**
     * El constructor utilitza la promoció de propietats de PHP 8+ per a més concisió.
     *
     * @param string|null $externalTransactionId L'identificador únic de la transacció del servei extern
     * (ex: l'ID de la sessió de Stripe 'cs_...'). És la clau
     * per buscar el registre de Resposta corresponent a la BBDD.
     * @param string $status L'estat del resultat, que hauria de ser una de les constants de la classe
     * (ex: self::STATUS_SUCCESS). Aquest estat determinarà quin flux
     * d'execució ('on_success_flow', 'on_failure_flow') es dispararà.
     * @param string|null $message Un missatge opcional per a tasques de logging o depuració.
     * @param array $extraData Un array associatiu opcional per passar dades addicionals del webhook
     * (com l'import final, el mètode de pagament, etc.) a les accions
     * del flux diferit, a través del context.
     */
    public function __construct(
        public ?string $externalTransactionId,
        public string $status,
        public ?string $message = null,
        public array $extraData = []
    ) {}
}