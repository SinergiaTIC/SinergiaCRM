<?php
$popupMeta = array (
    'moduleMain' => 'stic_Goals',
    'varName' => 'stic_Goals',
    'orderBy' => 'stic_goals.name',
    'whereClauses' => array (
  'name' => 'stic_goals.name',
  'stic_goals_contacts_name' => 'stic_goals.stic_goals_contacts_name',
  'stic_goals_project_name' => 'stic_goals.stic_goals_project_name',
  'stic_goals_stic_assessments_name' => 'stic_goals.stic_goals_stic_assessments_name',
  'origin' => 'stic_goals.origin',
  'start_date' => 'stic_goals.start_date',
  'area' => 'stic_goals.area',
  'assigned_user_id' => 'stic_goals.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'stic_goals_contacts_name',
  5 => 'stic_goals_project_name',
  6 => 'stic_goals_stic_assessments_name',
  7 => 'origin',
  8 => 'start_date',
  9 => 'area',
  10 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'stic_goals_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_GOALS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_GOALS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'name' => 'stic_goals_contacts_name',
  ),
  'stic_goals_project_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_GOALS_PROJECT_FROM_PROJECT_TITLE',
    'id' => 'STIC_GOALS_PROJECTPROJECT_IDA',
    'width' => '10%',
    'name' => 'stic_goals_project_name',
  ),
  'stic_goals_stic_assessments_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_GOALS_STIC_ASSESSMENTS_FROM_STIC_ASSESSMENTS_TITLE',
    'id' => 'STIC_GOALS_STIC_ASSESSMENTSSTIC_ASSESSMENTS_IDA',
    'width' => '10%',
    'name' => 'stic_goals_stic_assessments_name',
  ),
  'origin' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ORIGIN',
    'width' => '10%',
    'name' => 'origin',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'name' => 'start_date',
  ),
  'area' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_AREA',
    'width' => '10%',
    'name' => 'area',
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
  ),
),
);
