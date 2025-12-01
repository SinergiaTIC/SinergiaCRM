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
$loader = require __DIR__ . '/../../../SticInclude/vendor/autoload.php';
if ($loader instanceof \Composer\Autoload\ClassLoader) {
    $loader->unregister();
    $loader->register(true); // Prepend to ensure our dependencies (Symfony Validator 7.3) are loaded instead of the CRM's old ones
}

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
     * @param string|null $customerNif Customer's NIF/CIF
     * @param string|null $customerName Customer's name
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
        $previousHash = null,
        $customerNif = null,
        $customerName = null
    ) {
        $record = new RegistrationRecord();

        // Invoice identifier
        $record->invoiceId = new InvoiceIdentifier();
        $record->invoiceId->issuerId = $issuerNif;
        $record->invoiceId->invoiceNumber = $invoiceNumber;
        $record->invoiceId->issueDate = $issueDate;

        // Basic invoice data
        $record->issuerName = $issuerName;
        $record->description = $description;

        // Determine invoice type and recipients
        if (!empty($customerNif) && !empty($customerName)) {
            $record->invoiceType = InvoiceType::Factura; // F1 - Completa
            $recipient = new FiscalIdentifier($customerName, $customerNif);
            $record->recipients = [$recipient];
        } else {
            $record->invoiceType = InvoiceType::Simplificada; // F2 - Simplificada
        }

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
        global $mod_strings, $timedate, $sugar_config;
        if (
            empty($invoiceBean->status ?? '') ||
            empty($invoiceBean->verifactu_aeat_status_c ?? '') ||
            $invoiceBean->status !== 'emitted' ||
            $invoiceBean->verifactu_aeat_status_c === 'accepted') {

            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice cannot be sent to AEAT. Status: ' . ($invoiceBean->status ?? 'N/A') . ', AEAT Status: ' . ($invoiceBean->verifactu_aeat_status_c ?? 'N/A'));
            SugarApplication::appendErrorMessage($mod_strings['LBL_INVOICE_INVALID_STATUSES_FOR_SEND_TO_AEAT']);
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
            return;
        }

        try {
            // Get needing settings from stic_Setting module
            require_once 'modules/stic_Settings/Utils.php';
            $certificatePassword = stic_SettingsUtils::getSetting('GENERAL_CERTIFICATE_PASSWORD');
            $certificateType = stic_SettingsUtils::getSetting('GENERAL_CERTIFICATE_ENTITY_SEAL');
            $issuerNif = stic_SettingsUtils::getSetting('GENERAL_ORGANIZATION_ID');
            $issuerName = stic_SettingsUtils::getSetting('GENERAL_ORGANIZATION_NAME');

            if (empty($certificatePassword) || empty($issuerNif) || empty($issuerName) || $certificateType === null) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Missing required settings: certificate password, organization NIF or organization name.');
                SugarApplication::appendErrorMessage("<div class=\"alert alert-danger\">{$mod_strings['LBL_MISSING_SETTINGS']}</div>");
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
            }

            $useProduction = false; // false = pre-production, true = production
            $systemName = 'SinergiaCRM';
            $systemId = 'SC';
            $systemVersion = $sugar_config['sinergiacrm_version'] ?? 'unknown';
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

            // Configure certificate type (Entity Seal vs Personal)
            $client->setEntitySeal((bool) $certificateType);

            // Configure certificate
            $encryptedCertPath = 'custom/certificates/cert_encrypted.bin';
            if (!file_exists($encryptedCertPath)) {
                throw new Exception("Encrypted certificate not found at: " . $encryptedCertPath . ". Please upload a certificate in Administration > VeriFactu Certificate.");
            }

            // Decrypt certificate
            require_once 'include/utils/encryption_utils.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];
            $encryptedContent = file_get_contents($encryptedCertPath);

            // Manual decryption to avoid trim() corrupting binary P12
            // blowfishDecode() uses trim() which removes whitespace and nulls, damaging binary files
            $data = base64_decode($encryptedContent);
            $bf = new Crypt_Blowfish($key);
            $p12Content = $bf->decrypt($data);

            // DO NOT TRIM. OpenSSL handles the ASN.1 structure and ignores trailing padding.
            // rtrim($p12Content, "\0") can remove valid data if the file ends with null bytes.

            // Debug info
            $GLOBALS['log']->debug("DEBUG VERIFACTU: Encrypted size: " . strlen($encryptedContent));
            $GLOBALS['log']->debug("DEBUG VERIFACTU: Decrypted size: " . strlen($p12Content));

            // --- SOLUCIÓN MEJORADA ERROR 401 ---
            // 1. Leer P12
            $certs = [];
            if (!openssl_pkcs12_read($p12Content, $certs, $certificatePassword)) {
                $sslError = "";
                while ($msg = openssl_error_string()) {
                    $sslError .= $msg . "; ";
                }
                $GLOBALS['log']->fatal("DEBUG VERIFACTU: OpenSSL Error: " . $sslError);
                throw new Exception("Error leyendo el certificado P12. La contraseña es incorrecta o el fichero está dañado. Detalles OpenSSL: " . $sslError);
            }

            // 2. Depuración del Certificado (Verificar en sugarcrm.log)
            $certData = openssl_x509_parse($certs['cert']);
            if ($certData) {
                $certSubject = json_encode($certData['subject']);
                $certSerial = $certData['subject']['serialNumber'] ?? 'No encontrado';
                $certValidTo = date('Y-m-d H:i:s', $certData['validTo_time_t']);

                $GLOBALS['log']->fatal("--- DEBUG VERIFACTU CERT ---");
                $GLOBALS['log']->fatal("Subject: " . $certSubject);
                $GLOBALS['log']->fatal("Serial (NIF esperado): " . $certSerial);
                $GLOBALS['log']->fatal("NIF Configurado: " . $issuerNif);
                $GLOBALS['log']->fatal("Válido hasta: " . $certValidTo);

                // Advertencia si el NIF no coincide (limpiando prefijos comunes como IDCES-)
                $cleanCertNif = preg_replace('/^.*-/', '', $certSerial);
                if (strtoupper($cleanCertNif) !== strtoupper($issuerNif)) {
                    $GLOBALS['log']->fatal("¡ALERTA! El NIF del certificado ($cleanCertNif) NO COINCIDE con el NIF configurado ($issuerNif). Esto causará error 401/Rechazo.");
                }
            }

            // 3. Construcción limpia del PEM (Solo bloques válidos, sin Bag Attributes)
            // Función auxiliar para limpiar cabeceras extrañas
            $cleanPemBlock = function ($str) {
                if (preg_match('/(-----BEGIN (?:CERTIFICATE|PRIVATE KEY)-----.*?-----END (?:CERTIFICATE|PRIVATE KEY)-----)/s', $str, $matches)) {
                    return $matches[1];
                }
                return $str;
            };

            // Orden: Certificado -> Clave Privada -> Intermedios
            // Ponemos el certificado primero para facilitar el parseo en AeatClient::isEntitySealCertificate
            $pemContent = $cleanPemBlock($certs['cert']) . "\n" . $cleanPemBlock($certs['pkey']);

            if (isset($certs['extracerts']) && is_array($certs['extracerts'])) {
                foreach ($certs['extracerts'] as $extraCert) {
                    $pemContent .= "\n" . $cleanPemBlock($extraCert);
                }
            }

            // Guardar en archivo temporal
            $tempPemFile = tempnam(sys_get_temp_dir(), 'stic_cert_debug_');
            file_put_contents($tempPemFile, $pemContent);

            // Usar el PEM temporal
            $client->setCertificate($tempPemFile, null);
            // ------------------------------------------------

            // Configure environment (pre-production or production)
            $client->setProduction($useProduction);

            // Extract invoice data from bean and create registration record
            $invoiceNumber = $invoiceBean->number;
            
            // Force db date format
            $ldate = $timedate->to_db_date($invoiceBean->invoice_date, false);
            $issueDate = new DateTimeImmutable($ldate);

            $description = $invoiceBean->name;

            // Format amounts as strings with exactly 2 decimals (required by AEAT)
            $baseAmount = number_format((float) $invoiceBean->subtotal_amount, 2, '.', '');
            $totalTaxAmount = number_format((float) $invoiceBean->tax_amount, 2, '.', '');

            // Calculate correct total (Base + Tax) - AEAT requires this to be exact
            $totalAmount = number_format((float) $baseAmount + (float) $totalTaxAmount, 2, '.', '');

            // Log the values for debugging
            $invoiceTotal = number_format((float) $invoiceBean->total_amt, 2, '.', '');
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

            // Create breakdown details
            $breakdownDetails = [];
            $taxGroups = [];
            $productQuotes = [];

            // Load product quotes relationship
            if ($invoiceBean->load_relationship('aos_products_quotes')) {
                $productQuotes = $invoiceBean->aos_products_quotes->getBeans();
                
                foreach ($productQuotes as $quote) {
                    // Skip deleted items just in case
                    if (!empty($quote->deleted)) continue;

                    // Get tax rate (vat)
                    // vat field usually contains the percentage like "21.0" or "10.0"
                    $taxRate = $quote->vat;
                    if ($taxRate === '' || $taxRate === null) {
                        $taxRate = '0.00';
                    }
                    
                    // Normalize tax rate string (ensure 2 decimals)
                    $taxRate = number_format((float)$taxRate, 2, '.', '');
                    
                    if (!isset($taxGroups[$taxRate])) {
                        $taxGroups[$taxRate] = [
                            'baseAmount' => 0.0,
                            'taxAmount' => 0.0
                        ];
                    }
                    
                    // Add amounts
                    $taxGroups[$taxRate]['baseAmount'] += (float)$quote->product_total_price;
                    $taxGroups[$taxRate]['taxAmount'] += (float)$quote->vat_amt;
                }
            }

            // If no lines found (fallback to invoice totals - legacy behavior)
            if (empty($taxGroups)) {
                 // Calculate rate from totals to avoid hardcoding 21%
                 $calculatedRate = 0.00;
                 if ((float)$baseAmount != 0) {
                     $calculatedRate = ((float)$totalTaxAmount / (float)$baseAmount) * 100;
                 }

                 $breakdownDetails[] = self::createBreakdownDetail(
                    TaxType::IVA,
                    RegimeType::C01,
                    OperationType::Subject,
                    $baseAmount, // from invoice total
                    number_format($calculatedRate, 2, '.', ''), // Calculated rate
                    $totalTaxAmount // from invoice total
                );
            } else {
                foreach ($taxGroups as $rate => $amounts) {
                    $breakdownDetails[] = self::createBreakdownDetail(
                        TaxType::IVA, // Assuming IVA for now
                        RegimeType::C01, // General regime
                        OperationType::Subject,
                        number_format($amounts['baseAmount'], 2, '.', ''),
                        $rate,
                        number_format($amounts['taxAmount'], 2, '.', '')
                    );
                }
            }

            // Get customer info
            $customerNif = null;
            $customerName = null;
            if (!empty($invoiceBean->billing_account_id)) {
                $account = BeanFactory::getBean('Accounts', $invoiceBean->billing_account_id);
                if ($account) {
                    $customerName = $account->name;
                    $customerNif = $account->stic_identification_number_c;
                }
            } elseif (!empty($invoiceBean->billing_contact_id)) {
                $contact = BeanFactory::getBean('Contacts', $invoiceBean->billing_contact_id);
                if ($contact) {
                    $customerName = trim($contact->first_name . ' ' . $contact->last_name);
                    $customerNif = $contact->stic_identification_number_c;
                }
            }

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
                $previousHash,
                $customerNif,
                $customerName
            );

            // --- DEBUG MODE: volcado de datos antes de enviar ---
            // FORCE DEBUG ALWAYS for design phase
            if (true) {
                echo '<div style="background:white; padding:20px; border:2px solid red; margin:20px; font-family:sans-serif; z-index:99999; position:relative;">';
                echo '<h2 style="color:red; border-bottom:1px solid red;">DEBUG VERIFACTU - DATOS A ENVIAR</h2>';
                
                echo '<h3>0. Configuración y Sistema (SIF)</h3>';
                echo '<p><em>Estos datos forman parte de la huella digital de la factura.</em></p>';
                echo '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                echo '<tr><th style="background:#eee;">Campo</th><th style="background:#eee;">Valor</th></tr>';
                echo "<tr><td>Entorno</td><td>" . ($useProduction ? 'PRODUCCIÓN' : 'PRUEBAS (Pre-producción)') . "</td></tr>";
                echo "<tr><td>Tipo Certificado</td><td>" . ($certificateType ? 'Sello de Entidad' : 'Personal / Representante') . "</td></tr>";
                echo "<tr><td>Nombre Sistema</td><td>{$systemName}</td></tr>";
                echo "<tr><td>ID Sistema</td><td>{$systemId}</td></tr>";
                echo "<tr><td>Versión Sistema</td><td>{$systemVersion}</td></tr>";
                echo "<tr><td>Nº Instalación</td><td>{$installationNumber}</td></tr>";
                echo '</table>';

                echo '<h3>1. Datos Generales</h3>';
                echo '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                echo '<tr><th style="background:#eee;">Campo</th><th style="background:#eee;">Valor</th></tr>';
                echo "<tr><td>NIF Emisor</td><td>{$issuerNif}</td></tr>";
                echo "<tr><td>Nombre Emisor</td><td>{$issuerName}</td></tr>";
                echo "<tr><td>Número Factura</td><td>{$invoiceNumber}</td></tr>";
                echo "<tr><td>Fecha Expedición</td><td>{$issueDate->format('Y-m-d')}</td></tr>";
                echo "<tr><td>Tipo Factura</td><td><strong>" . ($record->invoiceType->value ?? 'N/A') . "</strong> (" . ($record->invoiceType->name ?? '') . ")</td></tr>";
                echo "<tr><td>Descripción</td><td>{$description}</td></tr>";
                echo "<tr><td>NIF Cliente</td><td>{$customerNif}</td></tr>";
                echo "<tr><td>Nombre Cliente</td><td>{$customerName}</td></tr>";
                echo "<tr><td>Total Impuestos</td><td>{$totalTaxAmount}</td></tr>";
                echo "<tr><td>Total Factura</td><td>{$totalAmount}</td></tr>";
                echo "<tr><td><strong>Hash Generado (Huella)</strong></td><td style='font-family:monospace; word-break:break-all;'>{$record->hash}</td></tr>";
                echo '</table>';

                echo '<h3>2. Desglose (Breakdown)</h3>';
                echo '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                echo '<tr>
                        <th style="background:#eee;">Tipo Impuesto</th>
                        <th style="background:#eee;">Régimen</th>
                        <th style="background:#eee;">Operación</th>
                        <th style="background:#eee;">Base</th>
                        <th style="background:#eee;">% Tipo</th>
                        <th style="background:#eee;">Cuota</th>
                      </tr>';
                foreach ($breakdownDetails as $bd) {
                    echo '<tr>';
                    echo "<td>" . ($bd->taxType->value ?? 'N/A') . "</td>";
                    echo "<td>" . ($bd->regimeType->value ?? 'N/A') . "</td>";
                    echo "<td>" . ($bd->operationType->value ?? 'N/A') . "</td>";
                    echo "<td>{$bd->baseAmount}</td>";
                    echo "<td>{$bd->taxRate}</td>";
                    echo "<td>{$bd->taxAmount}</td>";
                    echo '</tr>';
                }
                echo '</table>';

                echo '<h3>3. Encadenamiento</h3>';
                echo '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                if ($previousInvoiceId) {
                    echo "<tr><td>Factura Anterior</td><td>{$previousInvoiceId->invoiceNumber}</td></tr>";
                    echo "<tr><td>Hash Anterior</td><td>{$previousHash}</td></tr>";
                } else {
                    echo "<tr><td colspan='2'>Es la primera factura de la cadena (o no se encontró anterior)</td></tr>";
                }
                echo '</table>';

                echo '<h3>4. Detalle de Líneas (Debug Interno)</h3>';
                echo '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                echo '<tr><th>ID</th><th>Producto</th><th>Total</th><th>VAT (Raw)</th><th>VAT Amt</th><th>Rate Used</th></tr>';
                if (!empty($productQuotes)) {
                    foreach ($productQuotes as $quote) {
                         $rawVat = $quote->vat;
                         $fmtVat = number_format((float)$rawVat, 2, '.', '');
                         echo "<tr>";
                         echo "<td>{$quote->id}</td>";
                         echo "<td>{$quote->name}</td>";
                         echo "<td>{$quote->product_total_price}</td>";
                         echo "<td>'{$rawVat}'</td>";
                         echo "<td>{$quote->vat_amt}</td>";
                         echo "<td>{$fmtVat}</td>";
                         echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron líneas de producto (productQuotes empty)</td></tr>";
                }
                echo '</table>';

                echo '<br><h3 style="color:red;">EJECUCIÓN DETENIDA POR MODO DEBUG</h3>';
                echo '</div>';
                die();
            }
            // ---------------------------------------------------

            // Send records and get response (with detailed error handling)
            $response = $client->send([$record])->wait();

            // Add debug info to response
            $response->debugInfo = [
                'baseAmount' => $baseAmount,
                'taxAmount' => $totalTaxAmount,
                'totalAmount' => $totalAmount,
                'invoiceTotal' => $invoiceTotal,
                'endpoint' => $useProduction ? 'Production (www1.agenciatributaria.gob.es)' : 'Pre-production (prewww1/prewww10.aeat.es)',
            ];

            // Add the record to the response so we can access the hash
            $response->record = $record;

            // Process and save AEAT response
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
                $invoiceBean->verifactu_aeat_response_c = substr($aeatResponse, 0, 255);

                // Update status based on AEAT response
                if ($item->status->value === 'Correcto' || $item->status->value === 'AceptadoConErrores') {
                    $invoiceBean->verifactu_aeat_status_c = 'accepted';

                    // Generate and save QR code URL only when invoice is accepted
                    if (isset($response->record)) {
                        $qrUrl = self::generateQrCodeUrl($response->record, false, true);
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

            // Format response for display
            $debugInfo = $response->debugInfo ?? [];
            $formattedResponse = self::formatAeatResponse($response, $debugInfo);

            // Log the response
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': AEAT Response: ' . $formattedResponse);

            // Show success message with details
            $successMessage = 'Factura enviada correctamente a la AEAT';
            if ($invoiceBean->verifactu_aeat_status_c === 'accepted') {
                $successMessage .= ' y aceptada';
            }
            $successMessage .= '. <a href="#" onclick="document.getElementById(\'aeat-response-details\').style.display=\'block\'; this.style.display=\'none\'; return false;">Ver detalles</a>';
            $successMessage .= '<div id="aeat-response-details" style="display:none; margin-top:10px; padding:10px; background:#f5f5f5; border:1px solid #ddd;"><pre>' . htmlspecialchars($formattedResponse) . '</pre></div>';

            SugarApplication::appendSuccessMessage($successMessage);

            return true;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error sending invoice to AEAT: ' . $e->getMessage());

            // Format error for display
            $formattedError = self::formatAeatError($e);

            // Show error message with details
            $errorMessage = 'Error al enviar la factura a la AEAT. <a href="#" onclick="document.getElementById(\'aeat-error-details\').style.display=\'block\'; this.style.display=\'none\'; return false;">Ver detalles</a>';
            $errorMessage .= '<div id="aeat-error-details" style="display:none; margin-top:10px; padding:10px; background:#f5f5f5; border:1px solid #ddd;"><pre>' . htmlspecialchars($formattedError) . '</pre></div>';

            SugarApplication::appendErrorMessage($errorMessage);

            return false;
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
            if (isset($debugInfo['endpoint'])) {
                $output .= "  Endpoint: " . $debugInfo['endpoint'] . "\n";
            }
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

    /**
     * Generate the next invoice number based on the serial format
     *
     * @param string $format The serial format (e.g., 'YYYY-0000', 'YY-000', 'FACT-0000')
     * @param SugarBean $bean The invoice bean
     * @return string The generated invoice number
     */
    public static function generateNextInvoiceNumber($format, $bean)
    {
        global $db;

        // Get the year from the invoice date or current date
        $invoiceDate = !empty($bean->invoice_date) ? $bean->invoice_date : date('Y-m-d');
        $year = date('Y', strtotime($invoiceDate));
        $yearTwoDigits = substr($year, -2);

        $GLOBALS['log']->debug("generateNextInvoiceNumber - Format: $format, Year: $year");

        // Build a pattern to search for invoices with the same format and year
        $searchPattern = self::buildInvoiceNumber($format, 0, $year, $yearTwoDigits);
        // Replace the numeric part with % for SQL LIKE search
        preg_match('/(0+)/', $format, $matches);
        if (!empty($matches)) {
            $numericPlaceholder = $matches[0];
            $numericLength = strlen($numericPlaceholder);
            $zeroPattern = str_repeat('0', $numericLength);
            $searchPattern = str_replace($zeroPattern, str_repeat('_', $numericLength), $searchPattern);
        }

        // Find all invoice numbers with the same format and year
        // Note: stic_serial_format_c is in aos_invoices_cstm table
        $query = "SELECT aos_invoices.number
                  FROM aos_invoices
                  INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                  WHERE aos_invoices_cstm.stic_serial_format_c = " . $db->quoted($format) . "
                  AND aos_invoices.deleted = 0
                  AND aos_invoices.number IS NOT NULL
                  AND aos_invoices.number != ''
                  AND aos_invoices.number LIKE " . $db->quoted($searchPattern) . "
                  ORDER BY aos_invoices.number DESC
                  LIMIT 1";

        $GLOBALS['log']->debug("generateNextInvoiceNumber - Query: $query");

        $lastNumber = $db->getOne($query);
        $nextNumber = 1;

        if (!empty($lastNumber)) {
            $numericPart = self::extractNumericPart($lastNumber, $format);
            $GLOBALS['log']->debug("generateNextInvoiceNumber - Found invoice: {$lastNumber}, numeric part: $numericPart");
            $nextNumber = intval($numericPart) + 1;
        }

        $GLOBALS['log']->debug("generateNextInvoiceNumber - Next number: $nextNumber");

        // Build the new invoice number
        $generatedNumber = self::buildInvoiceNumber($format, $nextNumber, $year, $yearTwoDigits);
        $GLOBALS['log']->debug("generateNextInvoiceNumber - Generated number: '$generatedNumber'");

        return $generatedNumber;
    }

    /**
     * Extract the numeric part from an invoice number based on the format
     *
     * @param string $invoiceNumber The invoice number (e.g., '2024-0015')
     * @param string $format The format pattern (e.g., 'YYYY-0000')
     * @return string The numeric part as string
     */
    private static function extractNumericPart($invoiceNumber, $format)
    {
        $GLOBALS['log']->debug("extractNumericPart - Invoice: '$invoiceNumber', Format: '$format'");

        // Find the position and length of the numeric placeholder (0000, 000, 00, etc.)
        preg_match('/(0+)/', $format, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches)) {
            $GLOBALS['log']->debug("extractNumericPart - No numeric placeholder found in format");
            return '0';
        }

        $numericPlaceholder = $matches[0][0]; // e.g., '0000'
        $numericLength = strlen($numericPlaceholder);
        $numericPosition = $matches[0][1]; // Position in format string

        // Build a regex pattern from the format to extract the numeric part
        // Replace YYYY with \d{4}, YY with \d{2}, and 0+ with (\d+)
        $pattern = preg_quote($format, '/');
        $pattern = str_replace('YYYY', '\\d{4}', $pattern);
        $pattern = str_replace('YY', '\\d{2}', $pattern);
        $pattern = preg_replace('/0+/', '(\\d+)', $pattern, 1); // Only replace first occurrence

        $GLOBALS['log']->debug("extractNumericPart - Regex pattern: '/^$pattern$/'");

        // Match the invoice number against the pattern
        if (preg_match('/^' . $pattern . '$/', $invoiceNumber, $matches)) {
            // The numeric part is in the first capture group
            $result = isset($matches[1]) ? $matches[1] : '0';
            $GLOBALS['log']->debug("extractNumericPart - Extracted: '$result'");
            return $result;
        }

        $GLOBALS['log']->debug("extractNumericPart - Pattern did not match, returning '0'");
        return '0';
    }

    /**
     * Build the invoice number based on the format, next number, and year
     *
     * @param string $format The format pattern (e.g., 'YYYY-0000')
     * @param int $nextNumber The next sequential number
     * @param string $year The full year (4 digits)
     * @param string $yearTwoDigits The year with 2 digits
     * @return string The formatted invoice number
     */
    private static function buildInvoiceNumber($format, $nextNumber, $year, $yearTwoDigits)
    {
        // Find the numeric placeholder and its length
        preg_match('/(0+)/', $format, $matches);

        if (empty($matches)) {
            return $format;
        }

        $numericPlaceholder = $matches[0]; // e.g., '0000'
        $numericLength = strlen($numericPlaceholder);

        // Format the number with leading zeros
        $formattedNumber = str_pad($nextNumber, $numericLength, '0', STR_PAD_LEFT);

        // Replace placeholders in the format
        $result = str_replace('YYYY', $year, $format);
        $result = str_replace('YY', $yearTwoDigits, $result);
        $result = str_replace($numericPlaceholder, $formattedNumber, $result);

        return $result;
    }

}
