<?php
global $mod_strings;

$bean = BeanFactory::getBean($_REQUEST['module']);

if (!$bean) {
    $GLOBAL->log->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Invalid Module: " . $_REQUEST['module']);
    sugar_die("Invalid Module");
}

$signatureId = $_REQUEST['signature-id'] ?? '';
if (empty($signatureId)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature ID is empty.");
    sugar_die("Signature ID is required");
}

$recordIds = array();

if (isset($_REQUEST['current_post']) && $_REQUEST['current_post'] != '') {
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

// Obtain destination signers
$destSigners = stic_SignaturesUtils::getSignatureSigners($signatureId, $recordIds);

// consultar directamente en la base de datos si ya existen registros de stic_Signers con el mismo signatureId
$SQL = "SELECT ss.parent_id as id
            FROM stic_signatures s
            join stic_signatures_stic_signers_c ssssc on s.id =ssssc.stic_signatures_stic_signersstic_signatures_ida AND ssssc.deleted=0
            join stic_signers ss on ss.id=ssssc.stic_signatures_stic_signersstic_signers_idb  AND ss.deleted=0
            WHERE s.deleted=0
            AND s.id='{$signatureId}'";
$result = DBManagerFactory::getInstance()->query($SQL, true);
$existingSigners = array();
while ($row = DBManagerFactory::getInstance()->fetchByAssoc($result, false)) {
    $existingSigners[] = $row['id'];
}

$okCounter = 0;
$koCounter = 0;

foreach ($destSigners as $destSignerId => $destSigner) {

    $destSignerBean = BeanFactory::getBean($destSigner['module'], $destSignerId);
    if (!$destSignerBean) {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Cant not obtain signer data");
        $koCounter++;
        continue;
    }
    if (in_array($destSignerId, $existingSigners)) {
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Skipping existing signer with ID: " . $destSignerId);
        $koCounter++;
        continue; // Skip if the signer already exists
    }

    $stic_SignerBean = BeanFactory::newBean('stic_Signers');
    $stic_SignerBean->name = "{$destSignerBean->full_name} - {$stic_SignatureBean->name}";
    $stic_SignerBean->parent_type = $destSigner['module'];
    $stic_SignerBean->parent_id = $destSignerId;
    $stic_SignerBean->parent_name = $destSignerBean->full_name;
    $stic_SignerBean->record_id = $destSigner['sourceId'];
    $stic_SignerBean->email_address = $destSigner['email'];
    $stic_SignerBean->phone = $destSigner['phone'];
    $stic_SignerBean->status = 'pending';
    // $stic_SignerBean->unique_link = "{$destSignerId}:{$signatureId}"; no es necesario

    $stic_SignerBean->save();

    // add relationships between stic_Signers & stic_Signatures records throw stic_signatures_stic_signers_c
    $stic_SignatureBean->load_relationship('stic_signatures_stic_signers');
    $stic_SignatureBean->stic_signatures_stic_signers->add($stic_SignerBean->id);
    $okCounter++;
}

if ($okCounter != 0) {
    SugarApplication::appendSuccessMessage("<p class='msg-success'><strong>{$okCounter}</strong> " . translate('LBL_SIGNERS_ADDED_MSG', 'stic_Signatures') . ".</p>");
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": {$okCounter} signers added successfully.");
}

if ($koCounter != 0) {
    SugarApplication::appendErrorMessage("<p class='msg-error'><strong>{$koCounter}</strong> " . translate('LBL_SIGNERS_NOT_ADDED_MSG', 'stic_Signatures') . ".</p>");
    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": {$koCounter} signers could not be added because they already exist or an error occurred.");
}

SugarApplication::redirect('index.php?module=stic_Signatures&action=DetailView&record=' . $signatureId);
