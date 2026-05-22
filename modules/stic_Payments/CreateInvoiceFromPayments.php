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

require_once 'include/SugarObjects/VardefManager.php';
require_once 'modules/AOS_Invoices/Line_Items.php';

class CreateInvoiceFromPayments
{
    public static function getPaymentsByIds($paymentIds)
    {
        $payments = array();
        foreach ($paymentIds as $id) {
            $payment = BeanFactory::getBean('stic_Payments', $id);
            if ($payment && !$payment->deleted) {
                $payments[] = $payment;
            }
        }
        return $payments;
    }

    public static function validateSamePayer($payments)
    {
        $payerAccountId = null;
        $payerContactId = null;

        foreach ($payments as $payment) {
            $payment->load_relationship('stic_payments_accounts');
            $payment->load_relationship('stic_payments_contacts');

            $accountIds = $payment->stic_payments_accounts->get();
            $contactIds = $payment->stic_payments_contacts->get();

            $currentAccountId = !empty($accountIds[0]) ? $accountIds[0] : null;
            $currentContactId = !empty($contactIds[0]) ? $contactIds[0] : null;

            if ($payerAccountId === null && $currentAccountId !== null) {
                $payerAccountId = $currentAccountId;
            } elseif ($payerAccountId !== null && $currentAccountId !== null && $payerAccountId !== $currentAccountId) {
                return false;
            }

            if ($payerContactId === null && $currentContactId !== null) {
                $payerContactId = $currentContactId;
            } elseif ($payerContactId !== null && $currentContactId !== null && $payerContactId !== $currentContactId) {
                return false;
            }
        }

        return true;
    }

    public static function getPayerInfo($payments)
    {
        $payer = array(
            'account_id' => null,
            'account_name' => null,
            'contact_id' => null,
            'contact_name' => null,
        );

        foreach ($payments as $payment) {
            $payment->load_relationship('stic_payments_accounts');
            $payment->load_relationship('stic_payments_contacts');

            $accountIds = $payment->stic_payments_accounts->get();
            $contactIds = $payment->stic_payments_contacts->get();

            if (!empty($accountIds[0]) && $payer['account_id'] === null) {
                $account = BeanFactory::getBean('Accounts', $accountIds[0]);
                if ($account && !$account->deleted) {
                    $payer['account_id'] = $account->id;
                    $payer['account_name'] = $account->name;
                }
            }

            if (!empty($contactIds[0]) && $payer['contact_id'] === null) {
                $contact = BeanFactory::getBean('Contacts', $contactIds[0]);
                if ($contact && !$contact->deleted) {
                    $payer['contact_id'] = $contact->id;
                    $payer['contact_name'] = $contact->name;
                }
            }

            if (($payer['account_id'] !== null || $payer['contact_id'] !== null) &&
                ($payer['account_name'] !== null || $payer['contact_name'] !== null)) {
                break;
            }
        }

        return $payer;
    }

    public static function validatePaymentsNotInvoiced($payments)
    {
        $alreadyInvoiced = array();

        foreach ($payments as $payment) {
            $payment->load_relationship('aos_invoices');
            $invoices = $payment->aos_invoices->get();
            if (!empty($invoices)) {
                $alreadyInvoiced[] = $payment->name ? $payment->name : $payment->id;
            }
        }

        return $alreadyInvoiced;
    }

    public static function createInvoiceFromPayments($paymentIds, $invoiceData = array())
    {
        global $current_user, $timedate;

        $payments = self::getPaymentsByIds($paymentIds);
        if (empty($payments)) {
            return array('success' => false, 'message' => 'No se encontraron pagos');
        }

        if (!self::validateSamePayer($payments)) {
            return array('success' => false, 'message' => 'Todos los pagos deben ser del mismo pagador');
        }

        $alreadyInvoiced = self::validatePaymentsNotInvoiced($payments);
        if (!empty($alreadyInvoiced)) {
            return array(
                'success' => false,
                'message' => 'Los siguientes pagos ya están facturados: ' . implode(', ', $alreadyInvoiced)
            );
        }

        $payer = self::getPayerInfo($payments);

        $invoice = BeanFactory::newBean('AOS_Invoices');

        $invoice->name = $invoiceData['name'] ?? 'Invoice from Payments';
        $invoice->invoice_date = $invoiceData['invoice_date'] ?? $timedate->nowDate();
        $invoice->due_date = $invoiceData['due_date'] ?? null;
        $invoice->status = 'Draft';
        $invoice->assigned_user_id = $current_user->id;

        if (!empty($payer['account_id'])) {
            $invoice->billing_account_id = $payer['account_id'];
            $invoice->billing_account = $payer['account_name'];
        }

        if (!empty($payer['contact_id'])) {
            $invoice->billing_contact_id = $payer['contact_id'];
            $invoice->billing_contact = $payer['contact_name'];
        }

        $invoice->save();

        $totalAmount = 0;
        $lineItems = array();

        foreach ($payments as $payment) {
            $lineItem = BeanFactory::newBean('AOS_Products_Quotes');
            $lineItem->name = $payment->name ? $payment->name : 'Payment';
            $lineItem->product_id = '';
            $lineItem->product_qty = 1;
            $lineItem->product_unit_price = $payment->amount;
            $lineItem->product_list_price = $payment->amount;
            $lineItem->product_cost_price = 0;
            $lineItem->product_margin = 0;
            $lineItem->vat = 0;
            $lineItem->product_unit_price_usdollar = 0;
            $lineItem->product_cost_price_usdollar = 0;
            $lineItem->product_list_price_usdollar = 0;
            $lineItem->product_margin_usdollar = 0;

            $lineItem->parent_type = 'AOS_Invoices';
            $lineItem->parent_id = $invoice->id;

            $lineItem->save();
            $lineItems[] = $lineItem;

            $totalAmount += floatval($payment->amount);

            $payment->load_relationship('aos_invoices');
            $payment->aos_invoices->add($invoice->id);
        }

        $invoice->load_relationship('aos_products_quotes');
        foreach ($lineItems as $lineItem) {
            $invoice->aos_products_quotes->add($lineItem->id);
        }

        return array(
            'success' => true,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->number,
            'total_amount' => $totalAmount
        );
    }

    public static function getPaymentsByPayer($payerAccountId = null, $payerContactId = null, $excludeInvoiced = true)
    {
        $sql = "SELECT sp.id,
                       spa.stic_payments_accountsaccounts_ida as account_id,
                       spc.stic_payments_contactscontacts_ida as contact_id
                FROM stic_payments sp
                LEFT JOIN stic_payments_accounts spa ON sp.id = spa.stic_payments_id
                LEFT JOIN stic_payments_contacts spc ON sp.id = spc.stic_payments_id
                LEFT JOIN aos_invoices_stic_payments aisp ON sp.id = aisp.stic_payments_id
                WHERE sp.deleted = 0";

        $params = array();

        if ($excludeInvoiced) {
            $sql .= " AND aisp.aos_invoices_id IS NULL";
        }

        if ($payerAccountId) {
            $sql .= " AND spa.stic_payments_accountsaccounts_ida = ?";
            $params[] = $payerAccountId;
        }

        if ($payerContactId) {
            $sql .= " AND spc.stic_payments_contactscontacts_ida = ?";
            $params[] = $payerContactId;
        }

        if ($payerAccountId || $payerContactId) {
            $sql .= " GROUP BY sp.id ORDER BY sp.payment_date DESC";
        }

        $db = DBManagerFactory::getInstance();
        $result = $db->pquery($sql, $params);

        $paymentsWithPayer = array();
        while ($row = $db->fetchByAssoc($result)) {
            $payment = BeanFactory::getBean('stic_Payments', $row['id']);
            if ($payment && !$payment->deleted) {
                $payerName = '';
                $payerType = '';

                if (!empty($row['account_id'])) {
                    $account = BeanFactory::getBean('Accounts', $row['account_id']);
                    if ($account && !$account->deleted) {
                        $payerName = $account->name;
                        $payerType = 'account';
                    }
                } elseif (!empty($row['contact_id'])) {
                    $contact = BeanFactory::getBean('Contacts', $row['contact_id']);
                    if ($contact && !$contact->deleted) {
                        $payerName = $contact->name;
                        $payerType = 'contact';
                    }
                }

                $payment->payer_name = $payerName;
                $payment->payer_type = $payerType;
                $paymentsWithPayer[] = $payment;
            }
        }

        return $paymentsWithPayer;
    }
}