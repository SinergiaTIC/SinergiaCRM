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
    /**
     * Override editview to populate Contact addresses when a Contact is selected.
     */
    public function action_editview()
    {
        parent::action_editview();

        // Determine which customer ID was passed via popup selection.
        // The popup sends billing_account_id/billing_contact_id (not account_id/contact_id).
        // The core controller uses account_id/contact_id only for the Quote-to-Invoice flow.
        $selectedContactId = $_REQUEST['billing_contact_id'] ?? $_REQUEST['contact_id'] ?? '';
        $selectedAccountId = $_REQUEST['billing_account_id'] ?? $_REQUEST['account_id'] ?? '';

        // If a Contact was selected but NO Account was selected, populate addresses from Contact
        // (Account addresses take precedence when both are present)
        if (!empty($selectedContactId) && empty($selectedAccountId)) {
            $query = "SELECT * FROM contacts WHERE id = '?'";
            $result = $this->bean->db->pquery($query, [$selectedContactId]);
            $row = $this->bean->db->fetchByAssoc($result);
            if ($row) {
                // Primary address → billing address
                $this->bean->billing_address_street = $row['primary_address_street'];
                $this->bean->billing_address_city = $row['primary_address_city'];
                $this->bean->billing_address_state = $row['primary_address_state'];
                $this->bean->billing_address_postalcode = $row['primary_address_postalcode'];
                $this->bean->billing_address_country = $row['primary_address_country'];

                // Alternate address → shipping address (if exists, otherwise copy from primary)
                if (!empty($row['alt_address_street'])) {
                    $this->bean->shipping_address_street = $row['alt_address_street'];
                    $this->bean->shipping_address_city = $row['alt_address_city'];
                    $this->bean->shipping_address_state = $row['alt_address_state'];
                    $this->bean->shipping_address_postalcode = $row['alt_address_postalcode'];
                    $this->bean->shipping_address_country = $row['alt_address_country'];
                } else {
                    $this->bean->shipping_address_street = $row['primary_address_street'];
                    $this->bean->shipping_address_city = $row['primary_address_city'];
                    $this->bean->shipping_address_state = $row['primary_address_state'];
                    $this->bean->shipping_address_postalcode = $row['primary_address_postalcode'];
                    $this->bean->shipping_address_country = $row['primary_address_country'];
                }

                // Store identification number on bean for view/JS validation
                $this->bean->customer_id_number = $row['stic_identification_number_c'] ?? '';
            }
        } elseif (!empty($selectedAccountId)) {
            // When an Account is selected, also store its identification number
            $query = "SELECT stic_identification_number_c FROM accounts WHERE id = '?'";
            $result = $this->bean->db->pquery($query, [$selectedAccountId]);
            $row = $this->bean->db->fetchByAssoc($result);
            if ($row) {
                $this->bean->customer_id_number = $row['stic_identification_number_c'] ?? '';
            }
        }
    }

    /**
     * Override delete action (uses before_delete hook for blocking logic).
     */
    public function action_delete()
    {
        parent::action_delete();
    }

    /**
     * Block edit of invoices sent to AEAT.
     */
    public function action_edit()
    {
        global $mod_strings;

        $recordId = $_REQUEST['record'] ?? '';
        if (!empty($recordId)) {
            $invoiceBean = BeanFactory::getBean('AOS_Invoices', $recordId);
            if (!empty($invoiceBean->id) && !empty($invoiceBean->verifactu_submitted_at_c)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Attempt to edit invoice sent to AEAT: ' . $recordId);
                SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_VERIFACTU_CANNOT_EDIT_SENT']));
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $recordId);
                return;
            }
        }

        parent::action_edit();
    }

    public function action_sendToAEAT()
    {
        global $mod_strings;

        // Check if Verifactu is activated - if not, show error message
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        if (!AOS_InvoicesUtils::isVerifactuActivated()) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Attempt to send invoice to AEAT but Verifactu is not activated.');
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_VERIFACTU_NOT_ACTIVATED_SEND_ERROR']));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }

        $invoiceBean = BeanFactory::getBean('AOS_Invoices', $_REQUEST['invoiceId'] ?? '');
        if(empty($invoiceBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice not found with ID ' . ($_REQUEST['invoiceId'] ?? 'N/A'));
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_INVOICE_NOT_FOUND']));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }
        
        if(!empty($_REQUEST['set']) && $_REQUEST['set'] === 'emitted') {
            $invoiceBean->status = 'emitted';
            $invoiceBean->save();
            // after_save hook already called sendToAeat - skip direct call to avoid double-send
        } else {
            require_once 'custom/modules/AOS_Invoices/SticUtils.php';
            AOS_InvoicesUtils::sendToAeat($invoiceBean);
        }
        
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
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_ORIGINAL_INVOICE_NOT_SPECIFIED']));
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=index");
            return;
        }

        // Load the original invoice
        $originalInvoice = BeanFactory::getBean('AOS_Invoices', $originalId);

        if (empty($originalInvoice->id)) {
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_ORIGINAL_INVOICE_NOT_FOUND']));
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=index");
            return;
        }

        // Verify the invoice has been sent to AEAT
        if (empty($originalInvoice->verifactu_submitted_at_c)) {
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_ORIGINAL_INVOICE_MUST_BE_SENT_TO_AEAT']));
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=DetailView&record=$originalId");
            return;
        }

        // Verify the invoice has customer data (required for rectified invoices of type R1)
        // Error 1189: F1/F3/R1/R2/R3/R4 require Destinatarios block
        if (empty($originalInvoice->billing_account_id) && empty($originalInvoice->billing_contact_id)) {
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_ORIGINAL_INVOICE_NO_CUSTOMER_DATA']));
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=DetailView&record=$originalId");
            return;
        }

        // === Task 2: Verify the original invoice hasn't been rectified already (accepted by AEAT) ===
        $existingRectified = $originalInvoice->db->query(
            "SELECT id, number FROM aos_invoices i "
            . "INNER JOIN aos_invoices_cstm c ON i.id = c.id_c "
            . "WHERE c.verifactu_cancel_id_c = '{$originalInvoice->db->quote($originalId)}' "
            . "AND c.verifactu_aeat_status_c = 'accepted' "
            . "AND i.deleted = 0"
        );
        $existing = $originalInvoice->db->fetchByAssoc($existingRectified);
        if (!empty($existing)) {
            $existingRef = $existing['number'] ?? $existing['id'];
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert(
                str_replace('{0}', $existingRef, $mod_strings['LBL_ORIGINAL_ALREADY_RECTIFIED'])
            ));
            SugarApplication::redirect("index.php?module=AOS_Invoices&action=DetailView&record=$originalId");
            return;
        }
        // === End Task 2 ===

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
        
        // Log customer information for debugging
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Copied customer info - billing_account_id: ' . ($rectifiedInvoice->billing_account_id ?? 'empty') . ', billing_contact_id: ' . ($rectifiedInvoice->billing_contact_id ?? 'empty'));

        // Set the rectified invoice flag and related fields
        // IMPORTANT: Always set these fields to the correct values for THIS rectification
        // Never copy verifactu_cancel_id_c, verifactu_rectified_date_c, verifactu_rectified_type_c from original
        $rectifiedInvoice->verifactu_is_rectified_c = true;
        $rectifiedInvoice->verifactu_cancel_id_c = $originalInvoice->id;  // ID of the invoice we're rectifying
        $rectifiedInvoice->verifactu_rectified_date_c = $originalInvoice->invoice_date ?? '';
        
        // Set default rectification type to Substitution
        $rectifiedInvoice->verifactu_rectified_type_c = 'S';
        
        // Set default rectification base if not already set
        if (empty($rectifiedInvoice->verifactu_rectified_base_c)) {
            $rectifiedInvoice->verifactu_rectified_base_c = 'R1';
        }

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
        
        // Re-establish customer relationship explicitly after save to ensure it persists
        // This is necessary because SuiteCRM may not save relationship IDs correctly during initial save
        if (!empty($originalInvoice->billing_account_id)) {
            $rectifiedInvoice->billing_account_id = $originalInvoice->billing_account_id;
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Re-setting billing_account_id: ' . $originalInvoice->billing_account_id);
        }
        if (!empty($originalInvoice->billing_contact_id)) {
            $rectifiedInvoice->billing_contact_id = $originalInvoice->billing_contact_id;
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Re-setting billing_contact_id: ' . $originalInvoice->billing_contact_id);
        }
        if (!empty($originalInvoice->shipping_contact_id)) {
            $rectifiedInvoice->shipping_contact_id = $originalInvoice->shipping_contact_id;
        }
        
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

        // === Step 1.6: Add audit log entries for rectification ===
        $auditTimestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $rectifiedRef = !empty($rectifiedInvoice->number) ? $rectifiedInvoice->number : $rectifiedInvoice->name;

        // Audit log for the rectified invoice
        $rectifiedAuditLog = $rectifiedInvoice->verifactu_audit_log_c ?? '';
        if (!empty($rectifiedAuditLog)) {
            $rectifiedAuditLog .= "\n";
        }
        $rectifiedAuditLog .= "[{$auditTimestamp}] " . str_replace(['{0}', '{1}'], [$originalInvoice->number, $originalInvoice->id], $mod_strings['LBL_AUDIT_RECTIFIED_CREATED']);
        $rectifiedInvoice->verifactu_audit_log_c = $rectifiedAuditLog;
        $rectifiedInvoice->save();

        // Audit log for the original invoice
        // Use direct DB UPDATE to bypass before_save protection on accepted invoices
        $originalAuditLog = $originalInvoice->verifactu_audit_log_c ?? '';
        if (!empty($originalAuditLog)) {
            $originalAuditLog .= "\n";
        }
        $originalAuditLog .= "[{$auditTimestamp}] " . str_replace(['{0}', '{1}'], [$rectifiedRef, $rectifiedInvoice->id], $mod_strings['LBL_AUDIT_ORIGINAL_RECTIFIED']);
        $auditLogQuoted = $originalInvoice->db->quote($originalAuditLog);
        $originalInvoice->db->query("UPDATE aos_invoices_cstm SET verifactu_audit_log_c = '{$auditLogQuoted}' WHERE id_c = '{$originalInvoice->id}'");
        // === End Step 1.6 ===

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
        SugarApplication::appendSuccessMessage(AOS_InvoicesUtils::getStyledSuccessAlert($mod_strings['LBL_RECTIFIED_INVOICE_CREATED_SUCCESS']));

        // Redirect to EditView of the new rectified invoice
        SugarApplication::redirect("index.php?module=AOS_Invoices&action=EditView&record={$rectifiedInvoice->id}");
    }

    /**
     * Action to cancel an invoice in AEAT Verifactu system.
     * 
     * This action sends a cancellation record (RegistroAnulacion) to AEAT.
     *
     * 
     * Requirements:
     * - Invoice must be accepted by AEAT
     * - Invoice must not be a rectified invoice
     * - Invoice must have verifactu hash and previous hash
     */
    public function action_CancelInvoice()
    {
        global $mod_strings;

        // Get invoice ID from request
        $invoiceId = $_REQUEST['record'] ?? '';
        
        if (empty($invoiceId)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': No invoice ID specified');
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_ORIGINAL_INVOICE_NOT_SPECIFIED']));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }

        // Load the invoice
        $invoiceBean = BeanFactory::getBean('AOS_Invoices', $invoiceId);
        
        if (empty($invoiceBean->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice not found with ID: ' . $invoiceId);
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_INVOICE_NOT_FOUND']));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }

        // Validate invoice can be cancelled
        if ($invoiceBean->verifactu_aeat_status_c !== 'accepted') {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice not accepted by AEAT: ' . $invoiceId);
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_INVOICE_NOT_ACCEPTED_BY_AEAT']));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
            return;
        }

        // Send cancellation to AEAT
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        $result = AOS_InvoicesUtils::sendCancellationToAeat($invoiceBean);

        // Show result message
        if ($result['success']) {
            SugarApplication::appendSuccessMessage(AOS_InvoicesUtils::getStyledSuccessAlert($mod_strings['LBL_INVOICE_CANCELLED_SUCCESS'] . ' - CSV: ' . $result['csv']));
        } else {
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($result['message']));
        }

        // Redirect back to invoice
        SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
    }

    /**
     * Action to query AEAT Verifactu for registered invoices.
     */
    public function action_QueryAeatInvoices()
    {
        global $mod_strings;

        require_once 'custom/modules/AOS_Invoices/SticUtils.php';

        if (!AOS_InvoicesUtils::isVerifactuActivated()) {
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert(
                $mod_strings['LBL_VERIFACTU_NOT_ACTIVATED_SEND_ERROR']
            ));
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
            return;
        }

        if (!empty($_POST['query'])) {
            $year = $_POST['year'] ?? date('Y');
            $period = $_POST['period'] ?? date('m');
            $serieNumber = $_POST['serie_number'] ?? '';
            $dateFrom = $_POST['date_from'] ?? '';
            $dateTo = $_POST['date_to'] ?? '';
            $counterpartyNif = $_POST['counterparty_nif'] ?? '';
            $counterpartyName = $_POST['counterparty_name'] ?? '';
            $filterBySif = !empty($_POST['filter_by_sif']);

            if (!empty($dateFrom)) {
                $dateFrom = date('d-m-Y', strtotime($dateFrom));
            }
            if (!empty($dateTo)) {
                $dateTo = date('d-m-Y', strtotime($dateTo));
            }

            $result = AOS_InvoicesUtils::queryAeatInvoices(
                $year,
                $period,
                !empty($serieNumber) ? $serieNumber : null,
                !empty($dateFrom) ? $dateFrom : null,
                !empty($dateTo) ? $dateTo : null,
                !empty($counterpartyNif) ? $counterpartyNif : null,
                !empty($counterpartyName) ? $counterpartyName : null,
                $filterBySif,
            );

            $_SESSION['VERIFACTU_QUERY_RESULT'] = $result;
        }

        $this->view = 'queryaeatinvoices';
    }

    /**
     * Override massupdate to pre-check invoices before mass delete.
     * Invoices already sent to AEAT are skipped, and a summary warning is shown.
     * Only applies to explicit UID selection (not "entire" mode).
     */
    public function action_massupdate()
    {
        global $mod_strings;

        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        if (!AOS_InvoicesUtils::isVerifactuActivated()) {
            parent::action_massupdate();
            return;
        }

        $ids = [];

        if (!empty($_REQUEST['Delete'])) {
            if (!empty($_REQUEST['uid'])) {
                $ids = explode(',', $_REQUEST['uid']);
            } elseif (!empty($_REQUEST['entire'])) {
                $seed = BeanFactory::getBean('AOS_Invoices');
                require_once 'include/MassUpdate.php';
                $mass = new MassUpdate();
                $mass->setSugarBean($seed);
                if (!empty($_REQUEST['current_query_by_page'])) {
                    $mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_query_by_page']);
                }
                $query = $seed->create_new_list_query(
                    '',
                    $mass->where_clauses,
                    array(),
                    array(),
                    0,
                    '',
                    false,
                    $seed,
                    true,
                    true
                );
                $db = DBManagerFactory::getInstance();
                $result = $db->query($query, true);
                while ($row = $db->fetchByAssoc($result, false)) {
                    $ids[] = $row['id'];
                }
            }
        }

        if (!empty($ids)) {
            $allowedIds = [];
            $blockedInvoices = [];

            foreach ($ids as $id) {
                $id = trim($id);
                if (empty($id)) {
                    continue;
                }
                $invoice = BeanFactory::getBean('AOS_Invoices', $id);
                if (!empty($invoice->verifactu_aeat_status_c)
                    && in_array($invoice->verifactu_aeat_status_c, ['accepted', 'emitted'])) {
                    $invoiceLabel = !empty($invoice->number) ? $invoice->number : $id;
                    $blockedInvoices[] = '<a href="index.php?module=AOS_Invoices&action=DetailView&record=' . $id . '">' . $invoiceLabel . '</a>';
                } else {
                    $allowedIds[] = $id;
                }
            }

            if (!empty($blockedInvoices)) {
                $_SESSION['VERIFACTU_BLOCKED_DELETES'] = [
                    'deletedCount' => count($allowedIds),
                    'invoices' => $blockedInvoices,
                ];

                if (empty($allowedIds)) {
                    if (empty($mod_strings)) {
                        $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                    }
                    $errorMsg = sprintf(
                        $mod_strings['LBL_VERIFACTU_BLOCK_DELETE_ALL_BLOCKED'],
                        implode(', ', $blockedInvoices)
                    );
                    $styledMsg = '<div class="alert alert-warning" style="margin: 10px 0; padding: 12px; border-left: 4px solid #f0ad4e; background-color: #fcf8e3;">' . $errorMsg . '</div>';
                    SugarApplication::appendErrorMessage($styledMsg);
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=index');
                    return;
                }

                $_REQUEST['uid'] = implode(',', $allowedIds);
                $_POST['mass'] = $allowedIds;
                if (!empty($_REQUEST['entire'])) {
                    unset($_REQUEST['entire']);
                }
            }
        }

        parent::action_massupdate();

        if (!empty($_SESSION['VERIFACTU_BLOCKED_DELETES'])) {
            $blockedData = $_SESSION['VERIFACTU_BLOCKED_DELETES'];
            unset($_SESSION['VERIFACTU_BLOCKED_DELETES']);

            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            $errorMsg = sprintf(
                $mod_strings['LBL_VERIFACTU_BLOCK_DELETE_ALL_WARNING'],
                $blockedData['deletedCount'],
                implode(', ', $blockedData['invoices'])
            );
            $styledMsg = '<div class="alert alert-warning" style="margin: 10px 0; padding: 12px; border-left: 4px solid #f0ad4e; background-color: #fcf8e3;">' . $errorMsg . '</div>';
            SugarApplication::appendErrorMessage($styledMsg);
        }
    }

}
