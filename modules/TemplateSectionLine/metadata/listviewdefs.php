<?php
// $module_name = 'TemplateSectionLine';
// $listViewDefs [$module_name] =
// array(
//   'NAME' =>
//   array(
//     'width' => '32%',
//     'label' => 'LBL_NAME',
//     'default' => true,
//     'link' => true,
//   ),
// );

$module_name = 'TemplateSectionLine';
$listViewDefs [$module_name] =
array(
  'NAME' => array(
    'width' => '30%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'THUMBNAIL_NAME_C' => array(
    'width' => '30%',
    'label' => 'LBL_THUMBNAIL_NAME',
    'default' => true,
    'link' => true,
  ),
  'GRP' => array(
    'width' => '5%',
    'label' => 'LBL_GRP',
    'default' => true,
    'link' => true,
  ),
  'ORD' => array(
    'width' => '5%',
    'label' => 'LBL_ORD',
    'default' => true,
    'link' => true,
  ),
  'ASSIGNED_USER_NAME' => array(
    'width' => '30%',
    'label' => 'LBL_ASSIGNED_TO',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
// END STIC-Custom