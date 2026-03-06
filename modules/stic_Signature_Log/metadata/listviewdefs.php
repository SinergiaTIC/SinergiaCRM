<?php
$module_name = 'stic_Signature_Log';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ACTION' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_ACTION',
    'width' => '10%',
  ),
  'STIC_SIGNERS_STIC_SIGNATURE_LOG_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_SIGNERS_STIC_SIGNATURE_LOG_FROM_STIC_SIGNERS_TITLE',
    'id' => 'STIC_SIGNERS_STIC_SIGNATURE_LOGTIC_SIGNERS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'STIC_SIGNATURES_STIC_SIGNATURE_LOG_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_SIGNATURES_STIC_SIGNATURE_LOG_FROM_STIC_SIGNATURES_TITLE',
    'id' => 'STIC_SIGNATURES_STIC_SIGNATURE_LOGTIC_SIGNATURES_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'USER_AGENT' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_USER_AGENT',
    'width' => '10%',
    'default' => true,
  ),
  'IP_ADDRESS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_IP_ADDRESS',
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
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
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
);
;
?>
