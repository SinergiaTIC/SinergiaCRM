<?php

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

foreach ($destSigners as $destSignerId => $destSigner) {

    $destSignerBean = BeanFactory::getBean($destSigner['module'], $destSignerId);
    if (!$destSignerBean) {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Cant not obtain signer data");
        continue;
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
    

    


    // Check if the bean has the signature field
    if (isset($bean->signature)) {
        $bean->signature = $signatureId;
        $bean->save();
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature updated for Record ID: " . $recordId);
    } else {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature field not found in Record ID: " . $recordId);
    }
}

SugarApplication::redirect('index.php?module=stic_Signatures&action=DetailView&record=' . $signatureId);
