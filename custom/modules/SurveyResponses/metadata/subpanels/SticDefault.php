<?php
// created: 2021-06-10 10:55:50
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'contact_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_SURVEYRESPONSES_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'CONTACT_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Contacts',
    'target_record_key' => 'contact_id',
  ),
  'account_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_SURVEYRESPONSES_ACCOUNTS_FROM_ACCOUNTS_TITLE',
    'id' => 'ACCOUNT_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Accounts',
    'target_record_key' => 'account_id',
  ),
  'survey_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_SURVEYS_SURVEYRESPONSES_FROM_SURVEYS_TITLE',
    'id' => 'SURVEY_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Surveys',
    'target_record_key' => 'survey_id',
  ),
  'campaign_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_SURVEYRESPONSES_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
    'id' => 'CAMPAIGN_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Campaigns',
    'target_record_key' => 'campaign_id',
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'vname' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'link' => true,
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'SurveyResponses',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'SurveyResponses',
    'width' => '5%',
    'default' => true,
  ),
);