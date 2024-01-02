<?php
$module_name = 'SurveyResponses';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'contact_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'CONTACT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'contact_name',
      ),
      'account_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_ACCOUNTS_FROM_ACCOUNTS_TITLE',
        'id' => 'ACCOUNT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'account_name',
      ),
      'survey_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYS_SURVEYRESPONSES_FROM_SURVEYS_TITLE',
        'id' => 'SURVEY_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'survey_name',
      ),
      'campaign_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
        'id' => 'CAMPAIGN_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'campaign_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'contact_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_CONTACTS_FROM_CONTACTS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'CONTACT_ID',
        'name' => 'contact_name',
      ),
      'account_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_ACCOUNTS_FROM_ACCOUNTS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'ACCOUNT_ID',
        'name' => 'account_name',
      ),
      'survey_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYS_SURVEYRESPONSES_FROM_SURVEYS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'SURVEY_ID',
        'name' => 'survey_name',
      ),
      'campaign_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_SURVEYRESPONSES_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'CAMPAIGN_ID',
        'name' => 'campaign_name',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'current_user_only',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
