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

require_once 'include/MVC/View/views/view.edit.php';

class stic_PaymentsViewCreateinvoicefrompayments extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        parent::preDisplay();
    }

    public function display()
    {
        if (!empty($_POST['confirm_create'])) {
            $this->processCreateInvoice();
        } else {
            $this->displayForm();
        }
    }

    private function processCreateInvoice()
    {
        global $mod_strings;

        $paymentIds = $_POST['payment_ids'] ?? array();
        if (empty($paymentIds)) {
            SugarApplication::appendErrorMessage($mod_strings['LBL_NO_PAYMENTS_SELECTED']);
            SugarApplication::redirect('index.php?module=stic_Payments&action=ListView');
            return;
        }

        require_once 'modules/stic_Payments/CreateInvoiceFromPayments.php';

        $invoiceData = array(
            'name' => $_POST['invoice_name'] ?? 'Invoice from Payments',
            'invoice_date' => $_POST['invoice_date'] ?? null,
            'due_date' => $_POST['due_date'] ?? null,
        );

        $result = CreateInvoiceFromPayments::createInvoiceFromPayments($paymentIds, $invoiceData);

        if ($result['success']) {
            SugarApplication::appendSuccessMessage($mod_strings['LBL_INVOICE_CREATED_SUCCESS']);
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView&record=' . $result['invoice_id']);
        } else {
            SugarApplication::appendErrorMessage($result['message']);
            SugarApplication::redirect('index.php?module=stic_Payments&action=ListView');
        }
    }

    private function displayForm()
    {
        $payments = $this->bean->view_object_map['PAYMENTS'] ?? array();
        $payer = $this->bean->view_object_map['PAYER'] ?? array();
        $totalAmount = $this->bean->view_object_map['TOTAL_AMOUNT'] ?? 0;

        $this->ss = new Sugar_Smarty();
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('PAYMENTS', $payments);
        $this->ss->assign('PAYER', $payer);
        $this->ss->assign('TOTAL_AMOUNT', $totalAmount);
        $this->ss->assign('SITE_URL', $GLOBALS['sugar_config']['site_url']);

        $this->ss->display('modules/stic_Payments/tpls/CreateInvoiceFromPayments.tpl');
    }
}