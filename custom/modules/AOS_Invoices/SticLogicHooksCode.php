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

class AOS_InvoicesHook
{

    public function before_save($bean, $event, $arguments)
    {

        // If the serial format field is empty, set a default value
        if (empty($bean->stic_serial_format_c)) {
            $bean->stic_serial_format_c = '0';
        }

        // Generate the next invoice number based on the serial format
        if (empty($bean->number) && !empty($bean->stic_serial_format_c)) {
            require_once 'custom/modules/AOS_Invoices/SticUtils.php';
            $bean->number = AOS_InvoicesUtils::generateNextInvoiceNumber($bean->stic_serial_format_c, $bean);
        }
    }

    /**
     *
     * @param SugarBean $bean El bean de la factura
     * @param string $event El evento que disparó el hook
     * @param array $arguments Argumentos adicionales
     */
    public function after_save($bean, $event, $arguments)
    {
        return;
        // check if status is 'emitted'
        if ($bean->status !== 'emitted') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} status is not 'emitted', skipping AEAT send.");
            return;
        }

        // check if already sent
        if (!empty($bean->verifactu_aeat_status_c) && $bean->verifactu_aeat_status_c === 'sent') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} has already been sent to AEAT, skipping resend.");
            return;
        }

        // check if status changed to 'emitted' (only send on status change)
        if (!empty($bean->fetched_row['status']) && $bean->fetched_row['status'] === 'emitted') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} was already in 'emitted' status, skipping send.");
            return;
        }

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Sending invoice with id {$bean->id} to AEAT via Verifactu...");

        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::sendToAeat($bean);
    }
}
