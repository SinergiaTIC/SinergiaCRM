<?php
$module_name = 'stic_Prescription';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'START_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'END_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ACTIVE',
    'width' => '10%',
  ),
  'FREQUENCY' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_FREQUENCY',
    'width' => '10%',
    'default' => true,
  ),
  'SCHEDULE' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_SCHEDULE',
    'width' => '10%',
    'default' => true,
  ),
  'STIC_PRESCRIPTION_STIC_MEDICATION_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_PRESCRIPTION_STIC_MEDICATION_FROM_STIC_MEDICATION_TITLE',
    'id' => 'STIC_PRESCRIPTION_STIC_MEDICATIONSTIC_MEDICATION_IDA',
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
  'PRESCRIBER' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PRESCRIBER',
    'id' => 'CONTACT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => false,
  ),
  'SKIP_INTAKE' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_SKIP_INTAKE',
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
  'PRESCRIPTION' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_PRESCRIPTION',
    'width' => '10%',
  ),
  'STOCK_DEPLETION_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_STOCK_DEPLETION_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
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
