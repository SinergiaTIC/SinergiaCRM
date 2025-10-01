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

global $sugar_config;

// EntryPoint to download a signed PDF
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
// require_once 'include/entryPoint.php';

// Check for required parameters
if (!isset($_REQUEST['signerId']) || empty($_REQUEST['signerId'])) {
    sugar_die('No signerId received');
}

$signerId = $_REQUEST['signerId'];
$signerBean = BeanFactory::getBean('stic_Signers', $signerId);
if (!$signerBean) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Signer with ID $signerId not found");
    sugar_die('Signer not found');
}

// Construct the filename using the signer's ID
$fileName = "{$signerId}_signed.pdf";
$filePath = $sugar_config['upload_dir'] . $fileName;

// Check if the file exists and is readable
if (!file_exists($filePath) || !is_readable($filePath)) {
    sugar_die('File not found or is not accessible.');
}

require_once 'modules/stic_Signature_Log/Utils.php';
stic_SignatureLogUtils::logSignatureAction('SIGNED_PDF_DOWNLOADED', $signerBean->id, 'SIGNER', "Signed PDF downloaded for signer {$signerBean->name} (ID: {$signerBean->name})");



// Set headers for file download
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $signerBean->name . '.pdf"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Clear output buffer and read the file
ob_clean();
flush();
readfile($filePath);
exit;
