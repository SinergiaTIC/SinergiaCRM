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

require_once 'modules/AOS_Invoices/controller.php';
class CustomAOS_InvoicesController extends AOS_InvoicesController
{
    public function action_sendToAEAT()
    {
        global $mod_strings;
        
        $invoiceBean = BeanFactory::getBean('AOS_Invoices', $_REQUEST['invoiceId'] ?? '');
        if(empty($invoiceBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice not found with ID ' . ($_REQUEST['invoiceId'] ?? 'N/A'));
            SugarApplication::appendErrorMessage('Factura no encontrada');
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }
        
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::sendToAeat($invoiceBean);
        
        // Redirect back to invoice
        SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
    }
}
