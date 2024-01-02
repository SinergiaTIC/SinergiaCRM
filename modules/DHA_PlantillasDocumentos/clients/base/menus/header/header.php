<?php

$moduleName = 'DHA_PlantillasDocumentos';
$viewdefs[$moduleName]['base']['menu']['header'] = array(
   // Sistema nuevo
   // array(
      // 'label' =>'LNK_NEW_RECORD',
      // 'acl_action'=>'create',
      // 'acl_module'=>$moduleName,
      // 'icon' => 'icon-plus',
      // 'route'=>'#'.$moduleName.'/create',
   // ),
   // array(
      // 'route'=>'#'.$moduleName,
      // 'label' =>'LNK_LIST',
      // 'acl_action'=>'list',
      // 'acl_module'=>$moduleName,
      // 'icon' => 'icon-reorder',
   // ),

    
   array(
      'route' => '#bwc/index.php?' . http_build_query(
         array(
            'module' => $moduleName,
            'action' => 'EditView',
            'return_module' => $moduleName,
            'return_action' => 'DetailView',
         )
      ),
      'label' => 'LNK_NEW_RECORD',
      'acl_action' => 'create',
      'acl_module' => $moduleName,
      'icon' => 'icon-plus',
   ),
   array(
      'route' => '#bwc/index.php?' . http_build_query(
         array(
            'module' => $moduleName,
            'action' => 'index',
            'return_module' => $moduleName,
            'return_action' => 'index',
         )
      ),
      'label' => 'LNK_LIST',
      'acl_action' => 'list',
      'acl_module' => $moduleName,
      'icon' => 'icon-reorder',
   ),
   array(
      'route' => '#bwc/index.php?' . http_build_query(
         array(
            'module' => $moduleName,
            'action' => 'varlist',
            'return_module' => $moduleName,
            'return_action' => 'DetailView',
         )
      ),
      'label' => 'LBL_LISTA_VARIABLES',
      'acl_action' => 'create',
      'acl_module' => $moduleName,
      'icon' => 'icon-tags',
   ),
    
);
