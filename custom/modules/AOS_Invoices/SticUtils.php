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

// Autoloader para Verifactu-PHP y sus dependencias (UXML)
spl_autoload_register(function ($class) {
    // Configuración de namespaces y directorios
    $namespaces = [
        'josemmo\\Verifactu\\' => __DIR__ . '/../../../SticInclude/vendor/Verifactu-PHP/src/',
        'UXML\\' => __DIR__ . '/../../../SticInclude/vendor/uxml/src/',
    ];
    
    // Buscar en cada namespace configurado
    foreach ($namespaces as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // No es una clase de este namespace
            continue;
        }
        
        // Obtener el nombre relativo de la clase
        $relative_class = substr($class, $len);
        
        // Reemplazar el namespace con separadores de directorio
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        // Si el archivo existe, cargarlo
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

use DateTimeImmutable;
use josemmo\Verifactu\Models\ComputerSystem;
use josemmo\Verifactu\Models\Records\BreakdownDetails;
use josemmo\Verifactu\Models\Records\FiscalIdentifier;
use josemmo\Verifactu\Models\Records\InvoiceIdentifier;
use josemmo\Verifactu\Models\Records\InvoiceType;
use josemmo\Verifactu\Models\Records\OperationType;
use josemmo\Verifactu\Models\Records\RegimeType;
use josemmo\Verifactu\Models\Records\RegistrationRecord;
use josemmo\Verifactu\Models\Records\TaxType;
use josemmo\Verifactu\Services\AeatClient;
use josemmo\Verifactu\Services\QrGenerator;

/**
 * Utility class for AOS_Invoices Verifactu integration
 */
class AOS_InvoicesUtils
{
    /**
     * Create a registration record for an invoice
     * 
     * @param string $issuerNif Company's NIF/CIF
     * @param string $issuerName Company's name
     * @param string $invoiceNumber Invoice number
     * @param DateTimeImmutable $issueDate Invoice issue date
     * @param string $description Invoice description
     * @param array $breakdownDetails Array of breakdown details (tax breakdown)
     * @param string $totalTaxAmount Total tax amount
     * @param string $totalAmount Total invoice amount
     * @param InvoiceIdentifier|null $previousInvoiceId Previous invoice ID for chaining
     * @param string|null $previousHash Previous invoice hash for chaining
     * 
     * @return RegistrationRecord The created registration record
     */
    public static function createRegistrationRecord(
        $issuerNif,
        $issuerName,
        $invoiceNumber,
        $issueDate,
        $description,
        $breakdownDetails,
        $totalTaxAmount,
        $totalAmount,
        $previousInvoiceId = null,
        $previousHash = null
    ) {
        $record = new RegistrationRecord();

        // Invoice identifier
        $record->invoiceId = new InvoiceIdentifier();
        $record->invoiceId->issuerId = $issuerNif;
        $record->invoiceId->invoiceNumber = $invoiceNumber;
        $record->invoiceId->issueDate = $issueDate;

        // Basic invoice data
        $record->issuerName = $issuerName;
        $record->invoiceType = InvoiceType::Simplificada;
        $record->description = $description;

        // Tax breakdown
        $record->breakdown = $breakdownDetails;

        // Totals
        $record->totalTaxAmount = $totalTaxAmount;
        $record->totalAmount = $totalAmount;

        // Chaining (previous invoice reference)
        $record->previousInvoiceId = $previousInvoiceId;
        $record->previousHash = $previousHash;

        // Generate hash
        $record->hashedAt = new DateTimeImmutable();
        $record->hash = $record->calculateHash();

        // NOTE: Validation disabled due to incompatibility with Symfony Validator 3.4
        // Verifactu-PHP library requires Symfony 7.3+, but SinergiaCRM uses 3.4
        // Validation will be performed on the AEAT server
        // $record->validate();

        return $record;
    }

    /**
     * Create a breakdown detail entry
     * 
     * @param TaxType $taxType Tax type (IVA, IGIC, IPSI)
     * @param RegimeType $regimeType Regime type
     * @param OperationType $operationType Operation type
     * @param string $baseAmount Base amount (before tax)
     * @param string $taxRate Tax rate percentage
     * @param string $taxAmount Tax amount
     * 
     * @return BreakdownDetails The created breakdown detail
     */
    public static function createBreakdownDetail(
        $taxType,
        $regimeType,
        $operationType,
        $baseAmount,
        $taxRate,
        $taxAmount
    ) {
        $breakdown = new BreakdownDetails();
        $breakdown->taxType = $taxType;
        $breakdown->regimeType = $regimeType;
        $breakdown->operationType = $operationType;
        $breakdown->baseAmount = $baseAmount;
        $breakdown->taxRate = $taxRate;
        $breakdown->taxAmount = $taxAmount;

        return $breakdown;
    }

    /**
     * Configure the Computer System (SIF - Sistema Informático de Facturación)
     * 
     * @param string $vendorNif Vendor's NIF/CIF
     * @param string $vendorName Vendor's name
     * @param string $systemName System name
     * @param string $systemId System ID
     * @param string $systemVersion System version
     * @param string $installationNumber Installation number
     * @param bool $onlySupportsVerifactu Whether the system only supports Verifactu
     * @param bool $supportsMultipleTaxpayers Whether the system supports multiple taxpayers
     * @param bool $hasMultipleTaxpayers Whether the system has multiple taxpayers
     * 
     * @return ComputerSystem The configured computer system
     */
    public static function configureComputerSystem(
        $vendorNif,
        $vendorName,
        $systemName = 'SinergiaCRM Billing System',
        $systemId = 'SF',
        $systemVersion = '1.0.0',
        $installationNumber = '001',
        $onlySupportsVerifactu = true,
        $supportsMultipleTaxpayers = false,
        $hasMultipleTaxpayers = false
    ) {
        $system = new ComputerSystem();
        $system->vendorName = $vendorName;
        $system->vendorNif = $vendorNif;
        $system->name = $systemName;
        $system->id = $systemId;
        $system->version = $systemVersion;
        $system->installationNumber = $installationNumber;
        $system->onlySupportsVerifactu = $onlySupportsVerifactu;
        $system->supportsMultipleTaxpayers = $supportsMultipleTaxpayers;
        $system->hasMultipleTaxpayers = $hasMultipleTaxpayers;

        // NOTE: Validation disabled due to incompatibility with Symfony Validator 3.4
        // $system->validate();

        return $system;
    }

    /**
     * Send invoice records to AEAT
     * 
     * @param AOS_Invoices $invoiceBean Invoice bean object
     * 
     * @return object The AEAT response object
     * @throws Exception If certificate is not found or sending fails
     */
    public static function sendToAeat($invoiceBean)
    {
        // Configuration constants
       $certificatePath = 'CHAMIZO_GONZALEZ_JUAN___07224982S.p12';
       $certificatePassword = '01romera';
       $useProduction = false; // false = pre-production, true = production
       $issuerNif = '07224982S'; // Company's NIF/CIF - debe ser un NIF español válido
       $issuerName = 'CHAMIZO GONZALEZ JUAN'; // Company's name
       $systemName = 'SinergiaCRM Billing System';
       $systemId = 'SC'; // Changed to avoid conflicts with previous registrations
       $systemVersion = '1.0.0';
       $installationNumber = '001';

        // Configure computer system
        $system = self::configureComputerSystem(
            $issuerNif,
            $issuerName,
            $systemName,
            $systemId,
            $systemVersion,
            $installationNumber
        );

        // Create taxpayer identifier
        $taxpayer = new FiscalIdentifier($issuerName, $issuerNif);

        // Create AEAT client
        $client = new AeatClient($system, $taxpayer);

        // Configure certificate
        if (!file_exists($certificatePath)) {
            throw new Exception("Certificate not found at: " . $certificatePath);
        }
        $client->setCertificate($certificatePath, $certificatePassword);

        // Configure environment (pre-production or production)
        $client->setProduction($useProduction);

        // Extract invoice data from bean and create registration record
        $invoiceNumber = $invoiceBean->number;
        $ldate='2025-11-23';
        $issueDate = new DateTimeImmutable($ldate);
        $description = $invoiceBean->name;
        
        // Format amounts as strings with exactly 2 decimals (required by AEAT)
        $baseAmount = number_format((float)$invoiceBean->subtotal_amount, 2, '.', '');
        $totalTaxAmount = number_format((float)$invoiceBean->tax_amount, 2, '.', '');
        
        // Calculate correct total (Base + Tax) - AEAT requires this to be exact
        $totalAmount = number_format((float)$baseAmount + (float)$totalTaxAmount, 2, '.', '');
        
        // Log the values for debugging
        $invoiceTotal = number_format((float)$invoiceBean->total_amt, 2, '.', '');
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice amounts - Base: ' . $baseAmount . ', Tax: ' . $totalTaxAmount . ', Invoice Total: ' . $invoiceTotal . ', Calculated Total: ' . $totalAmount);
        
        // Warn if invoice total doesn't match calculated total
        if ($invoiceTotal !== $totalAmount) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice total (' . $invoiceTotal . ') differs from calculated total (' . $totalAmount . '). Using calculated total for AEAT.');
        }
        
        // Get previous invoice for chaining
        $previousInvoiceId = null;
        $previousHash = null;
        $previousInvoice = self::getPreviousInvoice($invoiceBean->id);
        
        if ($previousInvoice) {
            $previousInvoiceId = new InvoiceIdentifier();
            $previousInvoiceId->issuerId = $issuerNif;
            $previousInvoiceId->invoiceNumber = $previousInvoice->number;
            $previousInvoiceId->issueDate = new DateTimeImmutable($previousInvoice->invoice_date ?? '2025-11-23');
            $previousHash = $previousInvoice->verifactu_hash_c ?? null;
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Chaining to previous invoice: ' . $previousInvoice->number . ' (Hash: ' . ($previousHash ?? 'N/A') . ')');
        } else {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': No previous invoice found, this will be the first in the chain');
        }
        
        // Create breakdown details (this is a simplified example)
        // You may need to adjust this based on your actual tax structure
        $breakdownDetails = [
            self::createBreakdownDetail(
                TaxType::IVA,
                RegimeType::C01,
                OperationType::Subject,
                $baseAmount,
                '21.00', // Tax rate with 2 decimals
                $totalTaxAmount
            )
        ];

        // Create registration record
        $record = self::createRegistrationRecord(
            $issuerNif,
            $issuerName,
            $invoiceNumber,
            $issueDate,
            $description,
            $breakdownDetails,
            $totalTaxAmount,
            $totalAmount,
            $previousInvoiceId,
            $previousHash
        );

        // Send records and get response (with detailed error handling)
        try {
            $response = $client->send([$record])->wait();
            
            // Add debug info to response
            $response->debugInfo = [
                'baseAmount' => $baseAmount,
                'taxAmount' => $totalTaxAmount,
                'totalAmount' => $totalAmount,
                'invoiceTotal' => $invoiceTotal,
            ];
            
            // Add the record to the response so we can access the hash
            $response->record = $record;
            
            return $response;
            
        } catch (Exception $e) {
            // Log detailed error information
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': AEAT Error Details:');
            $GLOBALS['log']->error('  Message: ' . $e->getMessage());
            $GLOBALS['log']->error('  Type: ' . get_class($e));
            $GLOBALS['log']->error('  File: ' . $e->getFile() . ':' . $e->getLine());
            
            // Try to get more context from the previous invoice lookup
            $GLOBALS['log']->error('  Previous Invoice: ' . ($previousInvoice ? $previousInvoice->number : 'None'));
            $GLOBALS['log']->error('  Previous Hash: ' . ($previousHash ?? 'None'));
            $GLOBALS['log']->error('  Current Invoice: ' . $invoiceNumber);
            $GLOBALS['log']->error('  Record Hash: ' . $record->hash);
            
            // Re-throw the exception
            throw $e;
        }
    }

    /**
     * Get the previous invoice that was successfully sent to AEAT
     * 
     * @param string $currentInvoiceId Current invoice ID to exclude from search
     * @return stdClass|null Previous invoice object or null if none found
     */
    private static function getPreviousInvoice($currentInvoiceId)
    {
        global $db;
        
        try {
            // Query to find the most recent invoice that was sent to AEAT
            // and has a verifactu hash stored (custom fields are in aos_invoices_cstm table)
            // We include 'accepted' and other statuses because AEAT considers all sent invoices
            // for chaining purposes, even if they had errors
            $query = "
                SELECT 
                    i.id, 
                    i.number, 
                    i.invoice_date, 
                    c.verifactu_hash_c,
                    c.verifactu_aeat_status_c
                FROM aos_invoices i
                INNER JOIN aos_invoices_cstm c ON i.id = c.id_c
                WHERE i.deleted = 0
                  AND i.id != '" . $db->quote($currentInvoiceId) . "'
                  AND c.verifactu_hash_c IS NOT NULL
                  AND c.verifactu_hash_c != ''
                ORDER BY i.invoice_date DESC, i.number DESC
                LIMIT 1
            ";
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Query: ' . $query);
            
            $result = $db->query($query);
            if ($result && $row = $db->fetchByAssoc($result)) {
                // Create a simple object with the necessary data
                $invoice = new stdClass();
                $invoice->id = $row['id'];
                $invoice->number = $row['number'];
                $invoice->invoice_date = $row['invoice_date'];
                $invoice->verifactu_hash_c = $row['verifactu_hash_c'];
                
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found previous invoice: ' . $invoice->number);
                
                return $invoice;
            }
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': No previous invoice found');
            return null;
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error querying previous invoice: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format AEAT response for display
     * 
     * @param object $response AEAT response object
     * @param array $debugInfo Optional debug information to display
     * @return string Formatted response text
     */
    public static function formatAeatResponse($response, $debugInfo = [])
    {
        $output = "\n";
        $output .= "════════════════════════════════════════════════════════════════\n";
        $output .= "INVOICE SENT SUCCESSFULLY\n";
        $output .= "════════════════════════════════════════════════════════════════\n";
        $output .= "CSV: " . ($response->csv ?? 'N/A') . "\n";
        $output .= "Status: " . $response->status->value . "\n";
        $output .= "Wait time: {$response->waitSeconds}s\n";
        
        if ($response->submittedAt !== null) {
            $output .= "Submission date: " . $response->submittedAt->format('d-m-Y H:i:s') . "\n";
        }
        
        if (!empty($debugInfo)) {
            $output .= "\nSent values:\n";
            $output .= "  Base Amount: " . ($debugInfo['baseAmount'] ?? 'N/A') . "\n";
            $output .= "  Tax Amount: " . ($debugInfo['taxAmount'] ?? 'N/A') . "\n";
            $output .= "  Total Amount (sent to AEAT): " . ($debugInfo['totalAmount'] ?? 'N/A') . "\n";
            if (isset($debugInfo['invoiceTotal']) && $debugInfo['invoiceTotal'] !== $debugInfo['totalAmount']) {
                $output .= "  Invoice Total (from CRM): " . $debugInfo['invoiceTotal'] . " (differs from sent value)\n";
            }
        }
        
        $output .= "\nRecord details:\n";
        foreach ($response->items as $index => $item) {
            $output .= "  Invoice " . ($index + 1) . ": {$item->invoiceId->invoiceNumber}\n";
            $output .= "    - Status: {$item->status->value}\n";
            if ($item->errorCode !== null) {
                $output .= "    - Error [{$item->errorCode}]: {$item->errorDescription}\n";
            }
        }
        
        $output .= "════════════════════════════════════════════════════════════════\n";
        
        return $output;
    }

    /**
     * Format AEAT error for display
     * 
     * @param Exception $exception Exception object
     * @return string Formatted error text
     */
    public static function formatAeatError($exception)
    {
        $output = "\n";
        $output .= "════════════════════════════════════════════════════════════════\n";
        $output .= "ERROR SENDING INVOICE\n";
        $output .= "════════════════════════════════════════════════════════════════\n";
        $output .= "Message: " . $exception->getMessage() . "\n";
        $output .= "Exception Type: " . get_class($exception) . "\n";
        $output .= "File: " . $exception->getFile() . "\n";
        $output .= "Line: " . $exception->getLine() . "\n";
        $output .= "\nStack Trace:\n" . $exception->getTraceAsString() . "\n";
        
        // Show previous exception if exists
        if ($exception->getPrevious()) {
            $prev = $exception->getPrevious();
            $output .= "\nPrevious Exception:\n";
            $output .= "Message: " . $prev->getMessage() . "\n";
            $output .= "Type: " . get_class($prev) . "\n";
        }
        
        $output .= "════════════════════════════════════════════════════════════════\n";
        
        return $output;
    }

    /**
     * Generate QR code URL for invoice validation
     * 
     * @param RegistrationRecord $record Registration record with invoice data
     * @param bool $useProduction Whether to use production environment
     * @param bool $onlineMode Whether to use online mode (VeriFactu)
     * 
     * @return string QR code URL for AEAT validation
     */
    public static function generateQrCodeUrl($record, $useProduction = false, $onlineMode = true)
    {
        $qrGenerator = new QrGenerator();
        $qrGenerator->setProduction($useProduction);
        $qrGenerator->setOnlineMode($onlineMode);
        
        return $qrGenerator->fromRegistrationRecord($record);
    }
}