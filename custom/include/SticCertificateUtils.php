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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Utility class for managing digital certificates
 */
class SticCertificateUtils
{
    /**
     * Base path for certificate files (fallback for backward compatibility)
     */
    const CERT_DIR = 'custom/certificates/';

    /**
     * Path to the certificate metadata file (fallback for backward compatibility)
     */
    const METADATA_FILE_PATH = 'custom/certificates/cert_metadata.json';

    /**
     * Config category for certificate storage
     */
    const CONFIG_CATEGORY = 'SticCertificates';

    /**
     * Config keys for certificate components
     */
    const CONFIG_KEY_PRIVATE_KEY = 'private_key';
    const CONFIG_KEY_CERTIFICATE = 'certificate';
    const CONFIG_KEY_CA_CHAIN = 'ca_chain';
    const CONFIG_KEY_METADATA = 'metadata';

    /**
     * Save certificate components to config table
     *
     * @param array $components Array with 'private_key', 'certificate', 'ca_chain'
     * @param array $metadata Array with certificate metadata
     * @return bool Success status
     */
    public static function saveCertificateToConfig($components, $metadata = array())
    {
        global $db;

        try {
            // Save private key
            self::saveConfigValue(self::CONFIG_KEY_PRIVATE_KEY, $components['private_key'] ?? '');

            // Save certificate
            self::saveConfigValue(self::CONFIG_KEY_CERTIFICATE, $components['certificate'] ?? '');

            // Save CA chain
            self::saveConfigValue(self::CONFIG_KEY_CA_CHAIN, $components['ca_chain'] ?? '');

            // Save metadata as JSON
            self::saveConfigValue(self::CONFIG_KEY_METADATA, json_encode($metadata));

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate saved to config table successfully.');
            return true;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error saving certificate to config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Load certificate components from config table
     * Handles both encrypted (legacy) and unencrypted (new) formats
     *
     * @return array|null Array with 'private_key', 'certificate', 'ca_chain', or null if not found
     */
    public static function loadCertificateFromConfig()
    {
        $privateKey = self::loadConfigValue(self::CONFIG_KEY_PRIVATE_KEY);
        $certificate = self::loadConfigValue(self::CONFIG_KEY_CERTIFICATE);
        $caChain = self::loadConfigValue(self::CONFIG_KEY_CA_CHAIN);

        if (empty($privateKey) || empty($certificate)) {
            return null;
        }

        // Try to detect if data is encrypted (starts with valid PEM header after base64 decode)
        // If not valid PEM, try to decrypt
        $privateKey = self::ensureDecrypted($privateKey, 'private_key');
        $certificate = self::ensureDecrypted($certificate, 'certificate');
        $caChain = !empty($caChain) ? self::ensureDecrypted($caChain, 'ca_chain') : '';

        return array(
            'private_key' => $privateKey,
            'certificate' => $certificate,
            'ca_chain' => $caChain,
        );
    }

    /**
     * Ensure data is decrypted (PEM format). If encrypted, decrypt it.
     *
     * @param string $data The data to check/decrypt
     * @param string $type Type of data ('private_key', 'certificate', 'ca_chain')
     * @return string Decrypted data in PEM format
     */
    private static function ensureDecrypted($data, $type)
    {
        // Check if data appears to be valid PEM format
        if (self::isValidPem($data)) {
            return $data;
        }

        // Data might be encrypted - try to decrypt
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $type . ' appears encrypted, attempting decryption.');

        try {
            require_once 'include/Pear/Crypt_Blowfish/Blowfish.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];

            $bf = new Crypt_Blowfish($key);

            // Decrypt (data is base64 encoded before encryption)
            $decoded = base64_decode($data);
            if ($decoded !== false) {
                $decrypted = $bf->decrypt($decoded);
                if ($decrypted !== false && self::isValidPem($decrypted)) {
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $type . ' decrypted successfully.');
                    return $decrypted;
                }
            }
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error decrypting ' . $type . ': ' . $e->getMessage());
        }

        // If decryption failed or data is not valid PEM, return original
        return $data;
    }

    /**
     * Check if data appears to be valid PEM format
     *
     * @param string $data The data to check
     * @return bool True if data looks like valid PEM
     */
    private static function isValidPem($data)
    {
        if (empty($data) || !is_string($data)) {
            return false;
        }

        // Check for PEM headers
        if (strpos($data, '-----BEGIN ') !== false && strpos($data, '-----END ') !== false) {
            return true;
        }

        return false;
    }

    /**
     * Load certificate metadata from config table
     *
     * @return array|null Certificate metadata or null if not found
     */
    public static function loadMetadataFromConfig()
    {
        $metadataJson = self::loadConfigValue(self::CONFIG_KEY_METADATA);
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Raw metadata from config, length: ' . strlen($metadataJson ?? ''));
        
        if (empty($metadataJson)) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Metadata is empty');
            return null;
        }

        // Try to parse as JSON directly (new format)
        $decoded = json_decode($metadataJson, true);
        if ($decoded !== null) {
            return $decoded;
        }
        
        // Try with HTML entity decode (data might have been stored with HTML entities)
        $decoded = json_decode(html_entity_decode($metadataJson, ENT_QUOTES, 'UTF-8'), true);
        if ($decoded !== null) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Metadata parsed successfully after html_entity_decode');
            return $decoded;
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Metadata is not valid JSON, attempting decryption.');

        // Metadata might be encrypted (legacy format) - try to decrypt
        try {
            require_once 'include/Pear/Crypt_Blowfish/Blowfish.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];

            $bf = new Crypt_Blowfish($key);

            // Decrypt (data is base64 encoded before encryption)
            $decodedData = base64_decode($metadataJson);
            if ($decodedData !== false) {
                $decrypted = $bf->decrypt($decodedData);
                if ($decrypted !== false) {
                    $decoded = json_decode($decrypted, true);
                    if ($decoded !== null) {
                        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Metadata decrypted successfully.');
                        return $decoded;
                    } else {
                        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Decrypted data is not valid JSON: ' . substr($decrypted, 0, 200));
                    }
                } else {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Blowfish decryption returned false');
                }
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Base64 decode failed');
            }
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error decrypting metadata: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Delete certificate from config table
     *
     * @return bool Success status
     */
    public static function deleteCertificateFromConfig()
    {
        global $db;

        try {
            // Delete all certificate config entries
            $db->query("DELETE FROM config WHERE category = " . $db->quoted(self::CONFIG_CATEGORY));

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate deleted from config table.');
            return true;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error deleting certificate from config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if certificate exists in config table
     *
     * @return bool
     */
    public static function certificateExistsInConfig()
    {
        $privateKey = self::loadConfigValue(self::CONFIG_KEY_PRIVATE_KEY);
        $certificate = self::loadConfigValue(self::CONFIG_KEY_CERTIFICATE);
        
        return !empty($privateKey) && !empty($certificate);
    }

    /**
     * Save a single config value
     *
     * @param string $name Config name
     * @param string $value Config value
     */
    private static function saveConfigValue($name, $value)
    {
        global $db;

        // Delete existing value first
        $db->query("DELETE FROM config WHERE category = " . $db->quoted(self::CONFIG_CATEGORY) . " AND name = " . $db->quoted($name));

        // Insert new value
        $db->query("INSERT INTO config (category, name, value) VALUES (" . $db->quoted(self::CONFIG_CATEGORY) . ", " . $db->quoted($name) . ", " . $db->quoted($value) . ")");
    }

    /**
     * Load a single config value
     *
     * @param string $name Config name
     * @return string|null Config value or null if not found
     */
    private static function loadConfigValue($name)
    {
        global $db;

        $result = $db->getOne("SELECT value FROM config WHERE category = " . $db->quoted(self::CONFIG_CATEGORY) . " AND name = " . $db->quoted($name));
        
        return $result !== false ? $result : null;
    }

    /**
     * Get the certificate and private key components (ready to use)
     * Components are stored in config table without encryption
     *
     * @return array|null Array with 'certificate', 'private_key', 'ca_chain' (all in PEM format), or null if not found
     */
    public static function getCertificateComponents()
    {
        // Try to load from config first
        $components = self::loadCertificateFromConfig();

        // Fallback to file system for backward compatibility (with decryption)
        if ($components === null) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate not found in config, trying file system fallback.');
            $components = self::getCertificateComponentsFromFiles();
        }

        if ($components === null) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components not found in config or files.');
            return null;
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components loaded successfully.');
        return $components;
    }

    /**
     * Get certificate components from file system (fallback for backward compatibility)
     * Files are stored encrypted with Blowfish, so we need to decrypt them
     *
     * @return array|null Array with 'certificate', 'private_key', 'ca_chain', or null if not found
     */
    private static function getCertificateComponentsFromFiles()
    {
        $privateKeyFile = self::CERT_DIR . 'private_key_encrypted.bin';
        $certificateFile = self::CERT_DIR . 'certificate_encrypted.bin';
        $caChainFile = self::CERT_DIR . 'ca_chain_encrypted.bin';

        // Check if required files exist
        if (!file_exists($privateKeyFile) || !file_exists($certificateFile)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate files not found.');
            return null;
        }

        try {
            // Read encrypted files
            $encryptedPrivateKey = file_get_contents($privateKeyFile);
            $encryptedCertificate = file_get_contents($certificateFile);
            $encryptedCaChain = file_exists($caChainFile) ? file_get_contents($caChainFile) : '';

            // Decrypt using Blowfish
            require_once 'include/Pear/Crypt_Blowfish/Blowfish.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];

            $bf = new Crypt_Blowfish($key);

            // Decrypt private key
            $privateKeyData = base64_decode($encryptedPrivateKey);
            $privateKey = $bf->decrypt($privateKeyData);

            // Decrypt certificate
            $certificateData = base64_decode($encryptedCertificate);
            $certificate = $bf->decrypt($certificateData);

            // Decrypt CA chain if exists
            $caChain = '';
            if (!empty($encryptedCaChain)) {
                $caChainData = base64_decode($encryptedCaChain);
                $caChain = $bf->decrypt($caChainData);
            }

            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components decrypted from files.');

            return array(
                'private_key' => $privateKey,
                'certificate' => $certificate,
                'ca_chain' => $caChain,
            );

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error decrypting certificate components from files: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * DEPRECATED: This method is kept for backward compatibility but should not be used
     * Use getCertificateComponents() instead
     *
     * @deprecated Use getCertificateComponents() instead
     * @return array|null
     */
    public static function getCertificateAndPassword()
    {
        $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': This method is deprecated. Use getCertificateComponents() instead.');
        
        // For backward compatibility, return components without password
        $components = self::getCertificateComponents();
        if (!$components) {
            return null;
        }

        // Return in old format but without password
        return array(
            'cert_content' => $components['certificate'],
            'private_key' => $components['private_key'],
            'ca_chain' => $components['ca_chain'],
            'password' => null, // No longer needed
            'metadata' => self::getCertificateMetadata()
        );
    }

    /**
     * Check if a certificate exists
     *
     * @return bool
     */
    public static function certificateExists()
    {
        // Check config first
        if (self::certificateExistsInConfig()) {
            return true;
        }

        // Fallback to file system
        $privateKeyFile = self::CERT_DIR . 'private_key_encrypted.bin';
        $certificateFile = self::CERT_DIR . 'certificate_encrypted.bin';
        return file_exists($privateKeyFile) && file_exists($certificateFile) && file_exists(self::METADATA_FILE_PATH);
    }

    /**
     * Get certificate metadata without decrypting the certificate
     *
     * @return array|null
     */
    public static function getCertificateMetadata()
    {
        // Try to load from config first
        $metadata = self::loadMetadataFromConfig();
        if ($metadata !== null) {
            return $metadata;
        }

        // Fallback to file system
        if (!file_exists(self::METADATA_FILE_PATH)) {
            return null;
        }

        $metadataJson = file_get_contents(self::METADATA_FILE_PATH);
        return json_decode($metadataJson, true);
    }

    /**
     * Get parsed certificate information
     * This method reads and parses the certificate from PEM format
     *
     * @return array|null Array with certificate details or null on error
     */
    public static function getParsedCertificateInfo()
    {
        $components = self::getCertificateComponents();
        if (!$components) {
            return null;
        }

        try {
            // Parse the certificate (PEM format)
            $certDetails = openssl_x509_parse($components['certificate']);
            
            if ($certDetails === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to parse certificate.');
                return null;
            }
            
            return $certDetails;
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error parsing certificate: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract NIF/CIF from certificate
     * Tries multiple fields where NIF can be stored in the certificate
     *
     * @return string|null The NIF/CIF or null if not found
     */
    public static function getCertificateNif()
    {
        $components = self::getCertificateComponents();
        if (!$components) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components not available.');
            return null;
        }

        try {
            // Parse the certificate (PEM format)
            $certDetails = openssl_x509_parse($components['certificate']);
            
            if ($certDetails === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to parse certificate.');
                return null;
            }
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate subject fields: ' . print_r($certDetails['subject'], true));
            
            // PRIORITY 1: Try organizationIdentifier (OID 2.5.4.97) - Contains the entity NIF
            // This is used in representative certificates where there are two NIFs:
            // - serialNumber: Representative's personal NIF
            // - organizationIdentifier: Entity's NIF (this is what we need for Verifactu)
            if (!empty($certDetails['subject']['organizationIdentifier'])) {
                $nif = $certDetails['subject']['organizationIdentifier'];
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found organizationIdentifier (entity NIF): ' . $nif);
                
                // Clean prefixes like "VATES-", "IDCES-"
                $nif = preg_replace('/^[A-Z]+-/i', '', $nif);
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Using entity NIF from organizationIdentifier: ' . $nif);
                return strtoupper($nif);
            }
            
            // PRIORITY 2: Try serialNumber field (used in entity seal certificates without organizationIdentifier)
            if (!empty($certDetails['subject']['serialNumber'])) {
                $nif = $certDetails['subject']['serialNumber'];
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found serialNumber: ' . $nif);
                
                // Clean common prefixes like "IDCES-", "VATES-", etc.
                $nif = preg_replace('/^(IDCES-|VATES-|ES)/i', '', $nif);
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Using NIF from serialNumber: ' . $nif);
                
                return strtoupper($nif);
            }

            // Try CN if it contains a NIF pattern (8-9 characters: letter+digits+letter or digits+letter)
            if (!empty($certDetails['subject']['CN'])) {
                $cn = $certDetails['subject']['CN'];
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Trying to extract NIF from CN: ' . $cn);
                
                // Look for NIF pattern in CN (CIF: letter + 7-8 digits + letter, or NIF: 8 digits + letter)
                if (preg_match('/([A-Z]?\d{7,8}[A-Z])/i', $cn, $matches)) {
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Extracted NIF from CN: ' . $matches[1]);
                    return strtoupper($matches[1]);
                }
            }

            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': NIF not found in certificate subject fields. Available fields: ' . implode(', ', array_keys($certDetails['subject'])));
            return null;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error extracting NIF from certificate: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract holder name from certificate
     * Tries CN (Common Name), O (Organization), and GN+SN (Given Name + Surname) fields
     *
     * @return string|null The holder name or null if not found
     */
    public static function getCertificateHolderName()
    {
        $components = self::getCertificateComponents();
        if (!$components) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components not available.');
            return null;
        }

        try {
            // Parse the certificate (PEM format)
            $certDetails = openssl_x509_parse($components['certificate']);
            
            if ($certDetails === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to parse certificate.');
                return null;
            }
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate subject fields: ' . print_r($certDetails['subject'], true));
            
            // Try to get name from Organization field first (most reliable for company certificates)
            if (!empty($certDetails['subject']['O'])) {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found name in O field: ' . $certDetails['subject']['O']);
                return $certDetails['subject']['O'];
            }

            // Try Common Name and clean NIF/CIF if present at the end
            if (!empty($certDetails['subject']['CN'])) {
                $name = $certDetails['subject']['CN'];
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found name in CN field: ' . $name);
                
                // Remove NIF/CIF pattern from the end (e.g., " - 99999910G")
                $name = preg_replace('/\s*-?\s*[A-Z]?\d{7,8}[A-Z]\s*$/i', '', $name);
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Cleaned name: ' . $name);
                
                return trim($name);
            }

            // Try combining Given Name + Surname (for personal certificates)
            $givenName = $certDetails['subject']['GN'] ?? '';
            $surname = $certDetails['subject']['SN'] ?? '';
            
            if (!empty($givenName) && !empty($surname)) {
                $name = trim($givenName . ' ' . $surname);
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Composed name from GN+SN: ' . $name);
                return $name;
            }

            // Try organizationName (alternative field)
            if (!empty($certDetails['subject']['organizationName'])) {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Found name in organizationName field: ' . $certDetails['subject']['organizationName']);
                return $certDetails['subject']['organizationName'];
            }

            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Holder name not found in certificate subject fields. Available fields: ' . implode(', ', array_keys($certDetails['subject'])));
            return null;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error extracting holder name from certificate: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Determine if certificate is an entity seal (sello de entidad) or representative certificate
     * Entity seal certificates (1) are for legal entities, representative certificates (0) are for individuals
     *
     * @return int|null 1 if entity seal, 0 if representative, null if cannot determine
     */
    public static function isEntitySeal()
    {
        $components = self::getCertificateComponents();
        if (!$components) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components not available.');
            return null;
        }

        try {
            // Parse the certificate (PEM format)
            $certDetails = openssl_x509_parse($components['certificate']);
            
            if ($certDetails === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to parse certificate.');
                return null;
            }
            
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Checking certificate type. Subject: ' . print_r($certDetails['subject'], true));
            
            // Check if certificate has GN (Given Name) or SN (Surname) fields
            // These are typical of personal/representative certificates
            if (!empty($certDetails['subject']['GN']) || !empty($certDetails['subject']['SN'])) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate has GN/SN fields - Representative certificate (0)');
                return 0; // Representative certificate (personal)
            }

            // Check if certificate has Organization field but no Given Name/Surname
            // This is typical of entity seal certificates
            if (!empty($certDetails['subject']['O']) && empty($certDetails['subject']['GN']) && empty($certDetails['subject']['SN'])) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate has O field but no GN/SN - Entity seal certificate (1)');
                return 1; // Entity seal certificate
            }

            // Check CN for keywords that indicate entity seal
            if (!empty($certDetails['subject']['CN'])) {
                $cn = strtoupper($certDetails['subject']['CN']);
                
                // Common patterns for entity seals
                $entityPatterns = array(
                    '/SELLO\s+DE?\s+ENTIDAD/i',
                    '/ENTIDAD/i',
                    '/SELLO\s+ELECTRONICO/i',
                    '/SEAL/i',
                    '/\bS\.A\b/i',
                    '/\bS\.L\b/i',
                    '/\bS\.L\.U\b/i',
                );
                
                foreach ($entityPatterns as $pattern) {
                    if (preg_match($pattern, $cn)) {
                        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': CN matches entity pattern: ' . $pattern . ' - Entity seal certificate (1)');
                        return 1; // Entity seal certificate
                    }
                }
                
                // Common patterns for representative certificates
                $representativePatterns = array(
                    '/REPRESENTANTE/i',
                    '/REPRESENTACION/i',
                    '/PERSONA\s+FISICA/i',
                );
                
                foreach ($representativePatterns as $pattern) {
                    if (preg_match($pattern, $cn)) {
                        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': CN matches representative pattern: ' . $pattern . ' - Representative certificate (0)');
                        return 0; // Representative certificate
                    }
                }
            }

            // If we can't determine, log warning and return null
            $GLOBALS['log']->warn('Line ' . __LINE__ . ': ' . __METHOD__ . ': Cannot determine certificate type. Available subject fields: ' . implode(', ', array_keys($certDetails['subject'])));
            return null;

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error determining certificate type: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the certificate is valid (not expired)
     *
     * @return bool|null True if valid, false if expired, null if can't be determined
     */
    public static function isCertificateValid()
    {
        $metadata = self::getCertificateMetadata();
        if (!$metadata || empty($metadata['cert_details']['valid_to'])) {
            return null;
        }

        $validTo = strtotime($metadata['cert_details']['valid_to']);
        return $validTo > time();
    }

    /**
     * Example usage for signing a document (to be implemented in your signing logic)
     * NO PASSWORD NEEDED - certificate components are already extracted
     * 
     * @param string $documentPath Path to the document to sign
     * @return bool Success status
     */
    public static function signDocument($documentPath)
    {
        // Get certificate components (NO PASSWORD NEEDED!)
        $components = self::getCertificateComponents();
        if (!$components) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components not available.');
            return false;
        }

        // Now you have direct access to (all in PEM format):
        // - $components['certificate'] : The X.509 certificate
        // - $components['private_key'] : The private key
        // - $components['ca_chain'] : CA chain certificates (if any)

        // TODO: Implement your signing logic here
        // Example: Use openssl_pkcs7_sign() or other signing methods
        // openssl_pkcs7_sign($documentPath, $signedPath, $components['certificate'], $components['private_key'], array());
        
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate components loaded successfully for signing.');
        return true;
    }
}
