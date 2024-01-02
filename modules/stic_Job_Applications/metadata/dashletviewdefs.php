<?php
$dashletData['stic_Job_ApplicationsDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'status' => 
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
  'assigned_user_name' => 
  array (
    'default' => '',
  ),
  'contract_start_date' => 
  array (
    'default' => '',
  ),
  'contract_end_date' => 
  array (
    'default' => '',
  ),
);
$dashletData['stic_Job_ApplicationsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
    'name' => 'status',
  ),
  'stic_job_applications_stic_job_offers_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_JOB_APPLICATIONS_STIC_JOB_OFFERS_FROM_STIC_JOB_OFFERS_TITLE',
    'id' => 'STIC_JOB_APPLICATIONS_STIC_JOB_OFFERSSTIC_JOB_OFFERS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_job_applications_stic_job_offers_name',
  ),
  'stic_job_applications_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_JOB_APPLICATIONS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_JOB_APPLICATIONS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'stic_job_applications_contacts_name',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '5%',
    'default' => true,
    'name' => 'start_date',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => true,
  ),
  'status_details' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_STATUS_DETAILS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'motivations' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_MOTIVATIONS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'postinsertion_observations' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_POSTINSERTION_OBSERVATIONS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'preinsertion_observations' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_PREINSERTION_OBSERVATIONS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'contract_start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CONTRACT_START_DATE',
    'width' => '10%',
    'default' => false,
    'name' => 'contract_start_date',
  ),
  'contract_end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CONTRACT_END_DATE',
    'width' => '10%',
    'default' => false,
    'name' => 'contract_end_date',
  ),
  'contract_end_reason' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CONTRACT_END_REASON',
    'width' => '10%',
    'default' => false,
    'name' => 'contract_end_reason',
  ),
  'rejection_reason' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_REJECTION_REASON',
    'width' => '10%',
    'default' => false,
    'name' => 'rejection_reason',
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '5%',
    'default' => false,
    'name' => 'end_date',
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
);
