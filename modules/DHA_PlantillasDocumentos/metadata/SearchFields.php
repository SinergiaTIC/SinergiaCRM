<?php
$module_name = 'DHA_PlantillasDocumentos';
$searchFields[$module_name] = array (
  'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true, 'vname' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
  'document_name' => 
  array (
    'query_type' => 'default',
  ),
  'category_id' => 
  array (
    'query_type' => 'default',
    'options' => 'document_category_dom',
    'template_var' => 'CATEGORY_OPTIONS',
  ),
  'subcategory_id' => 
  array (
    'query_type' => 'default',
    'options' => 'document_subcategory_dom',
    'template_var' => 'SUBCATEGORY_OPTIONS',
  ),
  'active_date' => 
  array (
    'query_type' => 'default',
  ),
  'exp_date' => 
  array (
    'query_type' => 'default',
  ),
  'range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
);


global $sugar_flavor, $sugar_version;
if(!empty($sugar_flavor) && $sugar_flavor != 'CE'){
   $searchFields [$module_name]['favorites_only'] = array (
      'query_type'=>'format',
      'operator' => 'subquery',
      'subquery' => 'SELECT sugarfavorites.record_id 
                     FROM sugarfavorites 
                     WHERE sugarfavorites.deleted=0 
                       and sugarfavorites.module = \'DHA_PlantillasDocumentos\' 
                       and sugarfavorites.assigned_user_id = \'{0}\' ',  
      'db_field'=>array('id')
   );
   
   // Bug de sugar. No se desde que version se produce. Todos los módulos propios de Sugar tienen "= \'{0}\'", pero en cambio se necesita "= {0}". Se corrije solo para la version 7.2.0
   if (version_compare($sugar_version, '7.2.0', '==')) {
      $searchFields [$module_name]['favorites_only']['subquery'] = str_replace("'{0}'", "{0}", $searchFields [$module_name]['favorites_only']['subquery']);
   }
}  

?>
