<?php
$module_name = 'DHA_PlantillasDocumentos';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'document_name' => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      'modulo' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MODULO',
        'width' => '10%',
        'name' => 'modulo',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),      
    ),
    'advanced_search' => 
    array (
      'document_name' => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      'modulo' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MODULO',
        'width' => '10%',
        'name' => 'modulo',
      ),
      'idioma' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_IDIOMA_PLANTILLA',
        'width' => '10%',
        'name' => 'idioma',
      ),
      'category_id' => 
      array (
        'name' => 'category_id',
        'default' => true,
        'width' => '10%',
      ),
      'status_id' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_DOC_STATUS',
        'width' => '10%',
        'name' => 'status_id',
      ),
      'assigned_user_name' => 
      array (
        'link' => 'assigned_user_link',
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'assigned_user_name',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'aclroles' => 
      array (
        'type' => 'multienum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ROLES_WITH_ACCESS',
        'width' => '10%',
        'name' => 'aclroles',
      ),       
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);

global $sugar_flavor;
if(!empty($sugar_flavor) && $sugar_flavor != 'CE'){
   $searchdefs [$module_name]['layout']['basic_search']['favorites_only'] = array (
      'name' => 'favorites_only',
      'label' => 'LBL_FAVORITES_FILTER',
      'type' => 'bool',
   );
   $searchdefs [$module_name]['layout']['advanced_search']['favorites_only'] = $searchdefs [$module_name]['layout']['basic_search']['favorites_only'];
}

?>
