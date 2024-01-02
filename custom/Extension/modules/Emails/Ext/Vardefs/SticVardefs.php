<?php
$dictionary["Email"]["audited"] = true;
$dictionary["Email"]["fields"]["stic_job_applications_activities_emails"] = array (
    'name' => 'stic_job_applications_activities_emails',
    'type' => 'link',
    'relationship' => 'stic_job_applications_activities_emails',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_APPLICATIONS_ACTIVITIES_EMAILS_FROM_STIC_JOB_APPLICATIONS_TITLE',
);
  
$dictionary["Email"]["fields"]["stic_job_offers_activities_emails"] = array (
    'name' => 'stic_job_offers_activities_emails',
    'type' => 'link',
    'relationship' => 'stic_job_offers_activities_emails',
    'source' => 'non-db',
    'vname' => 'LBL_STIC_JOB_OFFERS_ACTIVITIES_EMAILS_FROM_STIC_JOB_OFFERS_TITLE',
);

$dictionary["Email"]["fields"]["name"]["link"] = true;
$dictionary["Email"]["fields"]["parent_name"]["inline_edit"] = 0;
