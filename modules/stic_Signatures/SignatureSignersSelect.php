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

/**
 * This script processes the selection of records to be added as signers
 * for a specific signature process. It handles fetching record IDs,
 * checking for existing signers, creating new signer records,
 * and establishing relationships with the signature.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings;

$bean = BeanFactory::getBean($_REQUEST['module']);

if (!$bean) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invalid Module: " . $_REQUEST['module']);
    sugar_die("Invalid Module");
}

$signatureId = $_REQUEST['signature-id'] ?? '';
if (empty($signatureId)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature ID is empty.");
    sugar_die("Signature ID is required");
}

$recordIds = array();

// Determine record IDs based on mass update context or direct selection
if (isset($_REQUEST['current_post']) && $_REQUEST['current_post'] !== '') {
    $order_by = '';
    require_once 'include/MassUpdate.php';
    $mass = new MassUpdate();
    $mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_post']);
    $ret_array = create_export_query_relate_link_patch($_REQUEST['module'], $mass->searchFields, $mass->where_clauses);
    $query = $bean->create_export_query($order_by, $ret_array['where'], $ret_array['join']);
    $result = DBManagerFactory::getInstance()->query($query, true);
    $uids = array();
    while ($val = DBManagerFactory::getInstance()->fetchByAssoc($result, false)) {
        $recordIds[] = $val['id'];
    }
} else {
    $recordIds = explode(',', $_REQUEST['uid']);
}

if (empty($recordIds)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": No record IDs found.");
    sugar_die("No records selected");
}

require_once 'modules/stic_Signatures/Utils.php';

$stic_SignatureBean = BeanFactory::getBean('stic_Signatures', $signatureId);

// Obtain destination signers based on the signature configuration and selected records
$destSigners = stic_SignaturesUtils::getSignatureSigners($signatureId, $recordIds);

// Query the database directly to find existing stic_Signers records
// associated with the current signatureId to prevent duplicates.
$SQL = "SELECT ss.parent_id as id
            FROM stic_signatures s
            JOIN stic_signatures_stic_signers_c ssssc ON s.id = ssssc.stic_signatures_stic_signersstic_signatures_ida AND ssssc.deleted = 0
            JOIN stic_signers ss ON ss.id = ssssc.stic_signatures_stic_signersstic_signers_idb AND ss.deleted = 0
            WHERE s.deleted = 0
            AND s.id = '{$signatureId}'";
$result = DBManagerFactory::getInstance()->query($SQL, true);
$existingSigners = array();
while ($row = DBManagerFactory::getInstance()->fetchByAssoc($result, false)) {
    $existingSigners[] = $row['id'];
}

$okCounter = 0;
$koCounter = 0;

// Process each destination signer
foreach ($destSigners as $destSignerId => $destSigner) {
    $destSignerBean = BeanFactory::getBean($destSigner['module'], $destSignerId);
    if (!$destSignerBean) {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not obtain signer data for ID: " . $destSignerId);
        $koCounter++;
        continue;
    }
    // Skip if the signer already exists for this signature
    if (in_array($destSignerId, $existingSigners)) {
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Skipping existing signer with ID: " . $destSignerId);
        $koCounter++;
        continue;
    }

    // Create a new stic_Signer bean
    $stic_SignerBean = BeanFactory::newBean('stic_Signers');
    $stic_SignerBean->name = "{$destSignerBean->full_name} - {$stic_SignatureBean->name}";
    $stic_SignerBean->parent_type = $destSigner['module'];
    $stic_SignerBean->parent_id = $destSignerId;
    $stic_SignerBean->parent_name = $destSignerBean->full_name;
    $stic_SignerBean->record_id = $destSigner['sourceId'];
    $stic_SignerBean->email_address = $destSigner['email'];
    $stic_SignerBean->phone = $destSigner['phone'];
    $stic_SignerBean->status = 'pending';
    // $stic_SignerBean->unique_link is commented out as it's not needed here

    $newId = $stic_SignerBean->save();
    if($newId){
        require_once 'modules/stic_Signature_Log/Utils.php';
        stic_SignatureLogUtils::logSignatureAction('ADD_SIGNER_TO_SIGNATURE',$newId,'SIGNER', $stic_SignatureBean->name);
        stic_SignatureLogUtils::logSignatureAction('ADD_SIGNER_TO_SIGNATURE',$stic_SignatureBean,'SIGNATURE', $stic_SignerBean->name);
        
    }

    // Add relationships between stic_Signers and stic_Signatures records
    // via the stic_signatures_stic_signers_c relationship table
    $stic_SignatureBean->load_relationship('stic_signatures_stic_signers');
    $stic_SignatureBean->stic_signatures_stic_signers->add($stic_SignerBean->id);
    $okCounter++;
}

// Display success or error messages to the user
if ($okCounter !== 0) {
    SugarApplication::appendSuccessMessage("<p class='msg-success'><strong>{$okCounter}</strong> " . translate('LBL_SIGNERS_ADDED_MSG', 'stic_Signatures') . ".</p>");
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": {$okCounter} signers added successfully.");
}

if ($koCounter !== 0) {
    SugarApplication::appendErrorMessage("<p class='msg-error'><strong>{$koCounter}</strong> " . translate('LBL_SIGNERS_NOT_ADDED_MSG', 'stic_Signatures') . ".</p>");
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": {$koCounter} signers could not be added because they already exist or an error occurred.");
}

// Redirect to the DetailView of the signature after processing
SugarApplication::redirect('index.php?module=stic_Signatures&action=DetailView&record=' . $signatureId);