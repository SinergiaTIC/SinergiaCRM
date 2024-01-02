<?php
$popupMeta = array (
    'moduleMain' => 'stic_Assessments',
    'varName' => 'stic_Assessments',
    'orderBy' => 'stic_assessments.name',
    'whereClauses' => array (
  'name' => 'stic_assessments.name',
  'stic_assessments_contacts_name' => 'stic_assessments.stic_assessments_contacts_name',
  'status' => 'stic_assessments.status',
  'assessment_date' => 'stic_assessments.assessment_date',
  'moment' => 'stic_assessments.moment',
  'scope' => 'stic_assessments.scope',
  'areas' => 'stic_assessments.areas',
  'assigned_user_id' => 'stic_assessments.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'stic_assessments_contacts_name',
  5 => 'assessment_date',
  6 => 'moment',
  7 => 'scope',
  8 => 'areas',
  9 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'stic_assessments_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ASSESSMENTS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_ASSESSMENTS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'name' => 'stic_assessments_contacts_name',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'assessment_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_ASSESSMENT_DATE',
    'width' => '10%',
    'name' => 'assessment_date',
  ),
  'moment' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MOMENT',
    'width' => '10%',
    'name' => 'moment',
  ),
  'scope' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SCOPE',
    'width' => '10%',
    'name' => 'scope',
  ),
  'areas' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_AREAS',
    'width' => '10%',
    'name' => 'ambits',
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
