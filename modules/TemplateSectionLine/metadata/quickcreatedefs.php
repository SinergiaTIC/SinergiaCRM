<?php
// $module_name = 'TemplateSectionLine';
// $viewdefs [$module_name] =
// array(
//   'QuickCreate' =>
//   array(
//     'templateMeta' =>
//     array(
//       'maxColumns' => '2',
//       'widths' =>
//       array(
//         0 =>
//         array(
//           'label' => '10',
//           'field' => '30',
//         ),
//         1 =>
//         array(
//           'label' => '10',
//           'field' => '30',
//         ),
//       ),
//       'useTabs' => false,
//       'tabDefs' =>
//       array(
//         'DEFAULT' =>
//         array(
//           'newTab' => false,
//           'panelDefault' => 'expanded',
//         ),
//       ),
//     ),
//     'panels' =>
//     array(
//       'default' =>
//       array(
//         0 =>
//         array(
//           0 => 'name',
//           1 =>
//           array(
//             'name' => 'grp',
//             'label' => 'LBL_GRP',
//           ),
//         ),
//         1 =>
//         array(
//           0 =>
//           array(
//             'name' => 'description',
//             'comment' => 'Full text of the note',
//             'label' => 'LBL_DESCRIPTION',
//           ),
//         ),
//       ),
//     ),
//   ),
// );

$module_name = 'TemplateSectionLine';
$viewdefs [$module_name] =
array(
  'QuickCreate' =>
  array(
    'templateMeta' =>
    array(
      'maxColumns' => '2',
      'widths' =>
      array(
        0 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' =>
      array(
        'DEFAULT' =>
        array(
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' =>
    array(
      'default' => array(
        0 => array(
          0 => 'name',
          1 => 'assigned_user_name',          
        ),
        1 => array(
          0 => array(
            'name' => 'grp',
            'label' => 'LBL_GRP',
          ),
          1 => array(
            'name' => 'ord',
            'label' => 'LBL_ORD',
        ),
        ),
        3 => array(
          0 => 'description',
        ),
        4 => array(
          0 => array (
            'name' => 'thumbnail_name_c',
            'label' => 'LBL_THUMBNAIL_NAME',
          ),
          1 => array (
            'name' => 'thumbnail_image_c',
            'studio' => 'visible',
            'label' => 'LBL_THUMBNAIL_IMAGE',
          ),
        ),      
      ),
    ),
  ),
);
// END STIC-Custom