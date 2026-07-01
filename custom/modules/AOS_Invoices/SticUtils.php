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
use josemmo\Verifactu\Models\Records\CancellationRecord;
use josemmo\Verifactu\Models\Records\FiscalIdentifier;
use josemmo\Verifactu\Models\Records\ForeignFiscalIdentifier;
use josemmo\Verifactu\Models\Records\ForeignIdType;
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
     * Re-entry guard: tracks invoice IDs currently being sent to AEAT
     * Prevents infinite recursion when internal save() triggers after_save hook
     * (AOS_Invoices::save() does not forward $skip_hooks to SugarBean::save())
     * @var array
     */
    private static array $processingInvoiceIds = [];

    /**
     * Check if Verifactu integration is activated
     * @return bool
     */
    public static function isVerifactuActivated()
    {
        require_once 'modules/stic_Settings/Utils.php';
        $setting = stic_SettingsUtils::getSetting('VERIFACTU_ACTIVATED');
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': VERIFACTU_ACTIVATED = ' . var_export($setting, true));
        return ($setting == 1 || $setting === '1');
    }

    /**
     * Check if Verifactu is activated and return status info for UI
     * @return array
     */
    public static function getVerifactuStatus()
    {
        $activated = self::isVerifactuActivated();

        require_once 'custom/include/SticCertificateUtils.php';
        $hasCertificate = SticCertificateUtils::certificateExists();

        $warning = '';
        if (!$activated && $hasCertificate) {
            global $mod_strings;
            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }
            $warning = $mod_strings['LBL_VERIFACTU_NOT_ACTIVATED_WARNING'];
        }

        return [
            'activated' => $activated,
            'hasCertificate' => $hasCertificate,
            'warning' => $warning,
        ];
    }

    /**
     * Filter the invoice_status_dom dropdown based on the invoice's current status.
     * Modifies $app_list_strings in place to only keep allowed options.
     * 
     * @param array $app_list_strings Reference to the global app_list_strings
     * @param string $currentStatus The current status value of the invoice
     * @return void
     */
    public static function filterStatusDropdown(&$app_list_strings, $currentStatus)
    {
        if (!self::isVerifactuActivated()) {
            return;
        }

        if (!isset($app_list_strings['invoice_status_dom'])) {
            return;
        }

        if ($currentStatus === 'draft') {
            // Draft can only stay as draft
            $allowed = array('draft');
        } else {
            // For all other statuses, remove 'draft' and 'Cancelled'.
            // All other statuses (Paid, Unpaid, and any future ones) remain available.
            $allowed = array_keys($app_list_strings['invoice_status_dom']);
            $allowed = array_diff($allowed, array('draft', 'Cancelled', ''));
        }

        foreach ($app_list_strings['invoice_status_dom'] as $key => $label) {
            if (!in_array($key, $allowed)) {
                unset($app_list_strings['invoice_status_dom'][$key]);
            }
        }
    }

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
     * @param bool $isRectified Whether this is a rectified invoice
     * @param string|null $rectifiedType Type of rectification ('S' = Substitution, 'I' = Differences)
     * @param string|null $rectifiedBase Base code for rectification (R1, R2, R3, R4, R5)
     * @param string|null $rectifiedSerie Serie of the rectified invoice
     * @param string|null $rectifiedNumber Number of the rectified invoice
     * @param DateTimeImmutable|null $rectifiedDate Date of the rectified invoice
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
        $customerName = null,
        $isRectified = false,
        $rectifiedType = null,
        $rectifiedBase = null,
        $rectifiedNumber = null,
        $rectifiedDate = null,
        $correctedBaseAmount = null,
        $correctedTaxAmount = null
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
        if ($isRectified && $rectifiedBase) {
            // For rectified invoices, use the rectified base type (R1, R2, R3, R4, R5)
            switch ($rectifiedBase) {
                case 'R1':
                    $record->invoiceType = InvoiceType::R1; // Art 80.1 y 80.2
                    break;
                case 'R2':
                    $record->invoiceType = InvoiceType::R2; // Art. 80.3
                    break;
                case 'R3':
                    $record->invoiceType = InvoiceType::R3; // Art. 80.4
                    break;
                case 'R4':
                    $record->invoiceType = InvoiceType::R4; // Resto
                    break;
                case 'R5':
                    $record->invoiceType = InvoiceType::R5; // Simplificada rectificativa
                    break;
                default:
                    // Fallback to R1 if unknown type
                    $record->invoiceType = InvoiceType::R1;
                    $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Unknown rectified base type: ' . $rectifiedBase . ', using R1 as default');
            }
            // For rectified invoices with customer info, still set recipients
            if (!empty($customerNif) && !empty($customerName)) {
                $recipient = self::createRecipientIdentifier($customerName, $customerNif);
                $record->recipients = [$recipient];
            }
        } elseif (!empty($customerNif) && !empty($customerName)) {
            $record->invoiceType = InvoiceType::Factura; // F1 - Completa
            $recipient = self::createRecipientIdentifier($customerName, $customerNif);
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

        // Rectified invoice data (if applicable)
        if ($isRectified && $rectifiedType) {
            // Set corrective type ('S' = Substitution, 'I' = Differences)
            // Required by AEAT for all rectified invoices (TipoRectificativa)
            $record->correctiveType = ($rectifiedType === 'S')
            ? \josemmo\Verifactu\Models\Records\CorrectiveType::Substitution
            : \josemmo\Verifactu\Models\Records\CorrectiveType::Differences;

            // Set corrected invoice reference only if we have the original invoice data
            if ($rectifiedNumber && $rectifiedDate) {
                $rectifiedInvoiceId = new InvoiceIdentifier();
                $rectifiedInvoiceId->issuerId = $issuerNif;
                $rectifiedInvoiceId->invoiceNumber = $rectifiedNumber;
                $rectifiedInvoiceId->issueDate = $rectifiedDate;
                $record->correctedInvoices = [$rectifiedInvoiceId];
            }

            // For substitution type ('S'), set corrected amounts
            if ($rectifiedType === 'S' && $correctedBaseAmount !== null && $correctedTaxAmount !== null) {
                $record->correctedBaseAmount = $correctedBaseAmount;
                $record->correctedTaxAmount = $correctedTaxAmount;
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Setting corrected amounts - Base: ' . $correctedBaseAmount . ', Tax: ' . $correctedTaxAmount);
            }

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Setting rectified invoice data - Type: ' . $rectifiedType . ', Base: ' . $rectifiedBase . ', Original: ' . ($rectifiedNumber ?? 'N/A'));
        }

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
     * Create a recipient identifier, using "No censado" (07) in test mode for personal NIFs only.
     * AEAT only accepts IDType=07 for individuals (personas físicas), not for companies (CIF).
     */
    private static function createRecipientIdentifier($name, $nif)
    {
        $isTestMode = stic_SettingsUtils::getSetting('VERIFACTU_TEST') == '1';
        $isPersonalNif = preg_match('/^[0-9]/', $nif);
        if ($isTestMode && $isPersonalNif) {
            return new ForeignFiscalIdentifier($name, 'ES', ForeignIdType::Unregistered, $nif);
        }
        return new FiscalIdentifier($name, $nif);
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
     * Build a ComputerSystem object with centralised SIF configuration.
     *
     * All Verifactu operations must use this method to guarantee that the
     * same system identification data is sent to AEAT on every request.
     *
     * The vendorNif/vendorName identify the software producer (SinergiaTIC),
     * separate from the taxpayer (certificate holder) which is set via FiscalIdentifier.
     *
     * @param string $issuerNif  NIF of the certificate holder / issuer (for FiscalIdentifier)
     * @param string $issuerName Name of the certificate holder / issuer (for FiscalIdentifier)
     *
     * @return ComputerSystem
     */
    private static function buildComputerSystem($issuerNif, $issuerName)
    {
        global $sugar_config;

        $systemName    = 'SinergiaCRM';
        $systemId      = 'SC';
        $systemVersion = $sugar_config['sinergiacrm_version'] ?? '1.0';

        $vendorNif   = $sugar_config['verifactu_vendor_nif'] ?? '';
        $vendorName  = $sugar_config['verifactu_vendor_name'] ?? '';

        // Auto-generate installation number from unique_key
        $installationNumber = 'SC-' . substr(md5($sugar_config['unique_key']), 0, 8);

        return self::configureComputerSystem(
            $vendorNif,
            $vendorName,
            $systemName,
            $systemId,
            $systemVersion,
            $installationNumber
        );
    }

    /**
     * Parse a date string into a DateTimeImmutable regardless of its format.
     *
     * SuiteCRM beans can expose dates either in DB format (Y-m-d) or in the
     * user/locale display format (e.g. d/m/Y or d.m.Y depending on config).
     * new DateTimeImmutable() only accepts Y-m-d natively; any other format
     * throws "Unexpected character". This helper tries Y-m-d first and then
     * the display formats in common use, so callers never need to worry about
     * which format the bean is using.
     *
     * @param string $dateStr Date string from a bean field
     * @return DateTimeImmutable
     * @throws Exception If the string cannot be parsed with any known format
     */
    private static function parseDateToImmutable($dateStr)
    {
        $formats = ['d-m-Y', 'Y-m-d', 'd/m/Y', 'd.m.Y', 'm/d/Y'];
        foreach ($formats as $format) {
            $dt = DateTimeImmutable::createFromFormat($format, $dateStr);
            if ($dt !== false) {
                // Reset time component to midnight to allow safe date-only comparisons
                return $dt->setTime(0, 0, 0);
            }
        }
        throw new Exception("Cannot parse date '" . $dateStr . "' with any known format.");
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
        global $db, $mod_strings, $sugar_config;

        // Check if Verifactu is activated - if not, skip (legacy mode)
        if (!self::isVerifactuActivated()) {
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Verifactu not activated (legacy mode), skipping sendToAeat.');
            return;
        }

        // Re-entry guard: prevent infinite recursion from internal save() → after_save → sendToAeat
        // (AOS_Invoices::save() does not forward $skip_hooks to SugarBean::save())
        $invoiceId = $invoiceBean->id;
        if (isset(self::$processingInvoiceIds[$invoiceId])) {
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Re-entry detected for invoice ' . $invoiceId . ', skipping.');
            return;
        }
        self::$processingInvoiceIds[$invoiceId] = true;

        // Allow sending if: status is 'emitted' AND (aeat_status is empty/pending/rejected, but NOT 'accepted')
        $aeatStatus = $invoiceBean->verifactu_aeat_status_c ?? '';
        if (
            empty($invoiceBean->status ?? '') ||
            $invoiceBean->status !== 'emitted' ||
            $aeatStatus === 'accepted') {

            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice cannot be sent to AEAT. Status: ' . ($invoiceBean->status ?? 'N/A') . ', AEAT Status: ' . ($aeatStatus ?: 'N/A'));
            SugarApplication::appendErrorMessage(self::getStyledErrorAlert($mod_strings['LBL_INVOICE_INVALID_STATUSES_FOR_SEND_TO_AEAT']));
            unset(self::$processingInvoiceIds[$invoiceId]);
            SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
            return;
        }

        $sendSuccess = false;

        try {
            // Load certificate utilities
            require_once 'custom/include/SticCertificateUtils.php';

            // Get certificate components (NO PASSWORD NEEDED!)
            $certComponents = SticCertificateUtils::getCertificateComponents();
            if (!$certComponents) {
                throw new Exception("Certificate not found or could not be decrypted. Please upload a certificate in Administration > Digital Certificate.");
            }

            // Extract NIF and holder name from certificate
            $issuerNif = SticCertificateUtils::getCertificateNif();
            $issuerName = SticCertificateUtils::getCertificateHolderName();

            if (empty($issuerNif) || empty($issuerName)) {
                throw new Exception("Could not extract NIF or holder name from certificate. Please verify the certificate is valid.");
            }

            // === Step 1.3: Generate real invoice number at AEAT send time (in memory only) ===
            // Generate number in a local variable - will be saved only if AEAT sends successfully
            $generatedInvoiceNumber = null;
            
            // Get the key value from bean
            $seriesKey = $invoiceBean->stic_invoice_type_c;
            
            // Get available series from config dynamically
            $availableSeries = array_keys($sugar_config['aos']['invoices']['series'] ?? []);
            
            // Use config key directly for both config and DB query
            // The dropdown should return the config key directly
            $seriesConfigKey = null;
            $seriesDbValue = null;
            
            // Check if bean value matches a config key
            if (in_array($seriesKey, $availableSeries)) {
                $seriesConfigKey = $seriesKey;
                $seriesDbValue = $seriesKey;
            } else {
                // Try to find matching series (fallback for backwards compatibility)
                // Check if the bean value matches any config key
                foreach ($availableSeries as $configKey) {
                    // Direct match
                    if ($configKey === $seriesKey) {
                        $seriesConfigKey = $configKey;
                        $seriesDbValue = $configKey;
                        break;
                    }
                }
                
                // If still not found, use first available series as fallback
                if (empty($seriesConfigKey) && !empty($availableSeries)) {
                    $seriesConfigKey = $availableSeries[0];
                    $seriesDbValue = $availableSeries[0];
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Series "' . $seriesKey . '" not found in config, using fallback: ' . $seriesConfigKey);
                }
            }
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Bean value: "' . $seriesKey . '", Config key: "' . ($seriesConfigKey ?? 'NULL') . '", DB value: "' . ($seriesDbValue ?? 'NULL') . '"');
            
            if (!empty($sugar_config['aos']['invoices']['series'][$seriesConfigKey])) {
                // === Step 2.2: Validate series format when loading config ===
                $seriesFormat = $sugar_config['aos']['invoices']['series'][$seriesConfigKey]['format'] ?? '';
                try {
                    self::validateSeriesFormat($seriesFormat);
                } catch (Exception $e) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invalid series format: ' . $e->getMessage());
                    $errorMsg = $mod_strings['LBL_AOS_SERIES_FORMAT_INVALID'] . ' (' . $seriesFormat . ') ' . $mod_strings['LBL_AOS_SERIES_FORMAT_INVALID_DETAILS'];
                    SugarApplication::appendErrorMessage(self::getStyledErrorAlert($errorMsg));
                    return;
                }
                // === End Step 2.2 ===
                
                require_once 'custom/modules/AOS_Invoices/SticUtils.php';
                $generatedInvoiceNumber = AOS_InvoicesUtils::generateNextInvoiceNumber($seriesConfigKey, $invoiceBean, $seriesDbValue);
                if (!empty($generatedInvoiceNumber)) {
                    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Generated invoice number (in memory): ' . $generatedInvoiceNumber);
                } else {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to generate invoice number for series: ' . $seriesConfigKey);
                }
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Could not find series configuration');
            }
            // === End Step 1.3 ===

            // === Step 1.6: Log to audit before sending ===
            if (!empty($generatedInvoiceNumber)) {
                $preAuditTimestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
                $preAuditLog = $invoiceBean->verifactu_audit_log_c ?? '';
                if (!empty($preAuditLog)) {
                    $preAuditLog .= "\n";
                }
                $preAuditLog .= "[{$preAuditTimestamp}] Preparing to send invoice to AEAT. Generated number: {$generatedInvoiceNumber}, Series: {$seriesConfigKey}";
                $invoiceBean->verifactu_audit_log_c = $preAuditLog;
            }
            // === End Step 1.6 ===

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate data loaded - NIF: ' . $issuerNif . ', Name: ' . $issuerName);

            // Get certificate type (entity seal or representative) from certificate itself
            $certificateType = SticCertificateUtils::isEntitySeal();

            // Get other settings from stic_Settings module
            require_once 'modules/stic_Settings/Utils.php';
            $taxTypeSetting = stic_SettingsUtils::getSetting('VERIFACTU_TAX_TYPE');

            // Determine Tax Type (IVA, IPSI, IGIC)
            $verifactuTaxType = TaxType::IVA; // Default to IVA (01)
            if ($taxTypeSetting === '02') {
                $verifactuTaxType = TaxType::IPSI;
            } elseif ($taxTypeSetting === '03') {
                $verifactuTaxType = TaxType::IGIC;
            }

            if ($certificateType === null) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Cannot determine certificate type (entity seal or representative).');
                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($mod_strings['LBL_MISSING_SETTINGS']));
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
            }

            $useProduction = stic_SettingsUtils::getSetting('VERIFACTU_TEST') == '1' ? false : true;

            // Configure computer system
            $system = self::buildComputerSystem($issuerNif, $issuerName);

            // Create taxpayer identifier
            $taxpayer = new FiscalIdentifier($issuerName, $issuerNif);

            // Create AEAT client
            $client = new AeatClient($system, $taxpayer);

            // Configure certificate type (Entity Seal vs Personal)
            $client->setEntitySeal((bool) $certificateType);

            // Get certificate components (already extracted as PEM - NO PASSWORD NEEDED!)
            $certificate = $certComponents['certificate'];
            $privateKey = $certComponents['private_key'];
            $caChain = $certComponents['ca_chain'];

            // Debug info
            $GLOBALS['log']->debug("DEBUG VERIFACTU: Certificate PEM size: " . strlen($certificate));
            $GLOBALS['log']->debug("DEBUG VERIFACTU: Private key PEM size: " . strlen($privateKey));

            // --- CERTIFICATE VALIDATION ---
            // Parse and validate certificate details
            $certData = openssl_x509_parse($certificate);
            if ($certData) {
                $certSubject = json_encode($certData['subject']);
                $certSerial = $certData['subject']['serialNumber'] ?? 'No encontrado';
                $certValidTo = date('Y-m-d H:i:s', $certData['validTo_time_t']);

                $GLOBALS['log']->info("--- DEBUG VERIFACTU CERT ---");
                $GLOBALS['log']->info("Subject: " . $certSubject);
                $GLOBALS['log']->info("Serial: " . $certSerial);
                $GLOBALS['log']->info("NIF Configured: " . $issuerNif);
                $GLOBALS['log']->info("Valid until: " . $certValidTo);
                $GLOBALS['log']->info("Certificate Type: " . ($certificateType ? 'Entity Seal' : 'Representative'));

                // Check expiration
                if ($certData['validTo_time_t'] < time()) {
                    $GLOBALS['log']->error("¡ALERT! Certificate has EXPIRED on " . $certValidTo);
                    throw new Exception("The certificate has expired. Please upload a valid certificate.");
                }
            } else {
                $GLOBALS['log']->error("Failed to parse certificate.");
                throw new Exception("Failed to parse certificate. The certificate may be invalid.");
            }

            // 4. Build PEM content (Certificate + Private Key + CA Chain)
            // Helper function to ensure clean PEM blocks
            $cleanPemBlock = function ($str) {
                if (preg_match('/(-----BEGIN (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----.*?-----END (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----)/s', $str, $matches)) {
                    return trim($matches[1]);
                }
                return trim($str);
            };

            // Order: Certificate -> Private Key -> CA Chain
            // Certificate first to facilitate parsing in AeatClient::isEntitySealCertificate
            $pemContent = $cleanPemBlock($certificate) . "\n" . $cleanPemBlock($privateKey);

            // Add CA chain if exists
            if (!empty($caChain)) {
                $pemContent .= "\n" . $cleanPemBlock($caChain);
            }

            // Save to temporary file (AEAT client requires file path)
            $tempPemFile = tempnam(sys_get_temp_dir(), 'stic_verifactu_cert_');
            file_put_contents($tempPemFile, $pemContent);

            $GLOBALS['log']->debug("DEBUG VERIFACTU: Temporary PEM file created: " . $tempPemFile);
            $GLOBALS['log']->debug("DEBUG VERIFACTU: PEM content size: " . strlen($pemContent) . " bytes");

            // Set certificate in AEAT client (NO PASSWORD NEEDED!)
            $client->setCertificate($tempPemFile, null);
            // ------------------------------------------------

            // Configure environment (pre-production or production)
            $client->setProduction($useProduction);

            // Extract invoice data from bean and create registration record
            // Use generated number (in memory) instead of bean value
            $invoiceNumber = !empty($generatedInvoiceNumber) ? $generatedInvoiceNumber : $invoiceBean->number;

            // Use parseDateToImmutable to handle both Y-m-d (DB) and display formats
            // (e.g. d/m/Y, d.m.Y) that BeanFactory may return depending on context.
            $issueDate = self::parseDateToImmutable($invoiceBean->invoice_date);

            $description = $invoiceBean->name;

            // Format amounts as strings with exactly 2 decimals (required by AEAT)
            $baseAmount = number_format((float) $invoiceBean->subtotal_amount, 2, '.', '');
            $totalTaxAmount = number_format((float) $invoiceBean->tax_amount, 2, '.', '');

            // Calculate correct total (Base + Tax) - AEAT requires this to be exact
            $totalAmount = number_format((float) $baseAmount + (float) $totalTaxAmount, 2, '.', '');

            // Log the values for debugging
            $invoiceTotal = number_format((float) $invoiceBean->total_amount, 2, '.', '');
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice amounts - Base: ' . $baseAmount . ', Tax: ' . $totalTaxAmount . ', Invoice Total: ' . $invoiceTotal . ', Calculated Total: ' . $totalAmount);

            // Warn if invoice total doesn't match calculated total
            if ($invoiceTotal !== $totalAmount) {
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice total (' . $invoiceTotal . ') differs from calculated total (' . $totalAmount . '). Using calculated total for AEAT.');
            }

            // Get previous invoice for chaining
            $previousInvoiceId = null;
            $previousHash = null;
            $previousInvoice = self::getPreviousInvoice($invoiceBean->id);

            // === Step 2.1: Validate chronological order by SERIES ===
            // Get last invoice from the SAME series for date validation
            $seriesName = $invoiceBean->stic_invoice_type_c;
            $seriesLastInvoiceQuery = "SELECT id, number, invoice_date 
                FROM aos_invoices 
                INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c 
                WHERE aos_invoices_cstm.stic_invoice_type_c = " . $db->quoted($seriesName) . "
                AND aos_invoices.deleted = 0 
                AND aos_invoices.id != " . $db->quoted($invoiceBean->id) . "
                AND aos_invoices_cstm.verifactu_aeat_status_c = 'accepted'
                AND aos_invoices.invoice_date IS NOT NULL
                ORDER BY aos_invoices.invoice_date DESC, aos_invoices.number DESC LIMIT 1";
            
            $seriesLastInvoice = $db->fetchOne($seriesLastInvoiceQuery);
            
            if ($seriesLastInvoice && !empty($seriesLastInvoice['invoice_date'])) {
                $seriesLastInvoiceDate = self::parseDateToImmutable($seriesLastInvoice['invoice_date']);
                
                // Validate chronological order by series
                if ($issueDate < $seriesLastInvoiceDate) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice date (' . $issueDate->format('Y-m-d') . ') is earlier than last registered invoice date for series ' . $seriesName . ' (' . $seriesLastInvoiceDate->format('Y-m-d') . ', #' . $seriesLastInvoice['number'] . '). Sending blocked.');
                    SugarApplication::appendErrorMessage(self::getStyledErrorAlert(
                        sprintf(
                            $mod_strings['LBL_INVOICE_DATE_BEFORE_LAST_REGISTERED'],
                            $issueDate->format('d/m/Y'),
                            $seriesLastInvoice['number'],
                            $seriesLastInvoiceDate->format('d/m/Y')
                        )
                    ));
                    $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                    $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                    SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
                    return;
                }
            }
            // === End Step 2.1 ===

            if ($previousInvoice) {
                $previousInvoiceDate = self::parseDateToImmutable($previousInvoice->invoice_date);

                $previousInvoiceId = new InvoiceIdentifier();
                $previousInvoiceId->issuerId = $issuerNif;
                $previousInvoiceId->invoiceNumber = $previousInvoice->number;
                $previousInvoiceId->issueDate = $previousInvoiceDate;
                $previousHash = $previousInvoice->verifactu_hash_c ?? null;

                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Chaining to previous invoice (global): ' . $previousInvoice->number . ' (Hash: ' . ($previousHash ?? 'N/A') . ')');
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
                    if (!empty($quote->deleted)) {
                        continue;
                    }

                    // Get tax rate (vat)
                    // vat field usually contains the percentage like "21.0" or "10.0"
                    $taxRate = $quote->vat;
                    if ($taxRate === '' || $taxRate === null) {
                        $taxRate = '0.00';
                    }

                    // Normalize tax rate string (ensure 2 decimals)
                    $taxRate = number_format((float) $taxRate, 2, '.', '');

                    // Determine Operation Type from custom field
                    // Values: S=Subject, E=Exempt, N=NotSubject, NL=NotSubjectLoc
                    $opTypeVal = $quote->verifactu_aeat_operation_type_c ?? 'S';
                    $operationType = OperationType::Subject; // Default (S1)

                    if ($opTypeVal === 'E') {
                        // Defaulting to E1 (Article 20) for generic Exempt.
                        // Ideally this should be more specific.
                        $operationType = OperationType::ExemptByArticle20;
                    } elseif ($opTypeVal === 'N') {
                        $operationType = OperationType::NonSubject;
                    } elseif ($opTypeVal === 'NL') {
                        $operationType = OperationType::NonSubjectByLocation;
                    }

                    // Group key must include Tax Rate AND Operation Type
                    // We use the enum value for the key to ensure uniqueness
                    $groupKey = $taxRate . '_' . $operationType->value;

                    if (!isset($taxGroups[$groupKey])) {
                        $taxGroups[$groupKey] = [
                            'baseAmount' => 0.0,
                            'taxAmount' => 0.0,
                            'taxRate' => $taxRate,
                            'operationType' => $operationType,
                        ];
                    }

                    // Calculate line total without tax (product_total_price might include tax in some cases)
                    // Use product_list_price * product_qty to get the base amount
                    $lineBaseAmount = (float) $quote->product_list_price * (float) $quote->product_qty;

                    // Add amounts
                    $taxGroups[$groupKey]['baseAmount'] += $lineBaseAmount;
                    $taxGroups[$groupKey]['taxAmount'] += (float) $quote->vat_amt;
                }
            }

            // If no lines found (fallback to invoice totals - legacy behavior)
            if (empty($taxGroups)) {
                // Calculate rate from totals to avoid hardcoding 21%
                $calculatedRate = 0.00;
                if ((float) $baseAmount != 0) {
                    $calculatedRate = ((float) $totalTaxAmount / (float) $baseAmount) * 100;
                }

                $breakdownDetails[] = self::createBreakdownDetail(
                    $verifactuTaxType,
                    RegimeType::C01,
                    OperationType::Subject,
                    $baseAmount, // from invoice total
                    number_format($calculatedRate, 2, '.', ''), // Calculated rate
                    $totalTaxAmount // from invoice total
                );
            } else {
                foreach ($taxGroups as $key => $groupData) {
                    $taxRateToSend = $groupData['taxRate'];
                    $taxAmountToSend = number_format($groupData['taxAmount'], 2, '.', '');

                    // Fix Error [1238]: If operation is exempt, tax rate and amount must be null
                    $opType = $groupData['operationType'];
                    // Check if it is an exempt type (starts with E) or Non-Subject (starts with N)
                    if (isset($opType->value) && (strpos($opType->value, 'E') === 0 || strpos($opType->value, 'N') === 0)) {
                        $taxRateToSend = null;
                        $taxAmountToSend = null;
                    }

                    $breakdownDetails[] = self::createBreakdownDetail(
                        $verifactuTaxType,
                        RegimeType::C01, // General regime
                        $opType, // Dynamic Operation Type
                        number_format($groupData['baseAmount'], 2, '.', ''),
                        $taxRateToSend,
                        $taxAmountToSend
                    );
                }
            }

            // Get customer info
            $customerNif = null;
            $customerName = null;
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Getting customer info - billing_account_id: ' . ($invoiceBean->billing_account_id ?? 'empty') . ', billing_contact_id: ' . ($invoiceBean->billing_contact_id ?? 'empty'));

            if (!empty($invoiceBean->billing_account_id)) {
                $account = BeanFactory::getBean('Accounts', $invoiceBean->billing_account_id);
                if ($account && !empty($account->id)) {
                    $customerName = $account->name;
                    $customerNif = $account->stic_identification_number_c;
                    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Customer from Account - Name: ' . $customerName . ', NIF: ' . ($customerNif ?? 'empty'));
                }
            } elseif (!empty($invoiceBean->billing_contact_id)) {
                $contact = BeanFactory::getBean('Contacts', $invoiceBean->billing_contact_id);
                if ($contact && !empty($contact->id)) {
                    $customerName = trim($contact->first_name . ' ' . $contact->last_name);
                    $customerNif = $contact->stic_identification_number_c;
                    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Customer from Contact - Name: ' . $customerName . ', NIF: ' . ($customerNif ?? 'empty'));
                }
            }

            if (empty($customerNif) || empty($customerName)) {
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': No customer info found for invoice - this will cause error 1189 for R1 invoices');
            }

            // === Validate customer NIF is informed ===
            if (empty($customerNif)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Customer NIF is not informed - blocking send');
                
                if (empty($mod_strings)) {
                    $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
                }
                
                // Build link to customer edit view
                $customerLink = '';
                $customerType = '';
                if (!empty($invoiceBean->billing_account_id)) {
                    $customerLink = '<a href="index.php?module=Accounts&action=EditView&record=' . $invoiceBean->billing_account_id . '" target="_blank">';
                    $customerType = 'la Organización';
                } elseif (!empty($invoiceBean->billing_contact_id)) {
                    $customerLink = '<a href="index.php?module=Contacts&action=EditView&record=' . $invoiceBean->billing_contact_id . '" target="_blank">';
                    $customerType = 'la Persona';
                }
                
                $errorMsg = 'El cliente (' . $customerType . ') no tiene informado el NIF. ';
                if ($customerLink) {
                    $errorMsg .= $customerLink . 'Haga clic aquí para editar el cliente</a> ';
                    $errorMsg .= 'y complete el campo NIF antes de enviar la factura a AEAT.';
                } else {
                    $errorMsg .= 'Seleccione un cliente (Organización o Persona) con NIF informado.';
                }

                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($errorMsg));
                $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
                return;
            }
            // === End customer NIF validation ===

            // === Step 2.10: Validate invoice type coherence ===
            $invoiceTypeValidation = self::validateInvoiceType($invoiceBean);
            if ($invoiceTypeValidation !== true) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice type validation failed: ' . $invoiceTypeValidation);
                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($invoiceTypeValidation));
                $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
                return;
            }
            // === End Step 2.10 ===

            // Prepare rectified invoice data (if applicable)
            $isRectified = !empty($invoiceBean->verifactu_is_rectified_c);
            $rectifiedType = $invoiceBean->verifactu_rectified_type_c ?? null;
            $rectifiedBase = $invoiceBean->verifactu_rectified_base_c ?? null;

            // Get the rectified invoice number from the related invoice
            $rectifiedNumber = null;
            if (!empty($invoiceBean->verifactu_cancel_id_c)) {
                $originalInvoice = BeanFactory::getBean('AOS_Invoices', $invoiceBean->verifactu_cancel_id_c);
                if (!empty($originalInvoice->id)) {
                    $rectifiedNumber = $originalInvoice->number;
                }
            }

            $rectifiedDate = null;
            if (!empty($invoiceBean->verifactu_rectified_date_c)) {
                try {
                    $rectifiedDate = self::parseDateToImmutable($invoiceBean->verifactu_rectified_date_c);
                } catch (Exception $e) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invalid rectified date: ' . $invoiceBean->verifactu_rectified_date_c);
                }
            }

            // For substitution rectified invoices, get the corrected amounts
            $correctedBaseAmount = null;
            $correctedTaxAmount = null;
            if ($isRectified && $rectifiedType === 'S') {
                // Get subtotal (base amount) from the current invoice
                $subtotal = isset($invoiceBean->subtotal_amount) ? floatval($invoiceBean->subtotal_amount) : 0.0;
                // Get tax amount from the current invoice
                $taxAmount = isset($invoiceBean->tax_amount) ? floatval($invoiceBean->tax_amount) : 0.0;

                // Format as string with 2 decimals (required by AEAT)
                $correctedBaseAmount = number_format($subtotal, 2, '.', '');
                $correctedTaxAmount = number_format($taxAmount, 2, '.', '');

                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Corrected amounts for substitution - Base: ' . $correctedBaseAmount . ', Tax: ' . $correctedTaxAmount);
            }

            // === Step 2.8: Block empty invoices ===
            $hasLines = $invoiceBean->get_linked_beans('aos_products_quotes', 'AOS_Products_Quotes');
            if (empty($hasLines)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . ($mod_strings['LBL_INVOICE_EMPTY'] ?? 'La factura no tiene líneas de producto.'));
                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($mod_strings['LBL_INVOICE_EMPTY'] ?? 'La factura no tiene líneas de producto.'));
                $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                unset(self::$processingInvoiceIds[$invoiceId]);
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
                return;
            }
            $subtotal = floatval($invoiceBean->subtotal_amount ?? 0);
            $tax = floatval($invoiceBean->tax_amount ?? 0);
            $isDifferencesRectified = ($isRectified && $rectifiedType === 'I');
            if ($subtotal <= 0 && $tax <= 0 && !$isDifferencesRectified) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . ($mod_strings['LBL_INVOICE_ZERO_AMOUNT'] ?? 'La factura tiene importe cero.'));
                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($mod_strings['LBL_INVOICE_ZERO_AMOUNT'] ?? 'La factura tiene importe cero.'));
                $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                unset(self::$processingInvoiceIds[$invoiceId]);
                SugarApplication::redirect('index.php?module=AOS_Invoices&action=DetailView&record=' . $invoiceBean->id);
                return;
            }
            // === End Step 2.8 ===

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
                $customerName,
                $isRectified,
                $rectifiedType,
                $rectifiedBase,
                $rectifiedNumber,
                $rectifiedDate,
                $correctedBaseAmount,
                $correctedTaxAmount
            );

            // --- DEBUG MODE: volcado de datos antes de enviar ---
            // FORCE DEBUG ALWAYS for design phase
            if (false) {
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
                        $fmtVat = number_format((float) $rawVat, 2, '.', '');
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
                        $invoiceBean->verifactu_check_url_c = $qrUrl;
                        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': QR URL generated: ' . $qrUrl);
                    }
                } else {
                    $invoiceBean->verifactu_aeat_status_c = 'rejected';
                }

                // Save submission date
                if (isset($response->submittedAt)) {
                    $invoiceBean->verifactu_submitted_at_c = $response->submittedAt->format('Y-m-d H:i:s');
                }

                // Save - hooks fire but re-entry guard prevents recursion
                $invoiceBean->save(false);

                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Invoice updated with AEAT response data');

                // === Step 1.6: Add to audit log ===
                $auditTimestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
                $auditLog = $invoiceBean->verifactu_audit_log_c ?? '';
                if (!empty($auditLog)) {
                    $auditLog .= "\n";
                }

                $invoiceNumberForLog = $generatedInvoiceNumber ?? $invoiceBean->number ?? 'N/A';
                $hashForLog = $invoiceBean->verifactu_hash_c ?? 'N/A';
                $statusForLog = $invoiceBean->verifactu_aeat_status_c ?? 'unknown';
                $responseForLog = $invoiceBean->verifactu_aeat_response_c ?? 'N/A';
                $rectifiedFlag = !empty($invoiceBean->verifactu_is_rectified_c) ? ' [RECTIFIED]' : '';

                if ($statusForLog === 'accepted') {
                    $qrForLog = $invoiceBean->verifactu_check_url_c ?? 'N/A';
                    $auditLog .= "[{$auditTimestamp}] Invoice sent to AEAT.{$rectifiedFlag} Number: {$invoiceNumberForLog}, Hash: {$hashForLog}, Status: ACCEPTED. QR: {$qrForLog}";
                } elseif ($statusForLog === 'rejected') {
                    $auditLog .= "[{$auditTimestamp}] Invoice sent to AEAT.{$rectifiedFlag} Number: {$invoiceNumberForLog}, Hash: {$hashForLog}, Status: REJECTED. Response: {$responseForLog}";
                } else {
                    $auditLog .= "[{$auditTimestamp}] Invoice sent to AEAT.{$rectifiedFlag} Number: {$invoiceNumberForLog}, Hash: {$hashForLog}, Status: {$statusForLog}";
                }

                $invoiceBean->verifactu_audit_log_c = $auditLog;
                $invoiceBean->save(false);
                // === End Step 1.6 ===
            }

            // Format response for display
            $debugInfo = $response->debugInfo ?? [];
            $formattedResponse = self::formatAeatResponse($response, $debugInfo);

            // Log the response
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': AEAT Response: ' . $formattedResponse);

            // Show success message with details
            $successMessage = $mod_strings['LBL_AEAT_COMMUNICATION_SUCCESS'];
            if ($invoiceBean->verifactu_aeat_status_c === 'accepted') {
                $successMessage .= $mod_strings['LBL_AEAT_COMMUNICATION_AND_ACCEPTED'];
            }
            $successMessage .= '. <a href="#" onclick="document.getElementById(\'aeat-response-details\').style.display=\'block\'; this.style.display=\'none\'; return false;">' . $mod_strings['LBL_AEAT_SHOW_DETAILS'] . '</a>';
            $successMessage .= '<div id="aeat-response-details" style="display:none; margin-top:10px; padding:10px; background:#f5f5f5; border:1px solid #ddd;"><pre>' . htmlspecialchars($formattedResponse) . '</pre></div>';

            // === Step 1.3: Save generated number only if AEAT send was accepted ===
            if (!empty($generatedInvoiceNumber) && $invoiceBean->verifactu_aeat_status_c === 'accepted') {
                $invoiceBean->number = $generatedInvoiceNumber;
                // Save the exact config key (e.g., "Factura normal"), not the dropdown key (e.g., "factura_no")
                $invoiceBean->stic_invoice_type_c = $seriesConfigKey;
                $invoiceBean->save(false);
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Saved generated invoice number: ' . $generatedInvoiceNumber . ', Series: ' . $seriesConfigKey);
            }
            // === End Step 1.3 ===

            if ($invoiceBean->verifactu_aeat_status_c === 'accepted') {
                SugarApplication::appendSuccessMessage(self::getStyledSuccessAlert($successMessage));
                $sendSuccess = true;

                // If this is a rectified invoice sent successfully, log on the original invoice's audit log
                if (!empty($invoiceBean->verifactu_is_rectified_c) && !empty($invoiceBean->verifactu_cancel_id_c)) {
                    $originalInvoice = BeanFactory::getBean('AOS_Invoices', $invoiceBean->verifactu_cancel_id_c);
                    if (!empty($originalInvoice->id)) {
                        $rectifiedRef = !empty($generatedInvoiceNumber) ? $generatedInvoiceNumber : $invoiceBean->name;
                        $auditTimestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
                        $originalAuditLog = $originalInvoice->verifactu_audit_log_c ?? '';
                        if (!empty($originalAuditLog)) {
                            $originalAuditLog .= "\n";
                        }
                        $originalAuditLog .= "[{$auditTimestamp}] " . str_replace(['{0}', '{1}'], [$rectifiedRef, $invoiceBean->id], $mod_strings['LBL_AUDIT_ORIGINAL_RECTIFIED_SENT']);
                        $auditLogQuoted = $originalInvoice->db->quote($originalAuditLog);
                        $originalInvoice->db->query("UPDATE aos_invoices_cstm SET verifactu_audit_log_c = '{$auditLogQuoted}', verifactu_valid_invoice_c = '0' WHERE id_c = '{$originalInvoice->id}'");
                    }
                }

                // Mark the invoice itself as vigente (1)
                $invoiceBean->db->query("UPDATE aos_invoices_cstm SET verifactu_valid_invoice_c = '1' WHERE id_c = '{$invoiceBean->id}'");
            } else {
                SugarApplication::appendErrorMessage(self::getStyledErrorAlert($mod_strings['LBL_AEAT_SEND_ERROR']));
            }

            return true;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error sending invoice to AEAT: ' . $e->getMessage());

            // Format error for display
            $formattedError = self::formatAeatError($e);

            // Show error message with details
            $errorMessage = $mod_strings['LBL_AEAT_SEND_ERROR'] . ' <a href="#" onclick="document.getElementById(\'aeat-error-details\').style.display=\'block\'; this.style.display=\'none\'; return false;">' . $mod_strings['LBL_AEAT_SHOW_DETAILS'] . '</a>';
            $errorMessage .= '<div id="aeat-error-details" style="display:none; margin-top:10px; padding:10px; background:#f5f5f5; border:1px solid #ddd;"><pre>' . htmlspecialchars($formattedError) . '</pre></div>';

            SugarApplication::appendErrorMessage(self::getStyledErrorAlert($errorMessage));

            return false;
        } finally {
            if (!$sendSuccess && !empty($invoiceBean->id)) {
                $invoiceBean->status = 'draft';
                $db->query("UPDATE aos_invoices SET status='draft' WHERE id='" . $invoiceBean->id . "' AND deleted=0");
                $db->query("UPDATE aos_invoices_cstm SET verifactu_hash_c=NULL, verifactu_previous_hash_c=NULL WHERE id_c='" . $invoiceBean->id . "'");
                $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Reverted invoice ' . $invoiceId . ' status to draft due to send failure.');
            }
            unset(self::$processingInvoiceIds[$invoiceId]);
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
            // and has a verifactu hash stored (custom fields are in aos_invoices_cstm table).
            // Cancelled invoices ARE included because they store the cancellation hash
            // in verifactu_cancel_hash_c, and we use that for proper chain linking.
            $query = "
                SELECT
                    i.id,
                    i.number,
                    i.invoice_date,
                    c.verifactu_hash_c,
                    c.verifactu_cancel_hash_c,
                    c.verifactu_aeat_status_c,
                    c.verifactu_submitted_at_c
                FROM aos_invoices i
                INNER JOIN aos_invoices_cstm c ON i.id = c.id_c
                WHERE i.deleted = 0
                  AND i.id != '" . $db->quote($currentInvoiceId) . "'
                  AND c.verifactu_hash_c IS NOT NULL
                  AND c.verifactu_hash_c != ''
                ORDER BY
                    CASE WHEN c.verifactu_submitted_at_c IS NULL THEN 1 ELSE 0 END ASC,
                    c.verifactu_submitted_at_c DESC,
                    i.invoice_date DESC,
                    i.number DESC
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
                // If previous invoice was cancelled, use the cancellation hash for chaining
                if ($row['verifactu_aeat_status_c'] === 'cancelled' && !empty($row['verifactu_cancel_hash_c'])) {
                    $invoice->verifactu_hash_c = $row['verifactu_cancel_hash_c'];
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Using cancellation hash from previous invoice (was cancelled)');
                } else {
                    $invoice->verifactu_hash_c = $row['verifactu_hash_c'];
                }
                $invoice->verifactu_submitted_at_c = $row['verifactu_submitted_at_c'];

                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found previous invoice: ' . $invoice->number . ' (submitted at: ' . ($invoice->verifactu_submitted_at_c ?? 'N/A') . ')');

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
     * @param string $seriesConfigKey The config key (e.g., 'factura_normal')
     * @param SugarBean $bean The invoice bean
     * @param string $seriesDbValue The database value for query (e.g., 'Factura normal')
     * @return string The generated invoice number
     */
    public static function generateNextInvoiceNumber($seriesConfigKey, $bean, $seriesDbValue = null, $filterByAeatStatus = true)
    {
        global $db, $sugar_config;

        // Use display value for DB query if provided, otherwise use config key
        $seriesForQuery = $seriesDbValue ?? $seriesConfigKey;

        // Get series configuration from sugar_config using config key
        if (empty($sugar_config['aos']['invoices']['series'][$seriesConfigKey])) {
            $GLOBALS['log']->error("generateNextInvoiceNumber - Series '$seriesConfigKey' not found in configuration");
            return '';
        }

        $seriesConfig = $sugar_config['aos']['invoices']['series'][$seriesConfigKey];
        $format = $seriesConfig['format'];
        $initialNumber = isset($seriesConfig['initialNumber']) ? (int) $seriesConfig['initialNumber'] : 1;

        // Get the year from the invoice date or current date
        $invoiceDate = !empty($bean->invoice_date) ? $bean->invoice_date : date('Y-m-d');
        $year = date('Y', strtotime($invoiceDate));
        $yearTwoDigits = substr($year, -2);

        $GLOBALS['log']->debug("generateNextInvoiceNumber - ConfigKey: $seriesConfigKey, DBValue: $seriesForQuery, Format: $format, Initial: $initialNumber, Year: $year, FilterByStatus: " . ($filterByAeatStatus ? 'true' : 'false'));

        // === Step 2.4: Detect if format includes year placeholder ===
        $hasYear = (strpos($format, 'YYYY') !== false || strpos($format, 'YY') !== false);
        // === End Step 2.4 ===

        // === Step 2.2: Validate series format ===
        try {
            self::validateSeriesFormat($format);
        } catch (Exception $e) {
            $GLOBALS['log']->error("generateNextInvoiceNumber - " . $e->getMessage());
            return '';
        }
        // === End Step 2.2 ===

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

        // Find all invoice numbers with the same series (invoice type) and year
        // If filterByAeatStatus is true (Verifactu activated), only consider invoices
        // that have been sent to AEAT (accepted/rejected).
        // If false (legacy mode), consider all non-deleted invoices.
        $query = "SELECT aos_invoices.number
                  FROM aos_invoices
                  INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                  WHERE aos_invoices_cstm.stic_invoice_type_c = " . $db->quoted($seriesForQuery) . "
                  AND aos_invoices.deleted = 0
                  AND aos_invoices.number IS NOT NULL
                  AND aos_invoices.number != ''
                  AND aos_invoices.number LIKE " . $db->quoted($searchPattern);

        if ($filterByAeatStatus) {
            $query .= " AND aos_invoices_cstm.verifactu_aeat_status_c IN ('accepted', 'rejected')";
        }

        // For formats without YYYY/YY, filter by fiscal year to ensure per-year reset
        if (!$hasYear) {
            $query .= " AND YEAR(aos_invoices.invoice_date) = " . $db->quoted($year);
        }

        $query .= " ORDER BY aos_invoices.number DESC LIMIT 1";

        $GLOBALS['log']->debug("generateNextInvoiceNumber - Query: $query");

        $lastNumber = $db->getOne($query);
        $GLOBALS['log']->debug("generateNextInvoiceNumber - ConfigKey: $seriesConfigKey, DBValue: $seriesForQuery, Year: $year, SearchPattern: $searchPattern, LastNumber found: " . ($lastNumber ?? 'NONE'));
        $nextNumber = $initialNumber; // Start with the configured initial number

        if (!empty($lastNumber)) {
            $numericPart = self::extractNumericPart($lastNumber, $format);
            $GLOBALS['log']->debug("generateNextInvoiceNumber - Found invoice: {$lastNumber}, numeric part: $numericPart");
            $nextNumber = intval($numericPart) + 1;
        }

        $GLOBALS['log']->debug("generateNextInvoiceNumber - Next number: $nextNumber");

        // Build the new invoice number
        $generatedNumber = self::buildInvoiceNumber($format, $nextNumber, $year, $yearTwoDigits);
        $GLOBALS['log']->debug("generateNextInvoiceNumber - Generated number: '$generatedNumber'");

        // === Step 2.4: Validate uniqueness for formats without year ===
        if (!$hasYear) {
            $GLOBALS['log']->debug("generateNextInvoiceNumber - Format without year detected, checking uniqueness");
            
            // Keep incrementing until we find a unique number
            $maxAttempts = 1000;
            $attempt = 0;
            
            while ($attempt < $maxAttempts) {
                // Check if this number already exists for this series
                $checkQuery = "SELECT COUNT(*) as cnt
                              FROM aos_invoices
                              INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                              WHERE aos_invoices_cstm.stic_invoice_type_c = " . $db->quoted($seriesForQuery) . "
                              AND aos_invoices.number = " . $db->quoted($generatedNumber) . "
                              AND aos_invoices.deleted = 0";

                if ($filterByAeatStatus) {
                    $checkQuery .= " AND aos_invoices_cstm.verifactu_aeat_status_c IN ('accepted', 'rejected')";
                }

                $checkQuery .= " AND YEAR(aos_invoices.invoice_date) = " . $db->quoted($year);

                $exists = $db->getOne($checkQuery);
                
                if (empty($exists)) {
                    // Number is unique, break the loop
                    break;
                }
                
                // Number already exists, increment and try again
                $nextNumber++;
                $generatedNumber = self::buildInvoiceNumber($format, $nextNumber, $year, $yearTwoDigits);
                $attempt++;
                
                $GLOBALS['log']->debug("generateNextInvoiceNumber - Number '$generatedNumber' exists, trying next: $nextNumber");
            }
            
            if ($attempt >= $maxAttempts) {
                $GLOBALS['log']->error("generateNextInvoiceNumber - Could not find unique number after $maxAttempts attempts");
                return '';
            }
            
            $GLOBALS['log']->debug("generateNextInvoiceNumber - Unique number found after $attempt attempts: '$generatedNumber'");
        }
        // === End Step 2.4 ===

        return $generatedNumber;
    }

    /**
     * Validate series format - only allow valid characters
     * 
     * Problem: Normative non-compliance 4.23 - format characters not validated
     * 
     * Allowed characters: A-Z, 0-9, hyphen (-), underscore (_), slash (/), dot (.), space
     * Allowed placeholders: YYYY, YY, 000+ (numeric sequence)
     * 
     * @param string $format The format pattern to validate
     * @return bool True if valid, throws Exception if invalid
     * @throws Exception If format contains invalid characters
     */
    public static function validateSeriesFormat($format)
    {
        // Check for lowercase letters (not allowed by AEAT)
        if (preg_match('/[a-z]/', $format)) {
            throw new Exception("El formato de serie no puede contener letras minúsculas. Formato: $format");
        }
        
        // Check for invalid characters (AEAT only allows A-Z, 0-9, hyphen, underscore, slash, dot, space)
        if (preg_match('/[^A-Z0-9\-_\/. ]/', $format)) {
            throw new Exception("El formato de serie contiene caracteres no permitidos. Solo se permiten: mayúsculas (A-Z), números (0-9), guión (-), guión bajo (_), barra (/), punto (.) y espacio. Formato: $format");
        }
        
        // Check for spaces at the beginning
        if (preg_match('/^ /', $format)) {
            throw new Exception("El formato de serie no puede empezar con un espacio. Formato: $format");
        }
        
        // Validate numeric placeholders (0 sequence)
        preg_match_all('/0+/', $format, $matches);
        foreach ($matches[0] as $numericPlaceholder) {
            if (strlen($numericPlaceholder) > 20) {
                throw new Exception("El formato de serie no puede tener más de 20 dígitos en la secuencia numérica. Formato: $format");
            }
        }

        // Must include YYYY or YY, and at least 2 consecutive zeros
        if ((strpos($format, 'YYYY') === false && strpos($format, 'YY') === false) || !preg_match('/0{2,}/', $format)) {
            throw new Exception("El formato de serie debe incluir un año (YYYY o YY) y al menos 2 ceros consecutivos para la numeración secuencial. Ejemplo: YYYY-0000 o YY-000. Formato: $format");
        }
        
        $GLOBALS['log']->debug("validateSeriesFormat - Format '$format' is valid");
        return true;
    }

    /**
     * Validate series type consistency with invoice type
     * 
     * Problem: Normative non-compliance 4.25 - Allow creating rectified invoice with normal series and vice versa
     * 
     * @param SugarBean $bean The invoice bean
     * @return bool True if valid, error message if invalid
     */
    public static function validateSeriesType($bean)
    {
        global $sugar_config, $mod_strings;

        // Only validate if invoice has series and isRectified flag
        if (empty($bean->stic_invoice_type_c)) {
            return true;
        }

        $seriesKey = $bean->stic_invoice_type_c;
        $isRectified = !empty($bean->verifactu_is_rectified_c);

        // Map dropdown key to config key
        $seriesMapping = [
            'factura_no' => 'Factura Normal',
            'factura_si' => 'Factura rectificativa',
            'Factura Normal' => 'Factura Normal',
            'Factura rectificativa' => 'Factura rectificativa'
        ];
        
        // Convert bean key to config key
        $seriesName = isset($seriesMapping[$seriesKey]) ? $seriesMapping[$seriesKey] : $seriesKey;
        
        $GLOBALS['log']->debug("validateSeriesType - Bean key: '$seriesKey', Config key: '$seriesName', isRectified: " . ($isRectified ? 'true' : 'false'));

        // Get series config
        if (empty($sugar_config['aos']['invoices']['series'][$seriesName])) {
            $GLOBALS['log']->debug("validateSeriesType - Series '$seriesName' not found in config, skipping validation");
            return true;
        }

        $seriesConfig = $sugar_config['aos']['invoices']['series'][$seriesName];
        $seriesIsRectified = !empty($seriesConfig['isRectified']);
        
        $GLOBALS['log']->debug("validateSeriesType - Series config: isRectified=" . ($seriesIsRectified ? 'true' : 'false'));

        // Check consistency
        if ($isRectified && !$seriesIsRectified) {
            $errorMsg = $mod_strings['LBL_VERIFACTU_SERIES_TYPE_MISMATCH_RECTIFIED'] 
                ?? "No puede seleccionar la serie '{$seriesName}' para una factura rectificativa. Esta serie no está configurada como serie rectificativa.";
            return $errorMsg;
        }

        if (!$isRectified && $seriesIsRectified) {
            $errorMsg = $mod_strings['LBL_VERIFACTU_SERIES_TYPE_MISMATCH_NORMAL'] 
                ?? "No puede seleccionar la serie '{$seriesName}' para una factura normal. Esta serie está configurada como serie rectificativa.";
            return $errorMsg;
        }

        $GLOBALS['log']->debug("validateSeriesType - Series type validation passed: seriesIsRectified=" . ($seriesIsRectified ? 'true' : 'false') . ", invoiceIsRectified=" . ($isRectified ? 'true' : 'false'));
        return true;
    }

    /**
     * Step 2.6: Validate series names uniqueness in configuration
     *
     * Since series are defined in config_override.php as an array with keys as series names,
     * duplicates would silently overwrite previous entries. This method logs a warning
     * if duplicate series names are detected.
     *
     * @return bool True if no duplicates found, false if duplicates exist
     */
    public static function validateSeriesUniqueness()
    {
        global $sugar_config;
        
        if (empty($sugar_config['aos']['invoices']['series'])) {
            return true;
        }
        
        $series = $sugar_config['aos']['invoices']['series'];
        $seriesNames = array_keys($series);
        $uniqueNames = array_unique($seriesNames);
        
        if (count($seriesNames) !== count($uniqueNames)) {
            $duplicates = array_diff_assoc($seriesNames, $uniqueNames);
            $duplicateList = implode(', ', array_keys($duplicates));
            $GLOBALS['log']->error("validateSeriesUniqueness - Duplicate series names detected in config_override.php: [$duplicateList]. Series keys must be unique - duplicates will silently overwrite previous entries.");
            return false;
        }
        
        $GLOBALS['log']->debug("validateSeriesUniqueness - All " . count($seriesNames) . " series names are unique");
        return true;
    }

    /**
     * Step 2.7: Check if series format can be modified
     *
     * Block format modification if there are already issued invoices (accepted by AEAT)
     * for this series. According to Verifactu regulations, format should remain consistent.
     *
     * @param string $seriesName The series name to check
     * @return bool True if format can be modified, false if blocked
     */
    public static function canModifySeriesFormat($seriesName)
    {
        global $db;
        
        if (empty($seriesName)) {
            return true;
        }
        
        $query = "SELECT COUNT(*) as cnt
                  FROM aos_invoices
                  INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                  WHERE aos_invoices_cstm.stic_invoice_type_c = " . $db->quoted($seriesName) . "
                  AND aos_invoices.deleted = 0
                  AND aos_invoices_cstm.verifactu_aeat_status_c = 'accepted'";
        
        $count = $db->getOne($query);
        
        if (!empty($count)) {
            $GLOBALS['log']->error("canModifySeriesFormat - Cannot modify series '$seriesName': $count invoices already accepted by AEAT");
            return false;
        }
        
        $GLOBALS['log']->debug("canModifySeriesFormat - Series '$seriesName' can be modified: no accepted invoices");
        return true;
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
     * Validate chronological order of invoice date per series
     * 
     * Problem: Incidences 4.2, 4.3, 4.20 - Date validation only runs at send time and doesn't consider series
     * 
     * @param SugarBean $bean The invoice bean
     * @return bool True if valid, false if error (error message set in bean)
     */
    public static function validateChronologicalOrder($bean)
    {
        global $db, $sugar_config, $mod_strings;

        // Only validate if invoice has a date and series
        if (empty($bean->invoice_date) || empty($bean->stic_invoice_type_c)) {
            return true;
        }

        // Get the series from the bean
        $seriesName = $bean->stic_invoice_type_c;
        
        // Get the current invoice date
        $currentDate = strtotime($bean->invoice_date);
        
        // Find the last issued invoice from the same series (excluding cancelled)
        $query = "SELECT aos_invoices.id, aos_invoices.invoice_date
                  FROM aos_invoices
                  INNER JOIN aos_invoices_cstm ON aos_invoices.id = aos_invoices_cstm.id_c
                  WHERE aos_invoices_cstm.stic_invoice_type_c = " . $db->quoted($seriesName) . "
                  AND aos_invoices.deleted = 0
                  AND aos_invoices.id != " . $db->quoted($bean->id) . "
                  AND aos_invoices_cstm.verifactu_aeat_status_c = 'accepted'
                  AND aos_invoices_cstm.verifactu_aeat_status_c != 'cancelled'
                  AND aos_invoices.invoice_date IS NOT NULL
                  ORDER BY aos_invoices.invoice_date DESC
                  LIMIT 1";

        $GLOBALS['log']->debug("validateChronologicalOrder - Query: $query");

        $lastInvoice = $db->fetchOne($query);

        if (!empty($lastInvoice) && !empty($lastInvoice['invoice_date'])) {
            $lastDate = strtotime($lastInvoice['invoice_date']);
            
            // If current invoice date is earlier than the last issued invoice
            if ($currentDate < $lastDate) {
                $lastDateFormatted = date('d/m/Y', $lastDate);
                $currentDateFormatted = date('d/m/Y', $currentDate);
                
                $errorMsg = $mod_strings['LBL_VERIFACTU_DATE_BEFORE_LAST'] 
                    ?? "La fecha de expedición ({$currentDateFormatted}) es anterior a la última factura emitida de la serie {$seriesName} ({$lastDateFormatted}).";
                
                $GLOBALS['log']->error("Line " . __LINE__ . ": " . __METHOD__ . ": " . $errorMsg);
                
                return $errorMsg;
            }
        }

        $GLOBALS['log']->debug("validateChronologicalOrder - Date validation passed for series: $seriesName, date: " . date('d/m/Y', $currentDate));
        return true;
    }

    /**
     * Step 2.10: Validate coherence between invoice type and rectification flag
     * 
     * Rules:
     * - If verifactu_is_rectified_c = 1, series must have isRectified = true
     * - If verifactu_is_rectified_c = 0 or empty, series must have isRectified = false
     * 
     * @param object $bean The invoice bean
     * @return bool|string True if valid, error message string if invalid
     */
    public static function validateInvoiceType($bean)
    {
        global $mod_strings, $sugar_config;

        $isRectified = !empty($bean->verifactu_is_rectified_c);
        $seriesName = $bean->stic_invoice_type_c ?? '';

        // Get the series configuration
        $seriesConfig = $sugar_config['aos']['invoices']['series'][$seriesName] ?? null;
        
        if (empty($seriesConfig)) {
            $GLOBALS['log']->error(__METHOD__ . ': Series not found in config: ' . $seriesName);
            return true; // Let other validation handle this
        }

        $seriesIsRectified = !empty($seriesConfig['isRectified']);

        if ($isRectified && !$seriesIsRectified) {
            // Invoice is marked as rectified but series is not rectified
            $GLOBALS['log']->error(__METHOD__ . ': Rectified invoice uses non-rectified series: ' . $seriesName);
            return $mod_strings['LBL_VERIFACTU_INVOICE_TYPE_RECTIFIED_MISMATCH']
                ?? "La factura está marcada como rectificativa pero la serie seleccionada ({$seriesName}) no es una serie rectificativa. Seleccione una serie rectificativa o desmarque la opción '¿Es factura rectificativa?'.";
        }

        if (!$isRectified && $seriesIsRectified) {
            // Invoice is not rectified but series is rectified
            $GLOBALS['log']->error(__METHOD__ . ': Normal invoice uses rectified series: ' . $seriesName);
            return $mod_strings['LBL_VERIFACTU_INVOICE_TYPE_NORMAL_MISMATCH']
                ?? "La factura no está marcada como rectificativa pero la serie seleccionada ({$seriesName}) es una serie rectificativa. Seleccione una serie normal o marque la opción '¿Es factura rectificativa?'.";
        }

        $GLOBALS['log']->debug(__METHOD__ . ': Invoice type validation passed - isRectified: ' . ($isRectified ? 'yes' : 'no') . ', series: ' . $seriesName . ', seriesIsRectified: ' . ($seriesIsRectified ? 'yes' : 'no'));
        return true;
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

    /**
     * Send cancellation record to AEAT
     *
     * @param AOS_Invoices $invoiceBean Invoice to cancel
     * @return array Response from AEAT with status and message
     */
    public static function sendCancellationToAeat($invoiceBean)
    {
        global $sugar_config;

        // Check if Verifactu is activated - if not, skip (legacy mode)
        if (!self::isVerifactuActivated()) {
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Verifactu not activated (legacy mode), skipping sendCancellationToAeat.');
            return ['success' => false, 'message' => 'Verifactu no está activado.'];
        }

        try {
            // --- Validate invoice can be cancelled ---
            if (empty($invoiceBean->id)) {
                throw new Exception('Invoice ID is required');
            }

            // Check invoice is accepted by AEAT
            if ($invoiceBean->verifactu_aeat_status_c !== 'accepted') {
                throw new Exception('Invoice must be accepted by AEAT before cancellation');
            }

            // Check invoice has required verifactu fields
            if (empty($invoiceBean->verifactu_hash_c) || empty($invoiceBean->verifactu_previous_hash_c)) {
                throw new Exception('Invoice must have verifactu hash and previous hash');
            }

            $originalHash = $invoiceBean->verifactu_hash_c;
            $originalCsv = $invoiceBean->verifactu_csv_c;
            $originalSubmittedAt = $invoiceBean->verifactu_submitted_at_c ?? null;

            // --- Get configuration ---
            // Load certificate utilities
            require_once 'custom/include/SticCertificateUtils.php';

            // Get certificate components
            $certComponents = SticCertificateUtils::getCertificateComponents();
            if (!$certComponents) {
                throw new Exception("Certificate not found or could not be decrypted. Please upload a certificate in Administration > Digital Certificate.");
            }

            // Extract NIF and holder name from certificate
            $issuerNif = SticCertificateUtils::getCertificateNif();
            $issuerName = SticCertificateUtils::getCertificateHolderName();

            if (empty($issuerNif) || empty($issuerName)) {
                throw new Exception("Could not extract NIF or holder name from certificate. Please verify the certificate is valid.");
            }

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate data loaded - NIF: ' . $issuerNif . ', Name: ' . $issuerName);

            // Get certificate type (entity seal or representative) from certificate itself
            $certificateType = SticCertificateUtils::isEntitySeal();

            // Get other settings from stic_Settings module
            require_once 'modules/stic_Settings/Utils.php';
            $useProduction = (stic_SettingsUtils::getSetting('VERIFACTU_TEST') == 1 ? false : true);

            // --- Create Computer System ---
            $system = self::buildComputerSystem($issuerNif, $issuerName);

            // --- Create Taxpayer ---
            $taxpayer = new FiscalIdentifier($issuerName, $issuerNif);

            // --- Get previous record in chain (for chaining) ---
            // Uses getPreviousInvoice() which searches by submitted_at date.
            // For cancelled invoices, it uses verifactu_cancel_hash_c (not verifactu_hash_c)
            // to maintain proper chain linking.
            $previousInvoice = self::getPreviousInvoice($invoiceBean->id);

            $previousInvoiceId = null;
            $previousHash = null;

            if ($previousInvoice) {
                $previousInvoiceId = new InvoiceIdentifier();
                $previousInvoiceId->issuerId = $issuerNif;
                $previousInvoiceId->invoiceNumber = $previousInvoice->number;
                $previousInvoiceId->issueDate = self::parseDateToImmutable($previousInvoice->invoice_date);
                $previousHash = $previousInvoice->verifactu_hash_c;
            }

            // --- Create Cancellation Record ---
            $cancellationRecord = new CancellationRecord();

            // Set invoice identifier (invoice to cancel)
            $cancellationRecord->invoiceId = new InvoiceIdentifier();
            $cancellationRecord->invoiceId->issuerId = $issuerNif;
            $cancellationRecord->invoiceId->invoiceNumber = $invoiceBean->number;
            $cancellationRecord->invoiceId->issueDate = self::parseDateToImmutable($invoiceBean->invoice_date);

            // Set chaining info (previous invoice in the chain)
            $cancellationRecord->previousInvoiceId = $previousInvoiceId;
            $cancellationRecord->previousHash = $previousHash;

            // Generate hash
            $cancellationRecord->hashedAt = new DateTimeImmutable();
            $cancellationRecord->hash = $cancellationRecord->calculateHash();

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Cancellation record created for invoice: ' . $invoiceBean->number);

            // --- Send to AEAT ---
            $client = new AeatClient($system, $taxpayer);

            // Configure certificate type (Entity Seal vs Personal)
            $client->setEntitySeal((bool) $certificateType);

            // Get certificate components (already extracted as PEM - NO PASSWORD NEEDED!)
            $certificate = $certComponents['certificate'];
            $privateKey = $certComponents['private_key'];
            $caChain = $certComponents['ca_chain'];

            // Build PEM content (Certificate + Private Key + CA Chain)
            $cleanPemBlock = function ($str) {
                if (preg_match('/(-----BEGIN (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----.*?-----END (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----)/s', $str, $matches)) {
                    return trim($matches[1]);
                }
                return trim($str);
            };

            // Order: Certificate -> Private Key -> CA Chain
            $pemContent = $cleanPemBlock($certificate) . "\n" . $cleanPemBlock($privateKey);

            // Add CA chain if exists
            if (!empty($caChain)) {
                $pemContent .= "\n" . $cleanPemBlock($caChain);
            }

            // Save to temporary file (AEAT client requires file path)
            $tempPemFile = tempnam(sys_get_temp_dir(), 'stic_verifactu_cancel_');
            file_put_contents($tempPemFile, $pemContent);

            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Temporary PEM file created: ' . $tempPemFile);

            // Set certificate in AEAT client (NO PASSWORD NEEDED!)
            $client->setCertificate($tempPemFile, null);

            // Configure environment (pre-production or production)
            $client->setProduction($useProduction);

            $response = $client->send([$cancellationRecord])->wait();

            // Clean up temporary file
            if (file_exists($tempPemFile)) {
                unlink($tempPemFile);
            }

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Cancellation sent to AEAT. CSV: ' . $response->csv);

            // --- Update invoice with cancellation info ---
            // Store the CancellationRecord hash in verifactu_cancel_hash_c (NOT in verifactu_hash_c).
            // This preserves the original registration hash for audit purposes.
            // The getPreviousInvoice() method uses verifactu_cancel_hash_c for cancelled invoices
            // to maintain proper Verifactu chain: RegistrationAlta -> RegistrationAnulacion -> next RegistrationAlta.
            $auditLines = [];
            $auditLines[] = '=== Auditoria Verifactu ===';
            $auditLines[] = 'Fecha: ' . (new DateTimeImmutable())->format('Y-m-d H:i:s');
            $auditLines[] = 'Operacion: Anulacion';
            $auditLines[] = 'Hash original: ' . (!empty($originalHash) ? $originalHash : 'N/D');
            $auditLines[] = 'CSV original: ' . (!empty($originalCsv) ? $originalCsv : 'N/D');
            if (!empty($originalSubmittedAt)) {
                $auditLines[] = 'Fecha envio original: ' . $originalSubmittedAt;
            }
            $auditLines[] = 'Hash anulacion: ' . $cancellationRecord->hash;
            $auditLines[] = 'CSV anulacion: ' . ($response->csv ?? 'N/D');

            $existingDescription = (string) ($invoiceBean->description ?? '');
            $descriptionSeparator = $existingDescription === '' ? '' : "\n\n";
            // Store audit log in the dedicated field instead of description
            $auditTimestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
            $auditLog = $invoiceBean->verifactu_audit_log_c ?? '';
            if (!empty($auditLog)) {
                $auditLog .= "\n";
            }
            $auditLog .= "[{$auditTimestamp}] Cancellation sent to AEAT. CSV: {$response->csv}. Cancellation hash: {$cancellationRecord->hash}. Original hash preserved: {$invoiceBean->verifactu_hash_c}";
            $invoiceBean->verifactu_audit_log_c = $auditLog;
            
            // Mark this as a cancellation operation to bypass edit blocking in before_save
            $invoiceBean->_is_cancellation = true;
            
            // Preserve original hash and store cancellation hash in separate field
            $invoiceBean->verifactu_cancel_hash_c = $cancellationRecord->hash;
            $invoiceBean->verifactu_aeat_status_c = 'cancelled';
            $invoiceBean->verifactu_valid_invoice_c = '0';
            $invoiceBean->verifactu_aeat_response_c = 'Factura anulada en AEAT. CSV: ' . $response->csv;
            $invoiceBean->verifactu_csv_c = $response->csv;
            if (isset($response->submittedAt)) {
                $invoiceBean->verifactu_submitted_at_c = $response->submittedAt->format('Y-m-d H:i:s');
            } else {
                $invoiceBean->verifactu_submitted_at_c = (new DateTimeImmutable())->format('Y-m-d H:i:s');
            }
            $invoiceBean->save();

            return [
                'success' => true,
                'csv' => $response->csv,
                'message' => 'Factura anulada correctamente en AEAT',
            ];

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error sending cancellation to AEAT: ' . $e->getMessage());

            // Update invoice with error
            if (!empty($invoiceBean->id)) {
                $invoiceBean->verifactu_aeat_status_c = 'error';
                $invoiceBean->verifactu_aeat_response_c = 'Error al anular: ' . $e->getMessage();
                $invoiceBean->save();
            }

            return [
                'success' => false,
                'message' => 'Error al anular la factura: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get styled alert HTML for error messages.
     *
     * @param string $message The error message
     * @return string Styled alert HTML
     */
    public static function getStyledErrorAlert($message)
    {
        return '<div class="alert alert-danger" style="margin: 10px 0; padding: 12px; border-left: 4px solid #d9534f; background-color: #f2dede;">' . $message . '</div>';
    }

    /**
     * Get styled alert HTML for success messages.
     *
     * @param string $message The success message
     * @return string Styled alert HTML
     */
    public static function getStyledSuccessAlert($message)
    {
        return '<div class="alert alert-success" style="margin: 10px 0; padding: 12px; border-left: 4px solid #5cb85c; background-color: #dff0d8;">' . $message . '</div>';
    }

    /**
     * Check if invoice series exist and create default ones if missing.
     * At least one normal series and one rectified series are required.
     * Uses Configurator to save config_override.php.
     *
     * @return array Status with 'created' (bool) and 'message' (string)
     */
    public static function ensureDefaultSeries()
    {
        static $executed = false;
        if ($executed) {
            return ['created' => false, 'message' => ''];
        }
        $executed = true;

        global $sugar_config;
        $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');

        $series = $sugar_config['aos']['invoices']['series'] ?? [];
        $hasNormal = false;
        $hasRectified = false;

        foreach ($series as $name => $config) {
            if (!empty($config['isRectified'])) {
                $hasRectified = true;
            } else {
                $hasNormal = true;
            }
        }

        if ($hasNormal && $hasRectified) {
            return ['created' => false, 'message' => ''];
        }

        $defaultSeries = [];
        $created = [];

        if (!$hasNormal) {
            $defaultSeries['Factura normal'] = [
                'format' => 'YYYY-0000',
                'initialNumber' => 1,
                'isRectified' => false,
            ];
            $created[] = $mod_strings['LBL_STIC_SERIES_NORMAL_NAME'];
        }

        if (!$hasRectified) {
            $defaultSeries['Factura rectificativa'] = [
                'format' => 'RECT-YYYY-0000',
                'initialNumber' => 1,
                'isRectified' => true,
            ];
            $created[] = $mod_strings['LBL_STIC_SERIES_RECTIFIED_NAME'];
        }

        $newLines = '';
        foreach ($defaultSeries as $seriesName => $seriesData) {
            $safeName = addslashes($seriesName);
            $safeFormat = addslashes($seriesData['format']);
            $isRectified = $seriesData['isRectified'] ? 'true' : 'false';
            $newLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['format'] = '{$safeFormat}';\n";
            $newLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['initialNumber'] = {$seriesData['initialNumber']};\n";
            $newLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['isRectified'] = {$isRectified};\n";
        }

        $configFile = 'config_override.php';
        $configContent = file_get_contents($configFile);

        $configContent = preg_replace(
            "/\\\$sugar_config\\['aos'\\]\\['invoices'\\]\\['series'\\].*?;\n/s",
            '',
            $configContent
        );

        $configContent = preg_replace("/\n{3,}/", "\n\n", $configContent);

        $marker = '/***CONFIGURATOR***/';
        $firstPos = strpos($configContent, $marker);
        $secondPos = ($firstPos !== false) ? strpos($configContent, $marker, $firstPos + strlen($marker)) : false;

        if ($secondPos !== false) {
            $configContent = substr_replace($configContent, $newLines, $secondPos, 0);
        } elseif ($firstPos !== false) {
            $configContent = substr_replace($configContent, $newLines, $firstPos, 0);
        } else {
            $configContent = rtrim($configContent) . "\n" . $newLines;
        }

        file_put_contents($configFile, $configContent);

        if (file_exists('config_override.php')) {
            include 'config_override.php';
        }

        $seriesItems = '';
        foreach ($created as $name) {
            $format = $defaultSeries[$name]['format'] ?? 'YYYY-0000';
            $seriesItems .= '<div style="margin:0 0 8px 20px;font-size:14px;">• '
                . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' → <em>' . htmlspecialchars($format, ENT_QUOTES, 'UTF-8') . '</em></div>';
        }
        $message = '<div style="font-family:inherit;">'
            . '<p style="margin:0 0 12px 0;font-size:14px;font-weight:bold;text-align:center;">'
            . $mod_strings['LBL_STIC_SERIES_AUTO_CREATED'] . '</p>'
            . $seriesItems
            . '<p style="margin:0 0 12px 0;font-size:13px;color:#555;">'
            . $mod_strings['LBL_STIC_SERIES_FORMAT_INFO'] . '</p>'
            . '<p style="margin:0;font-size:12px;color:#999;">'
            . $mod_strings['LBL_STIC_SERIES_AUTO_CREATED_ADMIN'] . '</p>'
            . '</div>';

        return ['created' => true, 'message' => $message];
    }

    /**
     * Query AEAT Verifactu for registered invoices.
     */
    public static function queryAeatInvoices(
        string $year,
        string $period,
        ?string $serieNumber = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $counterpartyNif = null,
        ?string $counterpartyName = null,
        bool $filterBySif = true,
    ): array {
        global $sugar_config;

        if (!self::isVerifactuActivated()) {
            return [
                'success' => false,
                'message' => 'Verifactu no está activado.',
            ];
        }

        try {
            require_once 'custom/include/SticCertificateUtils.php';

            $certComponents = SticCertificateUtils::getCertificateComponents();
            if (!$certComponents) {
                return [
                    'success' => false,
                    'message' => 'Certificado no encontrado. Por favor, cargue un certificado en Administración > Certificado Digital.',
                ];
            }

            $issuerNif = SticCertificateUtils::getCertificateNif();
            $issuerName = SticCertificateUtils::getCertificateHolderName();

            if (empty($issuerNif) || empty($issuerName)) {
                return [
                    'success' => false,
                    'message' => 'No se pudo extraer el NIF o nombre del certificado.',
                ];
            }

            $certificateType = SticCertificateUtils::isEntitySeal();

            require_once 'modules/stic_Settings/Utils.php';
            $useProduction = (stic_SettingsUtils::getSetting('VERIFACTU_TEST') == '1' ? false : true);

            require_once 'custom/modules/AOS_Invoices/SticAeatQueryClient.php';
            $system = self::buildComputerSystem($issuerNif, $issuerName);
            $taxpayer = new FiscalIdentifier($issuerName, $issuerNif);

            $consultaClient = new SticAeatQueryClient($system, $taxpayer);

            $consultaClient->setEntitySeal((bool) $certificateType);

            $certificate = $certComponents['certificate'];
            $privateKey = $certComponents['private_key'];
            $caChain = $certComponents['ca_chain'];

            $cleanPemBlock = function ($str) {
                if (preg_match('/(-----BEGIN (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----.*?-----END (?:CERTIFICATE|.*?PRIVATE KEY.*?)-----)/s', $str, $matches)) {
                    return trim($matches[1]);
                }
                return trim($str);
            };

            $pemContent = $cleanPemBlock($certificate) . "\n" . $cleanPemBlock($privateKey);
            if (!empty($caChain)) {
                $pemContent .= "\n" . $cleanPemBlock($caChain);
            }

            $tempPemFile = tempnam(sys_get_temp_dir(), 'stic_verifactu_cons_');
            file_put_contents($tempPemFile, $pemContent);

            $consultaClient->setCertificate($tempPemFile, null);
            $consultaClient->setProduction($useProduction);

            $result = $consultaClient->query(
                $year,
                $period,
                $serieNumber,
                $dateFrom,
                $dateTo,
                $counterpartyName,
                $counterpartyNif,
                $filterBySif ? $system : null,
            );

            unlink($tempPemFile);

            return [
                'success' => true,
                'data' => $result,
            ];
        } catch (\Throwable $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error querying AEAT: ' . $e->getMessage());
            if (isset($tempPemFile) && file_exists($tempPemFile)) {
                unlink($tempPemFile);
            }

            if (empty($mod_strings)) {
                $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
            }

            $msg = $e->getMessage();
            $isProduction = isset($useProduction) ? $useProduction : true;
            $mode = $mod_strings[$isProduction ? 'LBL_VERIFACTU_MODE_PRODUCTION' : 'LBL_VERIFACTU_MODE_TEST'];
            $endpoint = $mod_strings[$isProduction ? 'LBL_VERIFACTU_ENDPOINT_PRODUCTION' : 'LBL_VERIFACTU_ENDPOINT_TEST'];

            if ($e instanceof \GuzzleHttp\Exception\ConnectException) {
                $userMsg = sprintf($mod_strings['LBL_VERIFACTU_QUERY_ERROR_CONNECT'], $mode, $endpoint, $msg);
            } elseif ($e instanceof \GuzzleHttp\Exception\ClientException || $e instanceof \GuzzleHttp\Exception\ServerException) {
                $code = $e->getResponse() ? $e->getResponse()->getStatusCode() : '?';
                $userMsg = sprintf($mod_strings['LBL_VERIFACTU_QUERY_ERROR_HTTP'], $code, $msg);
            } elseif ($e instanceof \josemmo\Verifactu\Exceptions\AeatException) {
                $userMsg = sprintf($mod_strings['LBL_VERIFACTU_QUERY_ERROR_AEAT'], $msg);
            } elseif (stripos($msg, 'certificate') !== false || stripos($msg, 'SSL') !== false || stripos($msg, 'certificad') !== false) {
                $userMsg = sprintf($mod_strings['LBL_VERIFACTU_QUERY_ERROR_CERTIFICATE'], $msg);
            } else {
                $userMsg = sprintf($mod_strings['LBL_VERIFACTU_QUERY_ERROR_GENERIC'], $mode, $msg);
            }

            return [
                'success' => false,
                'message' => $userMsg,
            ];
        }
    }

    /**
     * Check and create default series if missing.
     * Returns the message HTML if series were created, null otherwise.
     * To be called from view preDisplay() methods.
     */
    public static function checkAndDisplaySeriesBanner()
    {
        $result = self::ensureDefaultSeries();
        if ($result['created']) {
            return $result['message'];
        }
        return null;
    }

}

/**
 * Helper function to show styled error message and redirect.
 *
 * @param string $message The error message
 * @param string $redirectUrl URL to redirect to (default: index page)
 */
function sticShowErrorAndRedirect($message, $redirectUrl = 'index.php?module=AOS_Invoices&action=index')
{
    SugarApplication::appendErrorMessage(AOS_InvoicesUtils::getStyledErrorAlert($message));
    header('Location: ' . $redirectUrl);
    exit;
}
