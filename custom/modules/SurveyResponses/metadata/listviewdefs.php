<?php
$module_name = 'SurveyResponses';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'CONTACT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SURVEYRESPONSES_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'CONTACT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'ACCOUNT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SURVEYRESPONSES_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'id' => 'ACCOUNT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'SURVEY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SURVEYS_SURVEYRESPONSES_FROM_SURVEYS_TITLE',
    'id' => 'SURVEY_ID',
    'width' => '10%',
    'default' => true,
  ),
  'CAMPAIGN_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SURVEYRESPONSES_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
    'id' => 'CAMPAIGN_ID',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>
