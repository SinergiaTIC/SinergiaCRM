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
     * Path to the encrypted certificate file
     */
    const CERT_FILE_PATH = 'custom/certificates/cert_encrypted.bin';
    
    /**
     * Path to the certificate metadata file
     */
    const METADATA_FILE_PATH = 'custom/certificates/cert_metadata.json';

    /**
     * Retrieve the decrypted certificate content and password
     *
     * @return array|null Array with 'cert_content' and 'password', or null if certificate doesn't exist
     */
    public static function getCertificateAndPassword()
    {
        if (!file_exists(self::CERT_FILE_PATH) || !file_exists(self::METADATA_FILE_PATH)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate or metadata file not found.');
            return null;
        }

        try {
            // 1. Load encrypted certificate
            $encryptedContent = file_get_contents(self::CERT_FILE_PATH);
            
            // 2. Load metadata
            $metadataJson = file_get_contents(self::METADATA_FILE_PATH);
            $metadata = json_decode($metadataJson, true);
            
            if (empty($metadata['encrypted_password'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Encrypted password not found in metadata.');
                return null;
            }

            // 3. Decrypt certificate and password
            require_once 'include/utils/encryption_utils.php';
            require_once 'include/Pear/Crypt_Blowfish/Blowfish.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];
            
            // Manual decryption for certificate to avoid trim() corrupting binary P12
            // blowfishDecode() uses trim() which removes whitespace and nulls, damaging binary files
            $data = base64_decode($encryptedContent);
            $bf = new Crypt_Blowfish($key);
            $certContent = $bf->decrypt($data);
            // DO NOT TRIM - OpenSSL handles the ASN.1 structure and ignores trailing padding
            
            // Decrypt password (safe to use blowfishDecode for text)
            $password = blowfishDecode($key, $metadata['encrypted_password']);

            return array(
                'cert_content' => $certContent,
                'password' => $password,
                'metadata' => $metadata
            );

        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error retrieving certificate: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if a certificate exists
     *
     * @return bool
     */
    public static function certificateExists()
    {
        return file_exists(self::CERT_FILE_PATH) && file_exists(self::METADATA_FILE_PATH);
    }

    /**
     * Get certificate metadata without decrypting the certificate
     *
     * @return array|null
     */
    public static function getCertificateMetadata()
    {
        if (!file_exists(self::METADATA_FILE_PATH)) {
            return null;
        }

        $metadataJson = file_get_contents(self::METADATA_FILE_PATH);
        return json_decode($metadataJson, true);
    }

    /**
     * Get parsed certificate information from the PKCS12 file
     * This method actually reads and parses the certificate
     *
     * @return array|null Array with certificate details or null on error
     */
    public static function getParsedCertificateInfo()
    {
        $certData = self::getCertificateAndPassword();
        if (!$certData) {
            return null;
        }

        try {
            $certInfo = array();
            if (openssl_pkcs12_read($certData['cert_content'], $certInfo, $certData['password'])) {
                $certDetails = openssl_x509_parse($certInfo['cert']);
                return $certDetails;
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to parse certificate.');
                return null;
            }
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
        $certData = self::getCertificateAndPassword();
        if (!$certData) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate data not available.');
            return null;
        }

        try {
            $certInfo = array();
            if (!openssl_pkcs12_read($certData['cert_content'], $certInfo, $certData['password'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to read certificate.');
                return null;
            }

            $certDetails = openssl_x509_parse($certInfo['cert']);
            
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
        $certData = self::getCertificateAndPassword();
        if (!$certData) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate data not available.');
            return null;
        }

        try {
            $certInfo = array();
            if (!openssl_pkcs12_read($certData['cert_content'], $certInfo, $certData['password'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to read certificate.');
                return null;
            }

            $certDetails = openssl_x509_parse($certInfo['cert']);
            
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
        $certData = self::getCertificateAndPassword();
        if (!$certData) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate data not available.');
            return null;
        }

        try {
            $certInfo = array();
            if (!openssl_pkcs12_read($certData['cert_content'], $certInfo, $certData['password'])) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to read certificate.');
                return null;
            }

            $certDetails = openssl_x509_parse($certInfo['cert']);
            
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
     * 
     * @param string $documentPath Path to the document to sign
     * @return bool Success status
     */
    public static function signDocument($documentPath)
    {
        // Get certificate and password
        $certData = self::getCertificateAndPassword();
        if (!$certData) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate not available.');
            return false;
        }

        // Extract PKCS12 contents
        $pkcs12Content = array();
        if (!openssl_pkcs12_read($certData['cert_content'], $pkcs12Content, $certData['password'])) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to read PKCS12 certificate.');
            return false;
        }

        // Now you have:
        // - $pkcs12Content['cert'] : The certificate
        // - $pkcs12Content['pkey'] : The private key
        // - $pkcs12Content['extracerts'] : Extra certificates (CA chain)

        // TODO: Implement your signing logic here
        // Example: Use openssl_pkcs7_sign() or other signing methods
        
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate loaded successfully for signing.');
        return true;
    }
}
