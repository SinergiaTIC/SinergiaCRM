<?php
$module_name = 'adrep_schedule';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'REPORT_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_REPORT_NAME',
    'id' => 'ADREP_REPORT_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'FORMAT' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_FORMAT',
    'width' => '10%',
  ),
  'DOW' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_DOW',
    'width' => '10%',
    'default' => true,
  ),
  'TOD' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_TOD',
    'width' => '10%',
  ),
  'ACTIVE_FLAG' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ACTIVE_FLAG',
    'width' => '10%',
  ),
);
?>
