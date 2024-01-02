<?php

$dictionary['Project']['fields']['stic_location_c'] =
array(
    'inline_edit' => 1,
    'labelValue' => 'Locations',
    'required' => 0,
    'source' => 'custom_fields',
    'name' => 'stic_location_c',
    'vname' => 'LBL_STIC_LOCATION',
    'type' => 'multienum',
    'massupdate' => 1,
    'default' => '^^',
    'no_default' => 1,
    'comments' => '',
    'help' => '',
    'importable' => 1,
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 1,
    'reportable' => 1,
    'unified_search' => 1,
    'merge_filter' => 'enabled',
    'size' => '20',
    'options' => 'stic_project_locations_list',
    'studio' => 'visible',
    'isMultiSelect' => true,
    'id' => 'Projectstic_location_c',
    'custom_module' => 'Project',
);

$dictionary["Project"]["fields"]["project_opportunities_1"] = array(
    'name' => 'project_opportunities_1',
    'type' => 'link',
    'relationship' => 'project_opportunities_1',
    'source' => 'non-db',
    'module' => 'Opportunities',
    'bean_name' => 'Opportunity',
    'side' => 'right',
    'vname' => 'LBL_PROJECT_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
);

$dictionary["Project"]["fields"]["stic_accounts_relationships_project"] = array(
    'name' => 'stic_accounts_relationships_project',
    'type' => 'link',
    'relationship' => 'stic_accounts_relationships_project',
    'source' => 'non-db',
    'module' => 'stic_Accounts_Relationships',
    'bean_name' => 'stic_Accounts_Relationships',
    'side' => 'right',
    'vname' => 'LBL_STIC_ACCOUNTS_RELATIONSHIPS_PROJECT_FROM_STIC_ACCOUNTS_RELATIONSHIPS_TITLE',
);
$dictionary["Project"]["fields"]["stic_contacts_relationships_project"] = array(
    'name' => 'stic_contacts_relationships_project',
    'type' => 'link',
    'relationship' => 'stic_contacts_relationships_project',
    'source' => 'non-db',
    'module' => 'stic_Contacts_Relationships',
    'bean_name' => 'stic_Contacts_Relationships',
    'side' => 'right',
    'vname' => 'LBL_STIC_CONTACTS_RELATIONSHIPS_PROJECT_FROM_STIC_CONTACTS_RELATIONSHIPS_TITLE',
);

$dictionary["Project"]["fields"]["stic_events_project"] = array(
    'name' => 'stic_events_project',
    'type' => 'link',
    'relationship' => 'stic_events_project',
    'source' => 'non-db',
    'module' => 'stic_Events',
    'bean_name' => 'stic_Events',
    'side' => 'right',
    'vname' => 'LBL_STIC_EVENTS_PROJECT_FROM_STIC_EVENTS_TITLE',
);

$dictionary["Project"]["fields"]["stic_payment_commitments_project"] = array(
    'name' => 'stic_payment_commitments_project',
    'type' => 'link',
    'relationship' => 'stic_payment_commitments_project',
    'source' => 'non-db',
    'module' => 'stic_Payment_Commitments',
    'bean_name' => 'stic_Payment_Commitments',
    'side' => 'right',
    'vname' => 'LBL_STIC_PAYMENT_COMMITMENTS_PROJECT_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
);

$dictionary["Project"]["fields"]["stic_followups_project"] = array(
    'name' => 'stic_followups_project',
    'type' => 'link',
    'relationship' => 'stic_followups_project',
    'source' => 'non-db',
    'module' => 'stic_FollowUps',
    'bean_name' => 'stic_FollowUps',
    'side' => 'right',
    'vname' => 'LBL_STIC_FOLLOWUPS_PROJECT_FROM_STIC_FOLLOWUPS_TITLE',
);

$dictionary["Project"]["fields"]["stic_goals_project"] = array(
    'name' => 'stic_goals_project',
    'type' => 'link',
    'relationship' => 'stic_goals_project',
    'source' => 'non-db',
    'module' => 'stic_Goals',
    'bean_name' => 'stic_Goals',
    'side' => 'right',
    'vname' => 'LBL_STIC_GOALS_PROJECT_FROM_STIC_GOALS_TITLE',
);

// Base fields from the module
$dictionary['Project']['fields']['description']['rows'] = '2'; // Make textarea fields shorter

$dictionary['Project']['fields']['estimated_end_date']['required'] = 0;
$dictionary['Project']['fields']['estimated_end_date']['importable'] = 1;

$dictionary['Project']['fields']['am_projecttemplates_project_1_name']['massupdate'] = true; // Don't work with 1 (must be true)

$dictionary['Project']['fields']['created_by_name']['massupdate'] = 0;

$dictionary['Project']['fields']['status']['options'] = 'stic_projects_status_list';

// Enabling massupdate for core fields
// STIC#981
$dictionary['Project']['fields']['estimated_start_date']['massupdate'] = 1;
$dictionary['Project']['fields']['estimated_end_date']['massupdate'] = 1;
$dictionary['Project']['fields']['status']['massupdate'] = 1;
$dictionary['Project']['fields']['priority']['massupdate'] = 1;
$dictionary['Project']['fields']['override_business_hours']['massupdate'] = 1;

$dictionary['Project']['fields']['assigned_user_id']['massupdate'] = 1;