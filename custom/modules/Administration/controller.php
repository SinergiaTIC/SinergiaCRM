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

require_once 'modules/Administration/controller.php';
class CustomAdministrationController extends AdministrationController
{

    /**
     * Populate the database with example records. All records are assigned and created by the fake user
     * "SinergiaCRM-TEST", which is also created with a special id (9) that will be used
     * in case of later deletion.
     *
     * @return void
     */
    public function action_insertSticData()
    {

        global $mod_strings;

        $db = DBManagerFactory::getInstance();

        // Load from an external file the queries to run.
        $sqlPopulate = file_get_contents('SticInclude/data/InsertSticData.sql');

        // As DBManagerFactory does not allow more than one SQL sentence in the same query,
        // will execute them in a loop.
        $sqlPopulate = explode('REPLACE INTO', $sqlPopulate);

        $dbErrors = '';

        foreach ($sqlPopulate as $key => $value) {
            if (empty(trim($value))) {
                continue;
            }
            $sql = 'REPLACE INTO ' . $value;
            $db->query($sql);
            $dbErrors .= $db->last_error;
        }

        if (empty($dbErrors)) {
            SugarApplication::appendErrorMessage('<div class="alert alert-success">' . $mod_strings['LBL_STIC_TEST_DATA_INSERT_SUCCESS'] . '</div>');
        } else {
            SugarApplication::appendErrorMessage('<div class="alert alert-danger">' . $mod_strings['LBL_STIC_TEST_DATA_INSERT_ERROR'] . '</div>');
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error populating data: ' . $dbErrors);

        }
        SugarApplication::redirect('index.php?module=Administration&action=SticManageTestData');
    }

    /**
     * Remove test records (those created by user with id 9) and the fake user.
     *
     * @return void
     */
    public function action_removeSticData($showMessage = true)
    {
        global $mod_strings;
        $removeId = '9';
        $db = DBManagerFactory::getInstance();

        // Build an array with the database tables to be cleaned
        $tableListResult = $db->query("SELECT table_name FROM information_schema.COLUMNS where table_schema = database() and COLUMN_NAME = 'created_by';");

        $dbErrors = '';

        while ($row = $db->fetchByAssoc($tableListResult)) {
            $table = $row['table_name'];

            // 1) Remove main table records
            $db->query("DELETE FROM {$table} WHERE created_by='{$removeId}';");
            $dbErrors .= $db->last_error;

            // 2) Remove orphan record in _cstm table, if exists
            $cstmTableExists = $db->getOne("SELECT count(*) FROM information_schema.TABLES where table_schema=database() and TABLE_NAME='{$table}_cstm'");
            if ($cstmTableExists == 1) {
                $db->query("DELETE FROM `{$table}_cstm` WHERE id_c NOT IN (SELECT id FROM {$table});");
                $dbErrors .= $db->last_error;
            }
        }

        // Delete user with $removeId
        $db->query("DELETE FROM users WHERE id = '{$removeId}';");
        $dbErrors .= $db->last_error;

        if ($showMessage == true) {
            if (empty($dbErrors)) {
                SugarApplication::appendErrorMessage('<div class="alert alert-success">' . $mod_strings['LBL_STIC_TEST_DATA_REMOVE_SUCCESS'] . '</div>');
            } else {
                SugarApplication::appendErrorMessage('<div class="alert alert-danger">' . $mod_strings['LBL_STIC_TEST_DATA_REMOVE_ERROR'] . '</div>');
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error removing test data: ' . $dbErrors);

            }
            SugarApplication::redirect('index.php?module=Administration&action=SticManageTestData');
        }
    }

    public function action_createReportingMySQLViews()
    {
        global $sugar_config;
        $sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;

        if (empty($sdaEnabled) || !$sdaEnabled) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA is disabled");
            die("Sinergia Data Analytics is disabled.");
            return;
        }

        global $current_user, $mod_strings;
        if (is_admin($current_user)) {
            require_once 'SticInclude/SinergiaDARebuild.php';
            $res = SinergiaDARebuild::rebuild(true, $_REQUEST['rebuild_filter'] ?? 'all');

            if ($res != 'ok') {
                $tx = "<script>$(window).on('load', function() {
                    $('#rebuild-feedback').html('<div class=\"container alert alert-danger\"> <div class=\"col-md-1\"><span style=\"font-size:xx-large\" class=\"col-md-1 glyphicon glyphicon-minus-sign center\"></span></div> <strong>{$mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG']}:</strong><p>{$res}</p></div>');
                });</script>";
            } else {
                $tx = "<script>$(window).on('load', function() {
                    $('#rebuild-feedback').html('<div class=\"container alert alert-success\"> <div class=\"col-md-1\"><span style=\"font-size:xx-large\" class=\"col-md-1 glyphicon glyphicon-check center\"></span></div><div class=\"col-md-11\"><strong>{$mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG']}</strong></div></div>');
                });</script>";
            }

            SugarApplication::appendSuccessMessage($tx);
            SugarApplication::redirect("index.php?module=Administration&action=sticmanagesdaintegration");

            die();
        } else {
            die('<h1>Operación restringida a administradores</h1>');

        }

    }

    public function action_configureMainMenu()
    {
        // Add specific logic for manage main menu
        require_once 'custom/modules/Administration/SticAdvancedMenu/SticAdvancedMenuEdit.php';

    }

    /**
     * Handle the upload, encryption, and storage of the digital certificate.
     *
     * @return void
     */
    public function action_SticSaveCertificate()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die("Not authorized access.");
        }

        if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {

            // 1. Read file content
            $fileContent = file_get_contents($_FILES['certificate_file']['tmp_name']);
            
            // 2. Get and validate certificate password
            $password = $_POST['certificate_password'] ?? '';
            
            if (empty($password)) {
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_PASSWORD_REQUIRED');
                return;
            }

            // 3. Verify password and extract certificate information
            $certInfo = array();
            if (!openssl_pkcs12_read($fileContent, $certInfo, $password)) {
                // Invalid password or corrupted certificate
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to read certificate. Invalid password or corrupted file.');
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_INVALID_PASSWORD');
                return;
            }

            // 4. Extract certificate details for metadata
            $certData = openssl_x509_parse($certInfo['cert']);
            $certDetails = array(
                'subject' => $certData['name'] ?? '',
                'issuer' => $certData['issuer']['CN'] ?? '',
                'valid_from' => isset($certData['validFrom_time_t']) ? date('Y-m-d H:i:s', $certData['validFrom_time_t']) : '',
                'valid_to' => isset($certData['validTo_time_t']) ? date('Y-m-d H:i:s', $certData['validTo_time_t']) : '',
                'serial_number' => $certData['serialNumberHex'] ?? $certData['serialNumber'] ?? '',
            );

            // 5. Extract components from PKCS12 (certificate and private key in PEM format)
            $certificate = $certInfo['cert'];      // X.509 certificate (PEM format)
            $privateKey = $certInfo['pkey'];       // Private key (PEM format)
            $caChain = $certInfo['extracerts'] ?? array(); // CA chain certificates

            // Validate extracted components
            if (empty($certificate) || empty($privateKey)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to extract certificate or private key from PKCS12.');
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_INVALID_PASSWORD');
                return;
            }

            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Extracted certificate: ' . strlen($certificate) . ' bytes, private key: ' . strlen($privateKey) . ' bytes');

            // 6. Prepare Blowfish encryption
            require_once 'include/utils/encryption_utils.php';
            global $sugar_config;
            $key = $sugar_config['unique_key'];

            // 7. Encrypt ONLY the certificate components (NO password storage needed!)
            // The private key is the most sensitive part
            $encryptedPrivateKey = blowfishEncode($key, $privateKey);
            $encryptedCertificate = blowfishEncode($key, $certificate);
            
            // Validate encryption
            if (empty($encryptedPrivateKey) || empty($encryptedCertificate)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to encrypt certificate components.');
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_WRITE');
                return;
            }
            
            // Encrypt CA chain if exists
            $encryptedCaChain = '';
            if (!empty($caChain)) {
                $caChainPem = '';
                foreach ($caChain as $caCert) {
                    $caChainPem .= $caCert . "\n";
                }
                $encryptedCaChain = blowfishEncode($key, $caChainPem);
            }

            // 8. Define secure path
            $uploadDir = 'custom/certificates/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to create certificate directory: ' . $uploadDir);
                    SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_WRITE');
                    return;
                }
                // Create .htaccess to prevent web access
                file_put_contents($uploadDir . '.htaccess', "Order Deny,Allow\nDeny from all");
            }

            // Verify directory is writable
            if (!is_writable($uploadDir)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate directory is not writable: ' . $uploadDir);
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_WRITE');
                return;
            }

            // 9. Save encrypted components separately (NO password needed for future use!)
            $privateKeyFile = $uploadDir . 'private_key_encrypted.bin';
            $certificateFile = $uploadDir . 'certificate_encrypted.bin';
            $caChainFile = $uploadDir . 'ca_chain_encrypted.bin';
            $metadataFile = $uploadDir . 'cert_metadata.json';

            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Saving private key to: ' . $privateKeyFile);

            // Save private key
            $bytesWritten = file_put_contents($privateKeyFile, $encryptedPrivateKey);
            if ($bytesWritten === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to write private key file: ' . $privateKeyFile);
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_WRITE');
                return;
            }
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Private key saved: ' . $bytesWritten . ' bytes');

            // Save certificate
            $bytesWritten = file_put_contents($certificateFile, $encryptedCertificate);
            if ($bytesWritten === false) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to write certificate file: ' . $certificateFile);
                SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_WRITE');
                return;
            }
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate saved: ' . $bytesWritten . ' bytes');

            // Save CA chain if exists
            if (!empty($encryptedCaChain)) {
                file_put_contents($caChainFile, $encryptedCaChain);
            }

            // 10. Save metadata (WITHOUT password - no longer needed!)
            $metadata = array(
                'original_filename' => $_FILES['certificate_file']['name'],
                'upload_date' => date('Y-m-d H:i:s'),
                'uploaded_by' => $current_user->id,
                'uploaded_by_name' => $current_user->name,
                'cert_details' => $certDetails,
                'has_ca_chain' => !empty($caChain),
            );
            file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT));

            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': Certificate uploaded successfully. Components stored separately (no password needed for future use).');

            // Redirect successfully to the view
            SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_SUCCESS');

        } else {
            // Error uploading
            SugarApplication::redirect('index.php?module=Administration&action=SticManageCertificate&msg=LBL_STIC_CERT_ERROR_UPLOAD');
        }
    }

}
