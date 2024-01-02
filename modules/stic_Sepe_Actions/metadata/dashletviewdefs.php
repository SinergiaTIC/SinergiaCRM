<?php
$dashletData['stic_Sepe_ActionsDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'start_date' => 
  array (
    'default' => '',
  ),
  'end_date' => 
  array (
    'default' => '',
  ),
  'type' => 
  array (
    'default' => '',
  ),
  'agreement' => 
  array (
    'default' => '',
  ),
  'assigned_user_name' => 
  array (
    'default' => '',
  ),
);
$dashletData['stic_Sepe_ActionsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'stic_sepe_actions_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_SEPE_ACTIONS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_SEPE_ACTIONS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_sepe_actions_contacts_name',
  ),
  'type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ACTION_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'type',
  ),
  'agreement' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_AGREEMENT',
    'width' => '10%',
    'default' => true,
    'name' => 'agreement',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
    'name' => 'start_date',
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
    'name' => 'end_date',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
    'name' => 'description',
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
    'name' => 'created_by_name',
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
    'name' => 'modified_by_name',
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
    'name' => 'date_entered',
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'searchfields' => 
  array (
    'name' => 
    array (
      'default' => '',
    ),
    'start_date' => 
    array (
      'default' => '',
    ),
    'end_date' => 
    array (
      'default' => '',
    ),
    'type' => 
    array (
      'default' => '',
    ),
    'agreement' => 
    array (
      'default' => '',
    ),
    'stic_sepe_actions_contacts_name' => 
    array (
      'default' => '',
    ),
    'assigned_user_name' => 
    array (
      'default' => '',
    ),
    'width' => '10%',
    'default' => false,
  ),
  'columns' => 
  array (
    'name' => 
    array (
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
    ),
    'stic_sepe_actions_contacts_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_STIC_SEPE_ACTIONS_CONTACTS_FROM_CONTACTS_TITLE',
      'id' => 'STIC_SEPE_ACTIONS_CONTACTSCONTACTS_IDA',
      'width' => '10%',
      'default' => true,
      'name' => 'stic_sepe_actions_contacts_name',
    ),
    'type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_ACTION_TYPE',
      'width' => '10%',
      'default' => true,
      'name' => 'type',
    ),
    'agreement' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_AGREEMENT',
      'width' => '10%',
      'default' => true,
      'name' => 'agreement',
    ),
    'start_date' => 
    array (
      'type' => 'date',
      'label' => 'LBL_START_DATE',
      'width' => '10%',
      'default' => true,
      'name' => 'start_date',
    ),
    'end_date' => 
    array (
      'type' => 'date',
      'label' => 'LBL_END_DATE',
      'width' => '10%',
      'default' => true,
      'name' => 'end_date',
    ),
    'assigned_user_name' => 
    array (
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
    ),
    'created_by_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CREATED',
      'id' => 'CREATED_BY',
      'width' => '10%',
      'default' => false,
      'name' => 'created_by_name',
    ),
    'modified_by_name' => 
    array (
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MODIFIED_NAME',
      'id' => 'MODIFIED_USER_ID',
      'width' => '10%',
      'default' => false,
      'name' => 'modified_by_name',
    ),
    'date_modified' => 
    array (
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
    ),
    'date_entered' => 
    array (
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
    ),
    'created_by' => 
    array (
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
    ),
    'width' => '10%',
    'default' => false,
  ),
);
