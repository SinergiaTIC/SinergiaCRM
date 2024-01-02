<?php
$dictionary["Task"]["audited"] = true;
$dictionary["Task"]["fields"]["stic_job_applications_activities_tasks"] = array (
    'name' => 'stic_job_applications_activities_tasks',
    'type' => 'link',
    'relationship' => 'stic_job_applications_activities_tasks',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_APPLICATIONS_ACTIVITIES_TASKS_FROM_STIC_JOB_APPLICATIONS_TITLE',
);

$dictionary["Task"]["fields"]["stic_job_offers_activities_tasks"] = array (
    'name' => 'stic_job_offers_activities_tasks',
    'type' => 'link',
    'relationship' => 'stic_job_offers_activities_tasks',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_OFFERS_ACTIVITIES_TASKS_FROM_STIC_JOB_OFFERS_TITLE',
);  

$dictionary['Task']['fields']['parent_name']['inline_edit'] = 0; // Make textarea fields shorter

$dictionary['Task']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['Task']['fields']['description']['massupdate'] = 0;

$dictionary['Task']['fields']['contact_email']['massupdate'] = 0;

// Enabling massupdate for core fields
// STIC#981
$dictionary['Task']['fields']['parent_name']['massupdate']='1';
$dictionary['Task']['fields']['status']['massupdate']='1';
$dictionary['Task']['fields']['date_start']['massupdate']='1';
$dictionary['Task']['fields']['date_due']['massupdate']='1';
$dictionary['Task']['fields']['contact_name']['massupdate']='1';
$dictionary['Task']['fields']['priority']['massupdate']='1';