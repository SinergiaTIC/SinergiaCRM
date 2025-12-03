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
            global $sugar_config;
            $key = $sugar_config['unique_key'];
            
            $certContent = blowfishDecode($key, $encryptedContent);
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
