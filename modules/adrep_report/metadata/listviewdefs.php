<?php
$module_name = 'adrep_report';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'PRIMARY_MODULE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PRIMARY_MODULE',
    'width' => '10%',
    'default' => true,
  ),
  'CSS_FILE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CSS_FILE',
    'width' => '10%',
  ),
  'PERMISSION' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PERMISSION',
    'width' => '10%',
  ),
);
?>
