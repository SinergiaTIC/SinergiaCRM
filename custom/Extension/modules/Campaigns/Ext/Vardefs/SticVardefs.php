<?php

$dictionary["Campaign"]["fields"]["stic_payment_commitments_campaigns"] = array(
    'name' => 'stic_payment_commitments_campaigns',
    'type' => 'link',
    'relationship' => 'stic_payment_commitments_campaigns',
    'source' => 'non-db',
    'module' => 'stic_Payment_Commitments',
    'bean_name' => 'stic_Payment_Commitments',
    'side' => 'right',
    'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
);

$dictionary['Campaign']['fields']['end_date']['required'] = false;

$dictionary['Campaign']['fields']['budget']['massupdate'] = 0;
$dictionary['Campaign']['fields']['actual_cost']['massupdate'] = 0;
$dictionary['Campaign']['fields']['actual_revenue']['massupdate'] = 0;
$dictionary['Campaign']['fields']['expected_revenue']['massupdate'] = 0;
$dictionary['Campaign']['fields']['expected_cost']['massupdate'] = 0;
$dictionary['Campaign']['fields']['refer_url']['massupdate'] = 0;
$dictionary['Campaign']['fields']['tracker_text']['massupdate'] = 0;
$dictionary['Campaign']['fields']['objective']['massupdate'] = 0;
$dictionary['Campaign']['fields']['content']['massupdate'] = 0;

// Enabling massupdate for core fields
// STIC#981
$dictionary['Campaign']['fields']['start_date']['massupdate'] = 1;
$dictionary['Campaign']['fields']['end_date']['massupdate'] = 1;
$dictionary['Campaign']['fields']['status']['massupdate'] = 1;
$dictionary['Campaign']['fields']['campaign_type']['massupdate'] = 1;
$dictionary['Campaign']['fields']['frequency']['massupdate'] = 1;

