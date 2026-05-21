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
        global $sugar_config, $mod_strings;

        // === Step 1.3: Clear number on duplicate to avoid AEAT duplicate error ===
        // When duplicating, SuiteCRM copies all fields including number
        // We must reset it so a new number is generated at send time
        $isDuplicate = (!empty($_REQUEST['mass_duplicate']) && $_REQUEST['mass_duplicate'] == '1') 
            || (!empty($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === 'true');
        
        if ($isDuplicate && !empty($bean->number)) {
            $GLOBALS['log']->debug(__METHOD__ . ': Clearing number on duplicate. Original number was: ' . $bean->number);
            $bean->number = '';
        }
        // === End Step 1.3 ===

        // === Legacy mode: Generate invoice number on save if Verifactu is not activated ===
        // When VERIFACTU_ACTIVATED = false, numbers are not assigned at AEAT send time
        // So we need to generate them here on save to have a number visible in the list
        // Generate for new invoices AND for duplicates (number was cleared above)
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        $isVerifactuActivated = AOS_InvoicesUtils::isVerifactuActivated();
        $isNew = empty($bean->fetched_row['id']);
        
        if (!$isVerifactuActivated && empty($bean->number) && ($isNew || $isDuplicate)) {
            $generatedNumber = AOS_InvoicesUtils::generateNextInvoiceNumber($bean->stic_invoice_type_c, $bean, null, false);
            if ($generatedNumber) {
                $bean->number = $generatedNumber;
                $GLOBALS['log']->debug(__METHOD__ . ': Generated invoice number in legacy mode: ' . $generatedNumber);
            }
        }
        // === End Legacy mode ===

        // === Validate customer identification number (DNI/NIF/CIF) ===
        if (!$isDuplicate && empty($bean->fetched_row['id'])) {
            if (!empty($bean->billing_account_id)) {
                $account = BeanFactory::getBean('Accounts', $bean->billing_account_id);
                if (!empty($account->id) && empty($account->stic_identification_number_c)) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Account ' . $bean->billing_account_id . ' has no identification number.');
                    SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_CUSTOMER_IDENTIFICATION_NUMBER_MISSING']));
                    $bean->in_save = false;
                }
            } elseif (!empty($bean->billing_contact_id)) {
                $contact = BeanFactory::getBean('Contacts', $bean->billing_contact_id);
                if (!empty($contact->id) && empty($contact->stic_identification_number_c)) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Contact ' . $bean->billing_contact_id . ' has no identification number.');
                    SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_CUSTOMER_IDENTIFICATION_NUMBER_MISSING']));
                    $bean->in_save = false;
                }
            }
        }
        // Clear address fields if no customer is selected
        if (empty($bean->billing_account_id) && empty($bean->billing_contact_id)) {
            $bean->billing_address_street = '';
            $bean->billing_address_city = '';
            $bean->billing_address_state = '';
            $bean->billing_address_postalcode = '';
            $bean->billing_address_country = '';
            $bean->shipping_address_street = '';
            $bean->shipping_address_city = '';
            $bean->shipping_address_state = '';
            $bean->shipping_address_postalcode = '';
            $bean->shipping_address_country = '';
        }
        // === End customer identification number validation ===

        // === Block status change from draft to non-emitted ===
        $isNewRecord = empty($bean->fetched_row['id']);
        $isCurrentlyDraft = !empty($bean->fetched_row['status']) && $bean->fetched_row['status'] === 'draft';
        if ($bean->status !== 'draft' && $bean->status !== 'emitted' && ($isNewRecord || $isCurrentlyDraft)) {
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($mod_strings['LBL_VERIFACTU_STATUS_DRAFT_TO_OTHER_ERROR']));
            $bean->status = 'draft';
            $bean->in_save = false;
            if (!$isNewRecord) {
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView&record=' . $bean->id);
                die();
            }
        }
        // === End block status change ===

        // === Step 1.1: Block edition of invoices accepted by AEAT ===
        // If the invoice is already accepted by AEAT, only non-tax fields can be edited
        if (!empty($bean->fetched_row['verifactu_aeat_status_c']) && 
            $bean->fetched_row['verifactu_aeat_status_c'] === 'accepted') {
            
            // Check if it's a duplicate or creating a rectified invoice (both are allowed)
            $isDuplicate = (!empty($_REQUEST['mass_duplicate']) && $_REQUEST['mass_duplicate'] == '1')
                || (!empty($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === 'true')
                // Allow if this is a new rectified invoice (action=CreateRectifiedInvoice)
                || ($_REQUEST['action'] === 'CreateRectifiedInvoice')
                // Allow if this is a cancellation operation
                || ($_REQUEST['action'] === 'CancelInvoice')
                // Allow if bean has a flag indicating cancellation in progress
                || (!empty($bean->_is_cancellation) && $bean->_is_cancellation === true);
            
            if (!$isDuplicate) {
                // List of NON-tax fields that CAN be edited
                $allowedFields = array(
                    'description', 
                    'assigned_user_id', 
                    'notes'
                );
                
                // List of tax fields that CANNOT be edited
                $protectedFields = array(
                    'number', 'stic_invoice_type_c', 'invoice_date', 'due_date',
                    'billing_account_id', 'billing_account', 'billing_contact_id', 'billing_contact',
                    'billing_address_street', 'billing_address_city', 'billing_address_state', 
                    'billing_address_postalcode', 'billing_address_country',
                    'shipping_address_street', 'shipping_address_city', 'shipping_address_state',
                    'shipping_address_postalcode', 'shipping_address_country',
                    'subtotal_amount', 'discount_amount', 'tax_amount', 'shipping_amount', 
                    'total_amount', 'total_amt', 'shipping_tax', 'shipping_tax_amt',
                    'currency_id', 'name',
                    // Campos Verifactu
                    'verifactu_hash_c', 'verifactu_previous_hash_c', 'verifactu_check_url_c',
                    'verifactu_aeat_status_c', 'verifactu_aeat_response_c', 'verifactu_cancel_id_c',
                    'verifactu_csv_c', 'verifactu_submitted_at_c', 'verifactu_cancel_hash_c',
                    'verifactu_audit_log_c', 'verifactu_is_rectified_c', 'verifactu_rectified_type_c',
                    'verifactu_rectified_base_c', 'verifactu_rectified_date_c'
                );
                
                // Detect which fields have been modified
                $modifiedFields = array();
                foreach ($protectedFields as $field) {
                    // Compare current value with original value
                    $currentValue = isset($bean->$field) ? $bean->$field : null;
                    $originalValue = isset($bean->fetched_row[$field]) ? $bean->fetched_row[$field] : null;
                    
                    // Normalize for comparison
                    $currentNormalized = ($currentValue === null || $currentValue === '') ? null : $currentValue;
                    $originalNormalized = ($originalValue === null || $originalValue === '') ? null : $originalValue;
                    
                    if ($currentNormalized !== $originalNormalized) {
                        $modifiedFields[] = $field;
                    }
                }
                
                // If any tax field was modified, block the save
                if (!empty($modifiedFields)) {
// Load mod_strings if not already loaded
                    if (empty($mod_strings)) {
                        $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                    }
                    
                    $errorMsg = $mod_strings['LBL_VERIFACTU_BLOCK_EDIT_ERROR'];

                    SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($errorMsg));
                    
// Redirect to detail view
                    if (!empty($bean->id)) {
                        SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $bean->id);
                    }
                    die();
                }
            }
        }
        // === End Step 1.1 ===

        // === Step 2.1: Validate chronological order by series ===
        // Only validate if Verifactu is active (not pending status) and not a duplicate
        if (!$isDuplicate && !empty($bean->verifactu_aeat_status_c) && $bean->verifactu_aeat_status_c !== 'pending') {
            require_once 'custom/modules/AOS_Invoices/SticUtils.php';
            $validationResult = AOS_InvoicesUtils::validateChronologicalOrder($bean);
            
            if ($validationResult !== true) {
                // Load mod_strings if not already loaded
                if (empty($mod_strings)) {
                    $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                }
                
                // Format the error message with actual values
                $currentDateFormatted = date('d/m/Y', strtotime($bean->invoice_date));
                $seriesName = $bean->stic_invoice_type_c;
                $lastDateFormatted = ''; // Extract from the result if available
                
                // For now, use a simpler message
                $errorMsg = $mod_strings['LBL_VERIFACTU_DATE_BEFORE_LAST']
                    ?? "La fecha de expedición ({$currentDateFormatted}) es anterior a la última factura emitida de la serie {$seriesName}.";

                SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($errorMsg));
                
                // Redirect to detail view
                if (!empty($bean->id)) {
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $bean->id);
                }
                die();
            }
        }
        // === End Step 2.1 ===

        // === Step 2.3: Validate series type consistency ===
        // Only validate if not a duplicate
        $GLOBALS['log']->debug(__METHOD__ . ': Step 2.3 - isDuplicate=' . ($isDuplicate ? 'true' : 'false') . ', isRectified=' . ($bean->verifactu_is_rectified_c ?? 'null') . ', series=' . ($bean->stic_invoice_type_c ?? 'null'));
        
        if (!$isDuplicate) {
            require_once 'custom/modules/AOS_Invoices/SticUtils.php';
            $seriesValidationResult = AOS_InvoicesUtils::validateSeriesType($bean);
            
            if ($seriesValidationResult !== true) {
                $GLOBALS['log']->error(__METHOD__ . ': Step 2.3 - Validation failed: ' . $seriesValidationResult);
                if (empty($mod_strings)) {
                    $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                }

                SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($seriesValidationResult));
                
                // Redirect to detail view
                if (!empty($bean->id)) {
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $bean->id);
                }
                die();
            }
        }
        // === End Step 2.3 ===

        // === Step 2.5: Validate max length (60 chars) for series + number ===
        if (!empty($bean->stic_invoice_type_c) && !empty($bean->number)) {
            $series = $bean->stic_invoice_type_c;
            $number = $bean->number;
            $combinedLength = strlen($series) + strlen($number);
            
            if ($combinedLength > 60) {
                $GLOBALS['log']->error(__METHOD__ . ': Step 2.5 - Combined length exceeds 60 characters: ' . $combinedLength);

                if (empty($mod_strings)) {
                    $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                }

                $errorMsg = $mod_strings['LBL_VERIFACTU_SERIES_NUMBER_TOO_LONG'];

                SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($errorMsg));
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView&record=' . $bean->id);
                die();
            }
        }
        // === End Step 2.5 ===

        // === Step 2.8: Regenerate number when series changes ===
        // If the invoice has NOT been sent to AEAT (pending), allow series change and reset number
        // If the invoice HAS been sent to AEAT (not pending), block series change
        if (!$isDuplicate && !empty($bean->fetched_row['id'])) {
            $currentSeries = $bean->stic_invoice_type_c;
            $originalSeries = $bean->fetched_row['stic_invoice_type_c'] ?? null;
            
            $aeatStatus = $bean->verifactu_aeat_status_c ?? 'pending';
            $hasBeenSent = !empty($bean->verifactu_submitted_at_c);
            
            $GLOBALS['log']->debug(__METHOD__ . ': Step 2.8 - Current series: ' . ($currentSeries ?? 'null') . ', Original series: ' . ($originalSeries ?? 'null') . ', AEAT status: ' . $aeatStatus . ', Submitted: ' . ($hasBeenSent ? 'yes' : 'no'));
            
            if (!empty($originalSeries) && $currentSeries !== $originalSeries) {
                if ($hasBeenSent || $aeatStatus === 'accepted' || $aeatStatus === 'emitted') {
                    // Invoice has been sent to AEAT - block series change
                    if (empty($mod_strings)) {
                        $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                    }

                    $errorMsg = $mod_strings['LBL_VERIFACTU_SERIES_CHANGE_BLOCKED'];

                    SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($errorMsg));
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView&record=' . $bean->id);
                    die();
                } else {
                    // Invoice not yet sent - allow series change and reset number
                    // The number will be regenerated at send time (Step 1.3)
                    if (!empty($bean->number) && strpos($bean->number, $mod_strings['LBL_VERIFACTU_DRAFT_NUMBER_PREFIX']) !== 0) {
                        $GLOBALS['log']->info(__METHOD__ . ': Step 2.8 - Series changed from "' . $originalSeries . '" to "' . $currentSeries . '". Resetting number for regeneration at send time.');
                        $bean->number = '';
                    }
                }
            }
        }
        // === End Step 2.8 ===

        // If duplicating a record, set status to 'draft' and clear Verifactu fields
        if (
            (!empty($_REQUEST['mass_duplicate']) && $_REQUEST['mass_duplicate'] == '1') // for mass duplicate
            || (!empty($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === 'true') // for single duplicate
            ) {
            $bean->status = 'draft';
            // Clear all Verifactu-related fields
            $bean->verifactu_hash_c = null;
            $bean->verifactu_previous_hash_c = null;
            $bean->verifactu_check_url_c = null;
            $bean->verifactu_aeat_status_c = 'pending';
            $bean->verifactu_aeat_response_c = null;
            $bean->verifactu_cancel_id_c = null;
            $bean->verifactu_csv_c = null;
            $bean->verifactu_submitted_at_c = null;
            // Also clear rectified invoice fields
            $bean->verifactu_is_rectified_c = 0;
            $bean->verifactu_rectified_type_c = null;
            $bean->verifactu_rectified_base_c = null;
            $bean->verifactu_cancel_id_c = null;
            $bean->verifactu_rectified_date_c = null;
            $bean->description = null;
        }

        // Validate rectified invoice data
        if (!empty($bean->verifactu_is_rectified_c)) {
            global $mod_strings, $app_list_strings;
            
            // Set default values for rectified invoice fields
            $bean->verifactu_rectified_type_c =  'S';
            $bean->verifactu_rectified_base_c =  'R1';

            // Load module strings if not loaded
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            
            $errors = [];
            
            // Validate required fields for rectified invoices
            if (empty($bean->verifactu_rectified_type_c)) {
                $errors[] = $mod_strings['LBL_FIELD_RECTIFIED_TYPE'];
            }
            if (empty($bean->verifactu_rectified_base_c)) {
                $errors[] = $mod_strings['LBL_FIELD_RECTIFIED_BASE'];
            }
            if (empty($bean->verifactu_cancel_id_c)) {
                $errors[] = $mod_strings['LBL_VERIFACTU_CANCEL_NAME'];
            }
            if (empty($bean->verifactu_rectified_date_c)) {
                $errors[] = $mod_strings['LBL_FIELD_RECTIFIED_DATE'];
            }
            
            // If there are validation errors, prevent save and show message
            if (!empty($errors)) {
                $errorMsg = $mod_strings['LBL_RECTIFIED_INVOICE_VALIDATION_ERROR'];
                $errorMsg .= '<br><strong>' . $mod_strings['LBL_MISSING_FIELDS'] . ':</strong> ' . implode(', ', $errors);

                SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($errorMsg));
                
                // Redirect back to edit view
                if (!empty($bean->id)) {
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView&record=' . $bean->id);
                } else {
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=EditView');
                }
                die();
            }
        }

        // If the invoice type field is empty, set a default value based on whether it's a rectified invoice
        if (empty($bean->stic_invoice_type_c)) {
            if (!empty($sugar_config['aos']['invoices']['series']) && is_array($sugar_config['aos']['invoices']['series'])) {
                // === Step 2.6: Validate series uniqueness ===
                require_once 'custom/modules/AOS_Invoices/SticUtils.php';
                AOS_InvoicesUtils::validateSeriesUniqueness();
                // === End Step 2.6 ===

                // Check if this is a rectified invoice
                $isRectified = !empty($bean->verifactu_is_rectified_c);

                // Find first series matching the invoice type
                foreach ($sugar_config['aos']['invoices']['series'] as $seriesName => $seriesConfig) {
                    $seriesIsRectified = !empty($seriesConfig['isRectified']);

                    // If invoice is rectified, find first rectified series
                    // If invoice is not rectified, find first non-rectified series
                    if ($isRectified === $seriesIsRectified) {
                        $bean->stic_invoice_type_c = $seriesName;
                        break;
                    }
                }
            }
        }

        // Auto-generate name if not provided: <Organization/Person name> - <Date/Time>
        if (empty($bean->name)) {
            $clientName = '';
            
            // Get Organization name
            if (!empty($bean->billing_account)) {
                $clientName = $bean->billing_account;
            }
            // Or get Person name
            elseif (!empty($bean->billing_contact)) {
                $clientName = $bean->billing_contact;
            }
            
            if (!empty($clientName)) {
                $dateTime = date('Y-m-d H:i');
                $bean->name = $clientName . ' - ' . $dateTime;
            }
        }

        // Generate the next invoice number based on the invoice type (series) - DEPRECATED by Step 1.3
        // Now numbers are assigned at AEAT send time, not at creation
        // if (empty($bean->number) && !empty($bean->stic_invoice_type_c)) {
        //     require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        //     $bean->number = AOS_InvoicesUtils::generateNextInvoiceNumber($bean->stic_invoice_type_c, $bean);
        // }
    }

    
    public function after_save($bean, $event, $arguments)
    {
        // Check if Verifactu is activated - if not, skip all AEAT logic (legacy mode)
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        if (!AOS_InvoicesUtils::isVerifactuActivated()) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Verifactu not activated (legacy mode), skipping AEAT send.');
            return;
        }

        // check if status is 'emitted'
        if ($bean->status !== 'emitted') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} status is not 'emitted', skipping AEAT send.");
            return;
        }

        // check if already sent and accepted (rejected invoices can be retried)
        if (!empty($bean->verifactu_aeat_status_c) && $bean->verifactu_aeat_status_c === 'accepted') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} has already been sent to AEAT, skipping resend.");
            return;
        }

        // check if status changed to 'emitted' (only send on status change)
        // Allow resend if previous AEAT status was 'rejected'
        if (!empty($bean->fetched_row['status']) && $bean->fetched_row['status'] === 'emitted' && $bean->verifactu_aeat_status_c !== 'rejected') {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Invoice with id {$bean->id} was already in 'emitted' status, skipping send.");
            return;
        }

        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Sending invoice with id {$bean->id} to AEAT via Verifactu...");

        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        AOS_InvoicesUtils::sendToAeat($bean);
    }

// === Step 1.1: Block deletion of issued/accepted invoices ===
    public function before_delete($bean, $event, $arguments)
    {
        global $mod_strings;

        if (!empty($bean->verifactu_aeat_status_c) &&
            in_array($bean->verifactu_aeat_status_c, array('accepted', 'emitted'))) {

            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }

            $invoiceInfo = !empty($bean->number) ? $bean->number : $bean->id;
            $errorMsg = sprintf(
                $mod_strings['LBL_VERIFACTU_BLOCK_DELETE_ALL_ERROR'],
                $invoiceInfo
            );

            $styledMsg = '<div class="alert alert-danger" style="margin: 10px 0; padding: 12px; border-left: 4px solid #d9534f; background-color: #f2dede;">' . $errorMsg . '</div>';

            if (!empty($_REQUEST['ajax'])) {
                echo json_encode(['success' => false, 'message' => $errorMsg]);
                exit;
            }

            SugarApplication::appendErrorMessage($styledMsg);
            header('Location: index.php?module=AOS_Invoices&action=index');
            exit;
        }
    }
    // === End Step 1.1 ===
}
