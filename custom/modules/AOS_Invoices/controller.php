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

        // Copy basic information from original invoice (excluding numeric totals)
        $fieldsToCopy = [
            'name',
            'billing_account_id',
            'billing_account',
            'billing_contact_id',
            'billing_contact',
            'shipping_contact_id',
            'shipping_contact',
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
        
        // Copy totals directly in database to avoid formatting issues
        $totalFields = [
            'total_amt',
            'discount_amount', 
            'subtotal_amount',
            'tax_amount',
            'shipping_amount',
            'shipping_tax',
            'shipping_tax_amt',
            'total_amount',
            'subtotal_tax_amount',
        ];
        
        $updateParts = [];
        foreach ($totalFields as $field) {
            if (isset($originalInvoice->$field) && $originalInvoice->$field !== null && $originalInvoice->$field !== '') {
                $value = $rectifiedInvoice->db->quote($originalInvoice->$field);
                $updateParts[] = "$field = $value";
            }
        }
        
        if (!empty($updateParts)) {
            $updateQuery = "UPDATE aos_invoices SET " . implode(', ', $updateParts) . " WHERE id = '".$rectifiedInvoice->id."'";
            $rectifiedInvoice->db->query($updateQuery);
            $GLOBALS['log']->debug("Updated totals directly in database for invoice {$rectifiedInvoice->id}");
        }

        // Add text to original invoice description to reference rectification
        $originalInvoice->description .= "\n {$mod_strings['LBL_ORIGINAL_INVOICE_RECTIFIED_BY']} {$rectifiedInvoice->number}";
        $originalInvoice->save();

        // Copy line item groups from original invoice
        $originalToRectifiedGroupIds = [];
        if (!empty($originalInvoice->id)) {
            $query = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$originalInvoice->id."' AND deleted = 0";
            $result = $rectifiedInvoice->db->query($query);
            
            while ($row = $rectifiedInvoice->db->fetchByAssoc($result)) {
                $originalGroupId = $row['id'];
                
                // Modify row for new group
                $row['id'] = '';
                $row['parent_id'] = $rectifiedInvoice->id;
                $row['parent_type'] = 'AOS_Invoices';
                
                // Format number fields
                if ($row['total_amt'] != null) {
                    $row['total_amt'] = format_number($row['total_amt']);
                }
                if ($row['discount_amount'] != null) {
                    $row['discount_amount'] = format_number($row['discount_amount']);
                }
                if ($row['subtotal_amount'] != null) {
                    $row['subtotal_amount'] = format_number($row['subtotal_amount']);
                }
                if ($row['tax_amount'] != null) {
                    $row['tax_amount'] = format_number($row['tax_amount']);
                }
                if ($row['subtotal_tax_amount'] != null) {
                    $row['subtotal_tax_amount'] = format_number($row['subtotal_tax_amount']);
                }
                if ($row['total_amount'] != null) {
                    $row['total_amount'] = format_number($row['total_amount']);
                }
                
                $newLineItemGroup = BeanFactory::newBean('AOS_Line_Item_Groups');
                $newLineItemGroup->populateFromRow($row);
                $newLineItemGroup->save();
                
                $originalToRectifiedGroupIds[$originalGroupId] = $newLineItemGroup->id;
                $GLOBALS['log']->debug("Copied line item group: Original ID={$originalGroupId}, New ID={$newLineItemGroup->id}");
            }
        }

        // Copy line items from original invoice
        if (!empty($originalInvoice->id)) {
            $query = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$originalInvoice->id."' AND deleted = 0 ORDER BY number";
            $result = $rectifiedInvoice->db->query($query);
            
            $lineCount = 0;
            while ($row = $rectifiedInvoice->db->fetchByAssoc($result)) {
                $lineCount++;
                
                // Store original ID for logging and custom fields lookup
                $originalLineId = $row['id'];
                
                // Generate new UUID for the line item
                $newId = create_guid();
                
                // Prepare values for insert
                $row['id'] = $newId;
                $row['parent_id'] = $rectifiedInvoice->id;
                $row['parent_type'] = 'AOS_Invoices';
                $row['date_entered'] = date('Y-m-d H:i:s');
                $row['date_modified'] = date('Y-m-d H:i:s');
                $row['modified_user_id'] = $GLOBALS['current_user']->id;
                $row['created_by'] = $GLOBALS['current_user']->id;
                
                // Update group_id if it was mapped
                if (!empty($row['group_id']) && isset($originalToRectifiedGroupIds[$row['group_id']])) {
                    $row['group_id'] = $originalToRectifiedGroupIds[$row['group_id']];
                }
                
                // Build INSERT query with all fields from the row
                $fields = [];
                $values = [];
                foreach ($row as $field => $value) {
                    $fields[] = $field;
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . $rectifiedInvoice->db->quote($value) . "'";
                    }
                }
                
                $insertQuery = "INSERT INTO aos_products_quotes (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
                $rectifiedInvoice->db->query($insertQuery);
                
                // Copy custom fields from aos_products_quotes_cstm table
                $customQuery = "SELECT * FROM aos_products_quotes_cstm WHERE id_c = '".$originalLineId."'";
                $customResult = $rectifiedInvoice->db->query($customQuery);
                if ($customRow = $rectifiedInvoice->db->fetchByAssoc($customResult)) {
                    $customRow['id_c'] = $newId;
                    
                    $customFields = [];
                    $customValues = [];
                    foreach ($customRow as $field => $value) {
                        $customFields[] = $field;
                        if ($value === null) {
                            $customValues[] = 'NULL';
                        } else {
                            $customValues[] = "'" . $rectifiedInvoice->db->quote($value) . "'";
                        }
                    }
                    
                    $insertCustomQuery = "INSERT INTO aos_products_quotes_cstm (" . implode(', ', $customFields) . ") VALUES (" . implode(', ', $customValues) . ")";
                    $rectifiedInvoice->db->query($insertCustomQuery);
                    
                    $GLOBALS['log']->debug("Copied custom fields for line item {$lineCount}: Operation Type={$customRow['verifactu_aeat_operation_type_c']}");
                }
                
                $GLOBALS['log']->debug("Copied line item {$lineCount}: Original ID={$originalLineId}, New ID={$newId}, Product={$row['product_id']}, Name={$row['name']}");
            }
            
            $GLOBALS['log']->debug("Total line items copied: {$lineCount} for invoice {$rectifiedInvoice->id}");
        }
        
        // Add success message
        SugarApplication::appendSuccessMessage($mod_strings['LBL_RECTIFIED_INVOICE_CREATED_SUCCESS']);

        // Redirect to EditView of the new rectified invoice
        SugarApplication::redirect("index.php?module=AOS_Invoices&action=EditView&record={$rectifiedInvoice->id}");
    }
}
