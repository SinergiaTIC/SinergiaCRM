<?php

// $campaignId = $_REQUEST['parent_id'];
$campaignId = $_REQUEST['campaign_id'] ?? '';

if (empty($campaignId) && ($_REQUEST['module'] ?? '') === 'stic_Message_Marketing') {
    $db = DBManagerFactory::getInstance();
    $messageMarketingId = $_REQUEST['record'];
    $sqlGetCampaign = "SELECT campaigns_stic_message_marketingcampaign_ida FROM campaigns_stic_message_marketing_c WHERE campaigns_stic_message_marketingmessage_idb = '{$messageMarketingId}'";
    $campaignId = $db->getOne($sqlGetCampaign);
}

if (!empty($campaignId)) {
    $db = DBManagerFactory::getInstance();

    $sql = "
        select pl.id as id, pl.name as 'value'
        from prospect_list_campaigns plc 
        join prospect_lists pl on pl.id = plc.prospect_list_id 
        where plc.deleted = 0
        and pl.deleted = 0
        and pl.list_type in ('default', 'test')
        and plc.campaign_id = '{$campaignId}'";

    $result = $db->query($sql);

    $app_list_strings['stic_prospectlists_campaign'] = array();
    while ($row = $db->fetchByAssoc($result)) {
        $app_list_strings['stic_prospectlists_campaign'][$row['id']] = $row['value'];
    }
}




