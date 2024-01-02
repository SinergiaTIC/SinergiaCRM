<?php
$dictionary["Note"]["audited"] = true;
$dictionary["Note"]["fields"]["stic_job_applications_activities_notes"] = array (
    'name' => 'stic_job_applications_activities_notes',
    'type' => 'link',
    'relationship' => 'stic_job_applications_activities_notes',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_APPLICATIONS_ACTIVITIES_NOTES_FROM_STIC_JOB_APPLICATIONS_TITLE',
);

$dictionary["Note"]["fields"]["stic_job_offers_activities_notes"] = array (
    'name' => 'stic_job_offers_activities_notes',
    'type' => 'link',
    'relationship' => 'stic_job_offers_activities_notes',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_OFFERS_ACTIVITIES_NOTES_FROM_STIC_JOB_OFFERS_TITLE',
);
  

$dictionary['Note']['fields']['filename']['inline_edit'] = 0;
$dictionary['Note']['fields']['parent_name']['inline_edit'] = 0;

$dictionary['Note']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['Note']['fields']['description']['massupdate'] = 0;

$dictionary['Note']['fields']['created_by_name']['massupdate'] = 0;
$dictionary['Note']['fields']['contact_email']['massupdate'] = 0;

$dictionary['Note']['fields']['parent_type']['inline_edit'] = false;
$dictionary['Note']['fields']['parent_name']['inline_edit'] = false;

// Enabling massupdate for core fields
// STIC#981
$dictionary['Note']['fields']['parent_name']['massupdate']='1';
$dictionary['Note']['fields']['contact_name']['massupdate']='1';
$dictionary['Note']['fields']['portal_flag']['massupdate']='1';
$dictionary['Note']['fields']['embed_flag']['massupdate']='1';
$dictionary['Note']['fields']['parent_name']['massupdate']='1';
$dictionary['Note']['fields']['contact_id']['massupdate']='1';