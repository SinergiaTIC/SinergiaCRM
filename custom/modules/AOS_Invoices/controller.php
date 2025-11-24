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
            return;
        }
        
        if (
            empty($invoiceBean->status ?? '') ||
            empty($invoiceBean->verifactu_aeat_status_c ?? '') ||
            $invoiceBean->status !== 'emitted' ||
            $invoiceBean->verifactu_aeat_status_c === 'accepted') {
            
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice cannot be sent to AEAT. Status: ' . ($invoiceBean->status ?? 'N/A') . ', AEAT Status: ' . ($invoiceBean->verifactu_aeat_status_c ?? 'N/A'));
            SugarApplication::appendErrorMessage($mod_strings['LBL_INVOICE_INVALID_STATUSES_FOR_SEND_TO_AEAT']);
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);

        }

        
        // die('Sending invoice ' . $invoiceId . ' to AEAT...'); // Placeholder for actual implementation
        require_once 'custom/modules/AOS_Invoices/SticUtils.php';
        try {
            $response = AOS_InvoicesUtils::sendToAeat($invoiceBean);
            
            // Update invoice with AEAT response data
            if (isset($response->items[0])) {
                $item = $response->items[0];
                
                // Save the hash from the record
                if (isset($response->record) && isset($response->record->hash)) {
                    $invoiceBean->verifactu_hash_c = $response->record->hash;
                }
                
                // Save the previous hash from the record
                if (isset($response->record) && isset($response->record->previousHash)) {
                    $invoiceBean->verifactu_previous_hash_c = $response->record->previousHash;
                }
                
                // Save the CSV
                if (isset($response->csv)) {
                    $invoiceBean->verifactu_csv_c = $response->csv;
                }
                
                // Save the AEAT response (status and error if any)
                $aeatResponse = $item->status->value;
                if ($item->errorCode !== null) {
                    $aeatResponse .= ' [' . $item->errorCode . ']: ' . $item->errorDescription;
                }
                $invoiceBean->verifactu_aeat_response_c = substr($aeatResponse, 0, 255); // Truncate to field max length
                
                // Update status based on AEAT response
                if ($item->status->value === 'Correcto' || $item->status->value === 'AceptadoConErrores') {
                    $invoiceBean->verifactu_aeat_status_c = 'accepted';
                    
                    // Generate and save QR code URL only when invoice is accepted
                    if (isset($response->record)) {
                        $qrUrl = AOS_InvoicesUtils::generateQrCodeUrl($response->record, false, true);
                        $invoiceBean->verifactu_qr_data_c = $qrUrl;
                        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': QR URL generated: ' . $qrUrl);
                    }
                } else {
                    $invoiceBean->verifactu_aeat_status_c = 'rejected';
                }
                
                // Save submission date
                if (isset($response->submittedAt)) {
                    $invoiceBean->verifactu_submitted_at_c = $response->submittedAt->format('Y-m-d H:i:s');
                }
                
                // Save without triggering logic hooks
                $invoiceBean->save(false);
                
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice updated with AEAT response data');
            }
            
            // Format and display the response
            $debugInfo = $response->debugInfo ?? [];
            $formattedResponse = AOS_InvoicesUtils::formatAeatResponse($response, $debugInfo);
            
            // Log the response
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': AEAT Response: ' . $formattedResponse);
            
            // Display response in the browser
            echo '<pre>' . htmlspecialchars($formattedResponse) . '</pre>';
            echo '<br><a href="index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id . '">Volver a la factura</a>';
            die();
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error sending invoice to AEAT: ' . $e->getMessage());
            
            // Format and display the error
            $formattedError = AOS_InvoicesUtils::formatAeatError($e);
            
            // Display error in the browser
            echo '<pre>' . htmlspecialchars($formattedError) . '</pre>';
            echo '<br><a href="index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id . '">Volver a la factura</a>';
            die();
        }

    }
}
