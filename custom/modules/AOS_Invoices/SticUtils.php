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

require_once __DIR__ . '/../../../SticInclude/vendor/Verifactu-PHP/autoload.php';

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
     * @param array $records Array of RegistrationRecord objects to send
     * @param string $issuerNif Company's NIF/CIF
     * @param string $issuerName Company's name
     * @param string $certificatePath Path to the certificate file (.pfx)
     * @param string $certificatePassword Certificate password
     * @param bool $useProduction Whether to use production environment (false = pre-production)
     * @param ComputerSystem|null $system Computer system configuration (optional, will be created if null)
     * 
     * @return object The AEAT response object
     * @throws Exception If certificate is not found or sending fails
     */
    public static function sendToAeat(
        $records,
        $issuerNif,
        $issuerName,
        $certificatePath,
        $certificatePassword,
        $useProduction = false,
        $system = null
    ) {
        // Configure computer system if not provided
        if ($system === null) {
            $system = self::configureComputerSystem($issuerNif, $issuerName);
        }

        // Create taxpayer identifier
        $taxpayer = new FiscalIdentifier($issuerName, $issuerNif);
        
        // Create AEAT client
        $client = new AeatClient($system, $taxpayer);

        // Configure certificate
        if (!file_exists($certificatePath)) {
            throw new Exception("Certificate not found at: $certificatePath");
        }
        $client->setCertificate($certificatePath, $certificatePassword);

        // Configure environment (pre-production or production)
        $client->setProduction($useProduction);

        // Send records and return response
        $response = $client->send($records)->wait();
        
        return $response;
    }

    /**
     * Format AEAT response for display
     * 
     * @param object $response AEAT response object
     * @return string Formatted response text
     */
    public static function formatAeatResponse($response)
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
        $output .= "════════════════════════════════════════════════════════════════\n";
        
        return $output;
    }
}