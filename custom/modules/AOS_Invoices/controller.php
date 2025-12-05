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
            SugarApplication::appendErrorMessage($mod_strings['LBL_INVOICE_NOT_FOUND']);
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }
        
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::sendToAeat($invoiceBean);
        
        // Redirect back to invoice
        SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
    }
    
    /**
     * Action to create a rectified invoice from an existing invoice.
     * 
     * This action creates a new invoice with the rectified flag set and pre-populated
     * with the original invoice information, including line items.
     */
    public function action_CreateRectifiedInvoice()
    {
        global $mod_strings;

        // Get the original invoice ID
        $originalId = $_REQUEST['record'] ?? '';

        if (empty($originalId)) {
            SugarApplication::appendErrorMessage($mod_strings['LBL_ORIGINAL_INVOICE_NOT_SPECIFIED']);
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=index");
            return;
        }

        // Load the original invoice
        $originalInvoice = BeanFactory::getBean('AOS_Invoices', $originalId);

        if (empty($originalInvoice->id)) {
            SugarApplication::appendErrorMessage($mod_strings['LBL_ORIGINAL_INVOICE_NOT_FOUND']);
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=index");
            return;
        }

        // Verify the invoice has been sent to AEAT
        if (empty($originalInvoice->verifactu_submitted_at_c)) {
            SugarApplication::appendErrorMessage($mod_strings['LBL_ORIGINAL_INVOICE_MUST_BE_SENT_TO_AEAT']);
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=DetailView&record=$originalId");
            return;
        }

        // Verify it's not already a rectified invoice
        if (!empty($originalInvoice->verifactu_is_rectified_c)) {
            SugarApplication::appendErrorMessage($mod_strings['LBL_CANNOT_RECTIFY_RECTIFIED_INVOICE']);
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=DetailView&record=$originalId");
            return;
        }

        // Create a new invoice (rectified)
        $rectifiedInvoice = BeanFactory::newBean('AOS_Invoices');

        // Copy basic information from original invoice
        $fieldsToCopy = [
            'name',
            'billing_account_id',
            'billing_account',
            'billing_contact_id',
            'billing_contact',
            'billing_address_street',
            'billing_address_city',
            'billing_address_state',
            'billing_address_postalcode',
            'billing_address_country',
            'shipping_address_street',
            'shipping_address_city',
            'shipping_address_state',
            'shipping_address_postalcode',
            'shipping_address_country',
            'currency_id',
            'assigned_user_id',
            'description',
            'invoice_date',
            'due_date',
        ];

        foreach ($fieldsToCopy as $field) {
            if (isset($originalInvoice->$field)) {
                $rectifiedInvoice->$field = $originalInvoice->$field;
            }
        }

        // Set the rectified invoice flag and related fields
        $rectifiedInvoice->verifactu_is_rectified_c = true;
        $rectifiedInvoice->verifactu_cancel_id_c = $originalInvoice->id;
        $rectifiedInvoice->verifactu_rectified_date_c = $originalInvoice->invoice_date ?? '';

        // Set default rectification type to Substitution
        $rectifiedInvoice->verifactu_rectified_type_c = 'S';

        // Set the rectified invoice series from configuration
        global $sugar_config;
        if (!empty($sugar_config['aos']['invoices']['series'])) {
            foreach ($sugar_config['aos']['invoices']['series'] as $seriesName => $seriesConfig) {
                if (!empty($seriesConfig['isRectified'])) {
                    $rectifiedInvoice->stic_invoice_type_c = $seriesName;
                    break;
                }
            }
        }

        // Append to the name to indicate it's a rectified invoice
        $rectifiedInvoice->name = $originalInvoice->name .  "({$mod_strings['LBL_RECTIFIED']})";

        // Save the rectified invoice first to get an ID
        $rectifiedInvoice->save();

        // Add text to original invoice description to reference rectification
        $originalInvoice->description .= "\n {$mod_strings['LBL_ORIGINAL_INVOICE_RECTIFIED_BY']} {$rectifiedInvoice->number}";
        $originalInvoice->save();

        // Copy line items from original invoice
        if (!empty($originalInvoice->id)) {
            $query = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = ? AND deleted = 0 ORDER BY number";
            $result = $rectifiedInvoice->db->pquery($query, [$originalInvoice->id]);
            
            while ($row = $rectifiedInvoice->db->fetchByAssoc($result)) {
                $newLineItem = BeanFactory::newBean('AOS_Products_Quotes');
                
                // Copy all fields except id, parent_id, and audit fields
                $excludeFields = ['id', 'parent_id', 'date_entered', 'date_modified', 'created_by', 'modified_user_id'];
                
                foreach ($row as $field => $value) {
                    if (!in_array($field, $excludeFields) && isset($newLineItem->$field)) {
                        $newLineItem->$field = $value;
                    }
                }
                
                // Set the new parent
                $newLineItem->parent_type = 'AOS_Invoices';
                $newLineItem->parent_id = $rectifiedInvoice->id;
                
                $newLineItem->save();
            }
        }

        // Recalculate totals
        require_once('modules/AOS_Invoices/AOS_Invoices.php');
        $tempInvoice = BeanFactory::getBean('AOS_Invoices', $rectifiedInvoice->id);
        
        // Add success message
        SugarApplication::appendSuccessMessage($mod_strings['LBL_RECTIFIED_INVOICE_CREATED_SUCCESS']);

        // Redirect to EditView of the new rectified invoice
        SugarApplication::redirect("index.php?module=AOS_Invoices&action=EditView&record={$rectifiedInvoice->id}");
    }
}
