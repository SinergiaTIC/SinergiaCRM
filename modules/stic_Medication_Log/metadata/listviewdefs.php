<?php
$module_name = 'stic_Medication_Log';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'INTAKE_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_INTAKE_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ADMINISTERED' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_ADMINISTERED',
    'width' => '10%',
  ),
  'SCHEDULE' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'studio' => 'visible',
    'default' => true,
  ),
  'STIC_MEDICATION_LOG_CONTACTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_MEDICATION_LOG_CONTACTSCONTACTS_IDA',
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
  'STIC_MEDICATION_LOG_STIC_PRESCRIPTION_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_PRESCRIPTION_TITLE',
    'id' => 'STIC_MEDICATION_LOG_STIC_PRESCRIPTIONSTIC_PRESCRIPTION_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'TIME' => 
  array (
    'type' => 'time',
    'label' => 'LBL_TIME',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'MEDICATION' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MEDICATION',
    'width' => '10%',
    'default' => false,
  ),
  'DOSAGE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DOSAGE',
    'width' => '10%',
    'default' => false,
  ),
  'STOCK_DEPLETION' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_STOCK_DEPLETION',
    'width' => '10%',
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
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
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
);
;
?>
