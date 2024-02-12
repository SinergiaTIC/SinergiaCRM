<?php
$module_name = 'adrep_column';
$listViewDefs [$module_name] = 
array (
  'REPORT_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_REPORT_NAME',
    'id' => 'REPORT_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'COLUMN_LABEL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PARAMETER_LABEL',
    'width' => '10%',
    'default' => true,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'DECIMALS' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_DECIMALS',
    'width' => '10%',
  ),
  'DROPDOWN_NAME' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DROPDOWN_NAME',
    'width' => '10%',
  ),
  'LINKED_MODULE_NAME' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LINKED_MODULE_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVE_FLAG' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ACTIVE_FLAG',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
?>
