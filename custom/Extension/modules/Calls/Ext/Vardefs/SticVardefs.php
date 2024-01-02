<?php
$dictionary["Call"]["audited"] = true;
$dictionary["Call"]["fields"]["stic_job_applications_activities_calls"] = array (
    'name' => 'stic_job_applications_activities_calls',
    'type' => 'link',
    'relationship' => 'stic_job_applications_activities_calls',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_APPLICATIONS_ACTIVITIES_CALLS_FROM_STIC_JOB_APPLICATIONS_TITLE',
);

$dictionary["Call"]["fields"]["stic_job_offers_activities_calls"] = array (
    'name' => 'stic_job_offers_activities_calls',
    'type' => 'link',
    'relationship' => 'stic_job_offers_activities_calls',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_OFFERS_ACTIVITIES_CALLS_FROM_STIC_JOB_OFFERS_TITLE',
);

$dictionary['Call']['fields']['email_reminder_time']['options'] = 'stic_email_reminder_time_list';

$dictionary['Call']['fields']['duration_hours']['inline_edit'] = 0;
$dictionary['Call']['fields']['duration_minutes']['inline_edit'] = 0;
$dictionary['Call']['fields']['parent_name']['inline_edit'] = 0;
$dictionary['Call']['fields']['reminders']['inline_edit'] = 0;
$dictionary['Call']['fields']['reschedule_history']['inline_edit'] = 0;

$dictionary['Call']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['Call']['fields']['description']['massupdate'] = 0;

$dictionary['Call']['fields']['outlook_id']['massupdate'] = 0;
$dictionary['Call']['fields']['accept_status']['massupdate'] = 0;
$dictionary['Call']['fields']['set_accept_links']['massupdate'] = 0;

// Enabling massupdate for core fields
// STIC#981
$dictionary['Call']['fields']['parent_name']['massupdate']='1';
$dictionary['Call']['fields']['status']['massupdate']='1';
$dictionary['Call']['fields']['direction']['massupdate']='1';
$dictionary['Call']['fields']['date_start']['massupdate']='1';
