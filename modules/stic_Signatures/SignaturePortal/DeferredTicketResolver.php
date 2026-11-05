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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Resolves the AWF deferred ticket associated to a signature portal session.
 *
 * The signing portal acts as the external event that resolves the deferred
 * "Firmar documento" action (the equivalent of the payment gateway webhook for
 * PaymentRouterAction): when the signature is registered (or when the portal is
 * displayed for an already signed signer), the deferred ticket is marked as
 * resolved so the deferred flow can be resumed via stic_AWF_returnHandler.
 */
class DeferredTicketResolver
{
    /**
     * Resolves the deferred ticket identified by a return token, if applicable.
     *
     * @param string $returnToken Ticket token_hash from the URL.
     * @param string $signerId    Signer of the portal session.
     * @param bool   $resultOk    Whether the portal operation (save/accept) succeeded.
     * @return ?string returnHandler URL to resume the deferred flow, or null when nothing to do.
     */
    public static function resolve(string $returnToken, string $signerId, bool $resultOk): ?string
    {
        if ($returnToken === '' || !$resultOk && !$signerId) {
            return null;
        }

        // Consider the signing event valid if the current operation succeeded or
        // the signer is already signed (e.g. repeated save attempts or portal reloads).
        if (!$resultOk) {
            $signer = BeanFactory::getBean('stic_Signers', $signerId);
            if (!$signer || empty($signer->id) || ($signer->status ?? '') !== 'signed') {
                return null;
            }
        }

        /** @var stic_AWF_Deferred_Tickets $ticket */
        $ticket = BeanFactory::getBean('stic_AWF_Deferred_Tickets');
        $ticket->retrieve_by_string_fields(['token_hash' => $returnToken, 'deleted' => 0]);
        if (empty($ticket->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": No deferred ticket found for returnToken");
            return null;
        }

        if (($ticket->status ?? '') === 'pending') {
            $ticket->status = 'resolved';
            $ticket->save();
        }

        global $sugar_config;
        $siteUrl = rtrim($sugar_config['site_url'] ?? '', '/');
        return $siteUrl . '/index.php?entryPoint=stic_AWF_returnHandler&token=' . urlencode($returnToken);
    }
}
