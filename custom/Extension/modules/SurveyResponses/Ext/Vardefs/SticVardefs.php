<?php

// STIC AAM 20210609 - There is a bug in SuiteCRM core that doesn't display the contact/account in the ListView
// It seems that these relationships definition are missing in the vardef. Defining them solves the issue
// STIC#290
$dictionary["SurveyResponses"]["relationships"]["surveyresponses_contacts"] = array(
  'rhs_module'        => 'SurveyResponses',
  'rhs_table'         => 'surveyresponses',
  'rhs_key'           => 'contact_id',
  'lhs_module'        => 'Contacts',
  'lhs_table'         => 'contacts',
  'lhs_key'           => 'id',
  'relationship_type' => 'one-to-many',
);

$dictionary["SurveyResponses"]["relationships"]["surveyresponses_accounts"] = array(
  'rhs_module'        => 'SurveyResponses',
  'rhs_table'         => 'surveyresponses',
  'rhs_key'           => 'account_id',
  'lhs_module'        => 'Accounts',
  'lhs_table'         => 'accounts',
  'lhs_key'           => 'id',
  'relationship_type' => 'one-to-many',
);

$dictionary['SurveyResponses']['fields']['survey_name']['required'] = 1;

$dictionary['SurveyResponses']['fields']['description']['rows'] = '2'; // Make textarea fields shorter
$dictionary['SurveyResponses']['fields']['description']['massupdate'] = 0;
