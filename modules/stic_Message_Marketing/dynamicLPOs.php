<?php

// $campaignId = $_REQUEST['parent_id'];
$campaignId = $_REQUEST['campaign_id'];

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

    $app_list_strings['stic_prospectlists_campaign'][''] = '';
    while ($row = $db->fetchByAssoc($result)) {
        $app_list_strings['stic_prospectlists_campaign'][$row['id']] = $row['value'];
    }
}




